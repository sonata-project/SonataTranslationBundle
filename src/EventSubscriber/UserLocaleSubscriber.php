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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

/**
 * Stores the locale of the user in the session after the
 * login. This can be used by the LocaleSubscriber afterwards.
 *
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
final class UserLocaleSubscriber implements EventSubscriberInterface
{
    public function onInteractiveLogin(InteractiveLoginEvent $event): void
    {
        if (!$event->getRequest()->hasPreviousSession()) {
            return;
        }

        $user = $event->getAuthenticationToken()->getUser();

        if ($user instanceof UserInterface && \is_callable([$user, 'getLocale']) && null !== $user->getLocale()) {
            $event->getRequest()->getSession()->set('_locale', $user->getLocale());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
        ];
    }
}
