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
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

#[ORM\Entity]
class GedmoCategory implements Translatable, \Stringable
{
    #[Gedmo\Locale]
    public ?string $locale = null;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column]
        #[ORM\GeneratedValue(strategy: 'NONE')]
        private string $id = '',
        #[Gedmo\Translatable(fallback: true)]
        #[ORM\Column]
        private string $name = ''
    ) {
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
