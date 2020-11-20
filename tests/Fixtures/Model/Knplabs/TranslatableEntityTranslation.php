<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;
use Knp\DoctrineBehaviors\Model\Translatable\Translation;

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
