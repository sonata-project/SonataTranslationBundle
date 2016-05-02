<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class LocaleSwitcherListener
{
    /**
     * @param BlockEvent $event
     */
    public function onBlock(BlockEvent $event, $eventName)
    {
        $settings = $event->getSettings();
        if ($eventName == 'sonata.block.event.sonata.admin.show.top') {
            $settings['locale_switcher_route'] = 'show';
        }

        $block = new Block();
        $block->setSettings($settings);
        $block->setName('sonata_translation.block.locale_switcher');
        $block->setType('sonata_translation.block.locale_switcher');

        $event->addBlock($block);
    }
}
