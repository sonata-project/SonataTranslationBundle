<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Twig\Extension;

use Sonata\TranslationBundle\Checker\TranslatableChecker;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationExtension extends \Twig_Extension
{
    /**
     * @var TranslatableChecker
     */
    protected $translatableChecker;

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function __construct(TranslatableChecker $translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_translation';
    }

    /**
     * @param TranslatableChecker $translatableChecker
     */
    public function setTranslatableChecker($translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * @return TranslatableChecker
     */
    public function getTranslatableChecker()
    {
        return $this->translatableChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('translatable', array($this, 'isTranslatable')),
        );
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
        return $this->getTranslatableChecker()->isTranslatable($object);
    }
}
