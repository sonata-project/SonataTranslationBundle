<?php
/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sonata\TranslationBundle\Twig\Extension;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class SonataTranslationExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sonata_translation';
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
     * Check if $object is translatable
     *
     * @param  mixed $object
     * @return bool
     */
    public function isTranslatable($object)
    {
        if (is_null($object)) {
            return false;
        }

        return (
            in_array(
                'Sonata\TranslationBundle\Model\TranslatableInterface',
                class_implements($object)
            )
        );
    }
}
