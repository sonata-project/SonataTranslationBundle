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

namespace Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KNPTranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Sonata\TranslationBundle\Model\TranslatableInterface;

// NEXT_MAJOR: Remove this file.
// @todo Remove check and else part when dropping support for knplabs/doctrine-behaviors < 2.0
if (interface_exists(KNPTranslatableInterface::class)) {
    class DeprecatedTranslatableEntity implements TranslatableInterface, KNPTranslatableInterface
    {
        use TranslatableTrait;

        /**
         * @var int|null
         */
        private $id;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function setLocale($locale)
        {
            $this->setCurrentLocale($locale);
        }

        public function getLocale()
        {
            return $this->getCurrentLocale();
        }
    }
} else {
    class DeprecatedTranslatableEntity implements TranslatableInterface
    {
        use Translatable;

        /**
         * @var int|null
         */
        private $id;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function setLocale($locale)
        {
            $this->setCurrentLocale($locale);
        }

        public function getLocale()
        {
            return $this->getCurrentLocale();
        }
    }
}
