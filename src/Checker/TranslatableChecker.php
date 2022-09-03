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

namespace Sonata\TranslationBundle\Checker;

/**
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
final class TranslatableChecker
{
    /**
     * @var string[]
     *
     * @phpstan-var class-string[]
     */
    private array $supportedInterfaces = [];

    /**
     * @var string[]
     *
     * @phpstan-var class-string[]
     */
    private array $supportedModels = [];

    /**
     * @param string[] $supportedInterfaces
     *
     * @phpstan-param class-string[] $supportedInterfaces
     */
    public function setSupportedInterfaces(array $supportedInterfaces): void
    {
        $this->supportedInterfaces = $supportedInterfaces;
    }

    /**
     * @return string[]
     *
     * @phpstan-return class-string[]
     */
    public function getSupportedInterfaces(): array
    {
        return $this->supportedInterfaces;
    }

    /**
     * @param string[] $supportedModels
     *
     * @phpstan-param class-string[] $supportedModels
     */
    public function setSupportedModels(array $supportedModels): void
    {
        $this->supportedModels = $supportedModels;
    }

    /**
     * @return string[]
     *
     * @phpstan-return class-string[]
     */
    public function getSupportedModels(): array
    {
        return $this->supportedModels;
    }

    /**
     * Check if $object is translatable.
     *
     * @param object|string|null $object
     *
     * @phpstan-param object|class-string|null $object
     */
    public function isTranslatable($object): bool
    {
        if (null === $object) {
            return false;
        }

        foreach ($this->getSupportedClasses() as $interface) {
            if (is_a($object, $interface, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     *
     * @phpstan-return class-string[]
     */
    private function getSupportedClasses(): array
    {
        return array_merge($this->getSupportedInterfaces(), $this->getSupportedModels());
    }
}
