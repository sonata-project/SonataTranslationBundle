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
use Twig\Environment;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 * @author Rolland Csatari <rolland.csatari@gmail.com>
 */
class LocaleSwitcherBlockService extends AbstractBlockService
{
    /**
     * @var bool
     */
    private $showCountryFlags;

    /**
     * @var bool
     */
    private $display;

    /**
     * @var string
     */
    private $template;

    /**
     * NEXT_MAJOR: Remove `$templating` argument.
     *
     * @param Environment|string $templatingOrDeprecatedName
     */
    public function __construct(
        $templatingOrDeprecatedName = null,
        ?EngineInterface $templating = null,
        ?bool $showCountryFlags = false,
        ?bool $display = true,
        ?string $template = null
    ) {
        parent::__construct($templatingOrDeprecatedName, $templating);
        $this->showCountryFlags = $showCountryFlags;
        $this->display = $display;
        $this->template = $template;
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
                'template' => $this->template ?? '@SonataTranslation/Block/block_locale_switcher.html.twig',
                'locale_switcher_route' => null,
                'locale_switcher_route_parameters' => [],
                'locale_switcher_show_country_flags' => $this->showCountryFlags,
                'display' => $this->display,
            ]
        );
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null)
    {
        return $this->renderPrivateResponse($blockContext->getTemplate(), [
            'block_context' => $blockContext,
            'block' => $blockContext->getBlock(),
        ], $response);
    }
}
