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

namespace Sonata\TranslationBundle\Tests\App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

#[ORM\Entity]
class KnpCategory implements TranslatableInterface, \Stringable
{
    use TranslatableTrait;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        private string $id = '',
        string $name = ''
    ) {
        $this->translations = new ArrayCollection();
        $this->newTranslations = new ArrayCollection();
        $this->setName($name);
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function getName(): ?string
    {
        return $this->translate()->getName();
    }

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function setName(string $name): void
    {
        $this->translate()->setName($name);
    }
}
