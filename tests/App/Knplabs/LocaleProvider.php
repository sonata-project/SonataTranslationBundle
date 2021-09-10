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

namespace Sonata\TranslationBundle\Tests\App\Knplabs;

use Knp\DoctrineBehaviors\Contract\Provider\LocaleProviderInterface;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Symfony\Component\HttpFoundation\RequestStack;

final class LocaleProvider implements LocaleProviderInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function provideCurrentLocale(): ?string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        if (null === $currentRequest) {
            return 'en';
        }

        return $currentRequest->query->get(AbstractTranslatableAdminExtension::TRANSLATABLE_LOCALE_PARAMETER);
    }

    public function provideFallbackLocale(): ?string
    {
        return 'en';
    }
}
