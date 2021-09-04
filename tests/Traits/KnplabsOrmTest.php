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
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs\Article;
use Sonata\TranslationBundle\Tests\Fixtures\Traits\ORM\Knplabs\ArticleTranslation;

/**
 * @author Omar Jbara <omar.jbara2@gmail.com>
 */
final class KnplabsOrmTest extends DoctrineOrmTestCase
{
    private const ARTICLE = Article::class;
    private const TRANSLATION = ArticleTranslation::class;

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

    public function testTranslationFieldFilter(): void
    {
        $qb = $this->em->createQueryBuilder()
            ->select('a')
            ->from(self::ARTICLE, 'a');
        $builder = new ProxyQuery($qb);

        $filter = new TranslationFieldFilter(TranslationFilterMode::KNPLABS);
        $filter->initialize('title');

        $filter->filter($builder, 'a', 'title', ['type' => null, 'value' => 'foo']);
        static::assertSame(
            'SELECT a FROM '.self::ARTICLE.' a LEFT JOIN a.translations tff'
            ." WHERE tff.title LIKE '%foo%'",
            $builder->getDQL()
        );
        static::assertTrue($filter->isActive());
    }

    public function testTranslationFieldFilterWithoutValue(): void
    {
        $qb = $this->em->createQueryBuilder()
            ->select('a')
            ->from(self::ARTICLE, 'a');
        $builder = new ProxyQuery($qb);

        $filter = new TranslationFieldFilter(TranslationFilterMode::KNPLABS);
        $filter->initialize('title');

        $filter->filter($builder, 'a', 'title', ['type' => null, 'value' => null]);
        static::assertSame(
            'SELECT a FROM '.self::ARTICLE.' a',
            $builder->getDQL()
        );
        static::assertFalse($filter->isActive());
    }

    public function testTranslationFieldFilterIfAlreadyJoined(): void
    {
        $qb = $this->em->createQueryBuilder()
            ->select('a')
            ->from(self::ARTICLE, 'a')
            ->leftJoin('a.translations', 'tff');
        $builder = new ProxyQuery($qb);

        $filter = new TranslationFieldFilter(TranslationFilterMode::KNPLABS);
        $filter->initialize('title');

        $filter->filter($builder, 'a', 'title', ['type' => null, 'value' => 'foo']);
        static::assertSame(
            'SELECT a FROM '.self::ARTICLE.' a LEFT JOIN a.translations tff'
            ." WHERE tff.title LIKE '%foo%'",
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
