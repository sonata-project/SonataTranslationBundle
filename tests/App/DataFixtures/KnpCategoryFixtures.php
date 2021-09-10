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

namespace Sonata\TranslationBundle\Tests\App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sonata\TranslationBundle\Tests\App\Entity\KnpCategory;

final class KnpCategoryFixtures extends Fixture
{
    public const CATEGORY = 'category_novel';

    public function load(ObjectManager $manager): void
    {
        $novelCategory = new KnpCategory(self::CATEGORY, 'Novel');

        $novelCategory->setCurrentLocale('es');
        $novelCategory->setName('Novela');
        $novelCategory->setCurrentLocale('fr');
        $novelCategory->setName('Roman');

        $novelCategory->mergeNewTranslations();

        $manager->persist($novelCategory);
        $manager->flush();
    }
}
