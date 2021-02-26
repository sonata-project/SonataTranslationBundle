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

namespace Sonata\TranslationBundle\Checker;

use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatable;
use Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatableTrait;
use Sonata\TranslationBundle\Traits\Translatable;
use Sonata\TranslationBundle\Traits\TranslatableTrait;

/**
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class TranslatableChecker
{
    /**
     * @var string[]
     *
     * @phpstan-var class-string[]
     */
    protected $supportedInterfaces = [];

    /**
     * @var string[]
     *
     * @phpstan-var class-string[]
     */
    protected $supportedModels = [];

    /**
     * @param string[] $supportedInterfaces
     *
     * @phpstan-param class-string[] $supportedInterfaces
     *
     * @return void
     */
    public function setSupportedInterfaces(array $supportedInterfaces)
    {
        $this->supportedInterfaces = $supportedInterfaces;
    }

    /**
     * @return string[]
     *
     * @phpstan-return class-string[]
     */
    public function getSupportedInterfaces()
    {
        return $this->supportedInterfaces;
    }

    /**
     * @param string[] $supportedModels
     * @phpstan-param class-string[] $supportedModels
     *
     * @return void
     */
    public function setSupportedModels($supportedModels)
    {
        $this->supportedModels = $supportedModels;
    }

    /**
     * @return string[]
     *
     * @phpstan-return class-string[]
     */
    public function getSupportedModels()
    {
        return $this->supportedModels;
    }

    /**
     * Check if $object is translatable.
     *
     * @param object|string|null $object
     *
     * @return bool
     */
    public function isTranslatable($object)
    {
        if (null === $object) {
            return false;
        }

        // NEXT_MAJOR: remove Translateable and PersonalTrait.
        $translateTraits = [
            Translatable::class,
            TranslatableTrait::class,
            PersonalTranslatable::class,
            PersonalTranslatableTrait::class,
        ];

        $traits = class_uses($object);
        \assert(\is_array($traits));
        if (\count(array_intersect($translateTraits, $traits)) > 0) {
            return true;
        }

        $objectInterfaces = class_implements($object);
        \assert(\is_array($objectInterfaces));
        foreach ($this->getSupportedInterfaces() as $interface) {
            if (\in_array($interface, $objectInterfaces, true)) {
                return true;
            }
        }

        foreach ($this->getSupportedModels() as $model) {
            if ($object instanceof $model) {
                return true;
            }
        }

        return false;
    }
}
