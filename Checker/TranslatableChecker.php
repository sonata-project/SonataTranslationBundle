<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Checker;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class TranslatableChecker
{
    /**
     * @var array
     */
    protected $supportedInterfaces = array();

    /**
     * @var array
     */
    protected $supportedModels = array();

    /**
     * @param array $supportedInterfaces
     */
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
        if ($object === null) {
            return false;
        }

        if (function_exists('class_uses')) {
            $traits = class_uses($object);
            if (in_array('Sonata\TranslationBundle\Traits\Translatable', $traits)) {
                return true;
            }
            if (in_array('Sonata\TranslationBundle\Traits\Gedmo\PersonalTranslatable', $traits)) {
                return true;
            }
        }

        $objectInterfaces = class_implements($object);
        foreach ($this->getSupportedInterfaces() as $interface) {
            if (in_array($interface, $objectInterfaces)) {
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
