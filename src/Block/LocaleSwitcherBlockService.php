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

namespace Sonata\TranslationBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class LocaleSwitcherBlockService extends AbstractBlockService
{
    /**
     * @var bool
     */
    private $showCountryFlags;

    public function __construct(
        ?string $name = null,
        EngineInterface $templating = null,
        ?bool $showCountryFlags = false
    ) {
        parent::__construct($name, $templating);
        $this->showCountryFlags = $showCountryFlags;
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
     * @deprecated since 3.x, will be removed in 4.0
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $this->configureSettings($resolver);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'admin' => null,
                'object' => null,
                'template' => '@SonataTranslation/Block/block_locale_switcher.html.twig',
                'locale_switcher_route' => null,
                'locale_switcher_route_parameters' => [],
                'locale_switcher_show_country_flags' => $this->showCountryFlags,
            ]
        );
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        return $this->renderPrivateResponse($blockContext->getTemplate(), [
            'block_context' => $blockContext,
            'block' => $blockContext->getBlock(),
        ], $response);
    }
}
