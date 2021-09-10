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

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/** @ORM\Entity */
class KnpCategory implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     *
     * @var string
     */
    private $id;

    public function __construct(string $id = '', string $name = '')
    {
        $this->id = $id;
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
        return $this->translate(null, true)->getName();
    }

    /**
     * @psalm-suppress UndefinedInterfaceMethod
     */
    public function setName(string $name): void
    {
        $this->translate(null, true)->setName($name);
    }
}
