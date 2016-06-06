<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Block;

use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class LocaleSwitcherBlockService extends BaseBlockService
{
    /**
     * @deprecated Will be removed when upgrading to SonataBlockBundle 3
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $this->configureSettings($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'admin' => null,
                'object' => null,
                'template' => 'SonataTranslationBundle:Block:block_locale_switcher.html.twig',
                'locale_switcher_route' => null,
                'locale_switcher_route_parameters' => array(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderPrivateResponse($blockContext->getTemplate(), array(
            'block_context' => $blockContext,
            'block' => $blockContext->getBlock(),
        ), $response);
    }
}
