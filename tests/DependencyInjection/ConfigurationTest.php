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

namespace Sonata\TranslationBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sonata\TranslationBundle\DependencyInjection\Configuration;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    public function testDefaultConfiguration(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'default_locale' => 'en',
                'default_filter_mode' => 'gedmo',
                'locale_switcher' => false,
                'locales' => [],
                'gedmo' => [
                    'enabled' => false,
                    'implements' => [],
                    'instanceof' => [],
                ],
                'knplabs' => [
                    'enabled' => false,
                    'implements' => [],
                    'instanceof' => [],
                ],
            ]
        );
    }

    protected function getConfiguration(): Configuration
    {
        return new Configuration();
    }
}
