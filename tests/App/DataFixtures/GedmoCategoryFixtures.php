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
use Sonata\TranslationBundle\Tests\App\Entity\GedmoCategory;

final class GedmoCategoryFixtures extends Fixture
{
    public const CATEGORY = 'category_novel';

    public function load(ObjectManager $manager): void
    {
        $novelCategory = new GedmoCategory(self::CATEGORY, 'Novel');

        $manager->persist($novelCategory);
        $manager->flush();

        $novelCategory->setLocale('es');
        $novelCategory->setName('Novela');

        $manager->flush();

        $novelCategory->setLocale('fr');
        $novelCategory->setName('Roman');

        $manager->flush();

        $this->addReference(self::CATEGORY, $novelCategory);
    }
}
