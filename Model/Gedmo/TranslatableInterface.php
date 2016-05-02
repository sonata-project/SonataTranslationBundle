<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Model\Gedmo;

use Sonata\TranslationBundle\Model\TranslatableInterface as GenericTranslatableInterface;

/**
 * This is a Convenient interface made to easily plug Gedmo admin extension on models.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
interface TranslatableInterface extends GenericTranslatableInterface
{
}
