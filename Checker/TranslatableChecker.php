<?php
/*
 * This file is part of the Sonata project.
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
     * Check if $object is translatable
     *
     * @param  mixed $object
     * @return bool
     */
    public function isTranslatable($object)
    {
        if ($object === null) {
            return false;
        }

        $objectTraits = class_uses($object);
        if (in_array('Sonata\TranslationBundle\Traits\Translatable', $objectTraits)) {
            return true;
        }

        $objectInterfaces = class_implements($object);
        foreach ($this->getSupportedInterfaces() as $interface) {
            if (in_array($interface, $objectInterfaces)) {
                return true;
            }
        }

        return false;
    }
} 