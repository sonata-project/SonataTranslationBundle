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

namespace Sonata\TranslationBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
final class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $defaultLocale = 'en')
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($request->attributes->has('_locale')) {
            $locale = (string) $request->attributes->get('_locale');
            $request->getSession()->set('_locale', $locale);

            return;
        }

        // if no explicit locale has been set on this request, use one from the session
        $request->setLocale((string) $request->getSession()->get('_locale', $this->defaultLocale));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
