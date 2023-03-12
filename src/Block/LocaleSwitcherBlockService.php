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
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
final class LocaleSwitcherBlockService extends AbstractBlockService
{
    public function __construct(
        Environment $twig,
        private LocaleProviderInterface $localeProvider
    ) {
        parent::__construct($twig);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'admin' => null,
                'object' => null,
                'template' => '@SonataTranslation/Block/block_locale_switcher.html.twig',
                'locale_switcher_route' => null,
                'locale_switcher_route_parameters' => [],
                'current_locale' => $this->localeProvider->get(),
            ]
        );
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        $template = $blockContext->getTemplate();

        \assert(\is_string($template));

        return $this->renderResponse($template, [
            'block_context' => $blockContext,
            'block' => $blockContext->getBlock(),
        ], $response);
    }
}
