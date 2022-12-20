<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Image extends Bookmark
{
    #[ORM\Column]
    #[Groups(['getBookmarks'])]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups(['getBookmarks'])]
    private ?int $height = null;

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }
}
