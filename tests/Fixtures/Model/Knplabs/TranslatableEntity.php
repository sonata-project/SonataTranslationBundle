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

namespace Sonata\TranslationBundle\Tests\Fixtures\Model\Knplabs;

use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface as KNPTranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use Sonata\TranslationBundle\Model\TranslatableInterface;

class TranslatableEntity implements TranslatableInterface, KNPTranslatableInterface
{
    use TranslatableTrait;

    /**
     * @var int|null
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setLocale(string $locale): void
    {
        $this->setCurrentLocale($locale);
    }

    public function getLocale(): string
    {
        return $this->getCurrentLocale();
    }
}
