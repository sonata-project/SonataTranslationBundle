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
 * @final since sonata-project/translation-bundle 2.7
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class LocaleSwitcherBlockService extends AbstractBlockService
{
    /**
     * @var bool
     */
    private $showCountryFlags;

    /**
     * NEXT_MAJOR: Change signature for (Environment $twig, bool $showCountryFlags = false).
     *
     * @param Environment|string   $templatingOrDeprecatedName
     * @param bool|EngineInterface $showCountryFlagsOrTemplating
     */
    public function __construct(
        $templatingOrDeprecatedName = null,
        $showCountryFlagsOrTemplating = null,
        ?bool $showCountryFlags = false
    ) {
        // NEXT_MAJOR: Uncomment the following 2 lines and remove the rest.
        // parent::__construct($twig);
        // $this->showCountryFlags = $showCountryFlags;

        if (\is_bool($showCountryFlagsOrTemplating)) {
            if (!$templatingOrDeprecatedName instanceof Environment) {
                throw new \TypeError(sprintf(
                    'Argument 1 passed to "%s()" must be an instance of "%s", %s given.',
                    __METHOD__,
                    Environment::class,
                    \is_object($templatingOrDeprecatedName)
                        ? 'instance of "'.\get_class($templatingOrDeprecatedName).'"'
                        : '"'.\gettype($templatingOrDeprecatedName).'"'
                ));
            }

            parent::__construct($templatingOrDeprecatedName);
            $this->showCountryFlags = $showCountryFlagsOrTemplating;
        } elseif (null === $showCountryFlagsOrTemplating || $showCountryFlagsOrTemplating instanceof EngineInterface) {
            @trigger_error(sprintf(
                'Passing "%s" as argument 2 to "%s()" is deprecated since sonata-project/translation-bundle 2.7'
                .' and will throw a "%s" error in version 3.0. You must pass a "bool" value instead.',
                null === $showCountryFlagsOrTemplating ? 'null' : EngineInterface::class,
                __METHOD__,
                \TypeError::class
            ), \E_USER_DEPRECATED);

            parent::__construct($templatingOrDeprecatedName, $showCountryFlagsOrTemplating);
            $this->showCountryFlags = $showCountryFlags ?? false;
        } else {
            throw new \TypeError(sprintf(
                'Argument 2 passed to "%s()" must be either a "bool" value, "null" or an instance of "%s", %s given.',
                __METHOD__,
                EngineInterface::class,
                \is_object($templatingOrDeprecatedName)
                    ? 'instance of "'.\get_class($templatingOrDeprecatedName).'"'
                    : '"'.\gettype($templatingOrDeprecatedName).'"'
            ));
        }
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
     * @deprecated since 3.x, will be removed in 4.0
     *
     * @return void
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $this->configureSettings($resolver);
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
                'locale_switcher_show_country_flags' => $this->showCountryFlags,
            ]
        );
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        $template = $blockContext->getTemplate();

        \assert(\is_string($template));

        return $this->renderPrivateResponse($template, [
            'block_context' => $blockContext,
            'block' => $blockContext->getBlock(),
        ], $response);
    }
}
