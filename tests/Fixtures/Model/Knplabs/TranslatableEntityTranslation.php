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

use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

// @todo Remove check and else part when dropping support for knplabs/doctrine-behaviors < 2.0
if (interface_exists(TranslationInterface::class)) {
    class TranslatableEntityTranslation implements TranslationInterface
    {
        use TranslationTrait;

        /**
         * @var string|null
         */
        private $title;

        public function getTitle(): ?string
        {
            return $this->title;
        }

        public function setTitle(string $title): void
        {
            $this->title = $title;
        }
    }
} else {
    class TranslatableEntityTranslation
    {
        use Translation;

        /**
         * @var string|null
         */
        private $title;

        public function getTitle(): ?string
        {
            return $this->title;
        }

        public function setTitle(string $title): void
        {
            $this->title = $title;
        }
    }
}
