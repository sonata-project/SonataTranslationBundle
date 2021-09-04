<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Tests\Traits;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\TranslatableListener;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\TranslationBundle\Enum\TranslationFilterMode;
use Sonata\TranslationBundle\Filter\TranslationFieldFilter;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslatable;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\ArticlePersonalTranslation;

final class GedmoOrmTest extends DoctrineOrmTestCase
{
    public const ARTICLE = ArticlePersonalTranslatable::class;
    public const TRANSLATION = ArticlePersonalTranslation::class;

    /** @var TranslatableListener */
    private $translatableListener;

    protected function setUp(): void
    {
        parent::setUp();

        if (!class_exists(EntityManager::class)) {
            static::markTestSkipped('Doctrine ORM is not available.');
        }

        $evm = new EventManager();
        $this->translatableListener = new TranslatableListener();
        $this->translatableListener->setTranslatableLocale('en');
        $this->translatableListener->setDefaultLocale('en');
        $evm->addEventSubscriber($this->translatableListener);

        $this->getMockSqliteEntityManager($evm);
    }

    public function testPersonalTranslatableEntity(): void
    {
        $article = new ArticlePersonalTranslatable();
        $article->setTitle('en');

        $this->em->persist($article);
        $this->em->flush();

        $this->translatableListener->setTranslatableLocale('de');
        $article->setTitle('de');

        $ltTranslation = new ArticlePersonalTranslation();
        $ltTranslation
            ->setField('title')
            ->setContent('lt')
            ->setObject($article)
            ->setLocale('lt');
        $this->em->persist($ltTranslation);
        $this->em->persist($article);
        $this->em->flush();

        // Tests if Gedmo\Locale annotation exists
        $article->setLocale('pl');
        $article->setTitle('pl');
        $this->em->persist($article);
        $this->em->flush();

        $this->em->clear();

        $article = $this->em->find(self::ARTICLE, ['id' => 1]);
        \assert($article instanceof ArticlePersonalTranslatable);
        $translations = $article->getTranslations();
        static::assertCount(3, $translations);
    }

    public function testTranslationFieldFilter(): void
    {
        $qb = $this->em->createQueryBuilder()
                       ->select('o')
                       ->from(self::ARTICLE, 'o');
        $builder = new ProxyQuery($qb);

        $filter = new TranslationFieldFilter(TranslationFilterMode::GEDMO);
        $filter->initialize('title');

        $filter->filter($builder, 'o', 'title', ['type' => null, 'value' => 'foo']);
        static::assertSame(
            'SELECT o FROM '.self::ARTICLE.' o LEFT JOIN o.translations tff'
            ." WHERE (tff.field = 'title' AND tff.content LIKE '%foo%') OR o.title LIKE '%foo%'",
            $builder->getDQL()
        );
        static::assertTrue($filter->isActive());
    }

    public function testTranslationFieldFilterWithoutValue(): void
    {
        $qb = $this->em->createQueryBuilder()
                       ->select('o')
                       ->from(self::ARTICLE, 'o');
        $builder = new ProxyQuery($qb);

        $filter = new TranslationFieldFilter(TranslationFilterMode::GEDMO);
        $filter->initialize('title');

        $filter->filter($builder, 'o', 'title', ['type' => null, 'value' => null]);
        static::assertSame(
            'SELECT o FROM '.self::ARTICLE.' o',
            $builder->getDQL()
        );
        static::assertFalse($filter->isActive());
    }

    public function testTranslationFieldFilterIfAlreadyJoined(): void
    {
        $qb = $this->em->createQueryBuilder()
                       ->select('o')
                       ->from(self::ARTICLE, 'o')
                       ->leftJoin('o.translations', 'tff');
        $builder = new ProxyQuery($qb);

        $filter = new TranslationFieldFilter(TranslationFilterMode::GEDMO);
        $filter->initialize('title');

        $filter->filter($builder, 'o', 'title', ['type' => null, 'value' => 'foo']);
        static::assertSame(
            'SELECT o FROM '.self::ARTICLE.' o LEFT JOIN o.translations tff'
            ." WHERE (tff.field = 'title' AND tff.content LIKE '%foo%') OR o.title LIKE '%foo%'",
            $builder->getDQL()
        );
        static::assertTrue($filter->isActive());
    }

    protected function getUsedEntityFixtures(): array
    {
        return [
            self::ARTICLE,
            self::TRANSLATION,
        ];
    }
}
