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

namespace Sonata\TranslationBundle\Action;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Jonathan Vautrin <jvautrin@pro-info.be>
 */
final class SwitchLocaleAction
{
    /**
     * @return RedirectResponse
     */
    public function __invoke(Request $request, $locale)
    {
        $request->getSession()->set('_locale', $locale);

        return new RedirectResponse($request->headers->get('referer', '/'));
    }
}
