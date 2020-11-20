<?php

namespace Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KNPTranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Sonata\TranslationBundle\Model\TranslatableInterface;

// @todo Remove check and else part when dropping support for knplabs/doctrine-behaviors < 2.0
if (interface_exists(KNPTranslatableInterface::class)) {
    class TranslatableEntity implements TranslatableInterface, KNPTranslatableInterface
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

        public function setLocale($locale): void
        {
            $this->setCurrentLocale($locale);
        }

        public function getLocale(): string
        {
            return $this->getCurrentLocale();
        }
    }
} else {
    class TranslatableEntity implements TranslatableInterface
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

        public function setLocale($locale): void
        {
            $this->setCurrentLocale($locale);
        }

        public function getLocale(): string
        {
            return $this->getCurrentLocale();
        }
    }
}
