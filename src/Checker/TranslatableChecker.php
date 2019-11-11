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
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class TranslatableChecker
{
    /**
     * @var array
     */
    protected $supportedInterfaces = [];

    /**
     * @var array
     */
    protected $supportedModels = [];

    public function setSupportedInterfaces(array $supportedInterfaces)
    {
        $this->supportedInterfaces = $supportedInterfaces;
    }

    /**
     * @return array
     */
    public function getSupportedInterfaces()
    {
        return $this->supportedInterfaces;
    }

    /**
     * @param array $supportedModels
     */
    public function setSupportedModels($supportedModels)
    {
        $this->supportedModels = $supportedModels;
    }

    /**
     * @return array
     */
    public function getSupportedModels()
    {
        return $this->supportedModels;
    }

    /**
     * Check if $object is translatable.
     *
     * @param mixed $object
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
        if (\count(array_intersect($translateTraits, $traits)) > 0) {
            return true;
        }

        $objectInterfaces = class_implements($object);
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
