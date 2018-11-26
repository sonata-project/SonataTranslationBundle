<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Locale controller.
 *
 * @author Jonathan Vautrin <jonathan.vautrin@gmail.com>
 */
final class LocaleController
{
    /**
     * Switch current locale.
     *
     * @return RedirectResponse
     */
    public function index(Request $request, string $locale): RedirectResponse
    {
        $request->getSession()->set('_locale', $locale);
        return new RedirectResponse($request->headers->get('referer', '/'));
    }
}
