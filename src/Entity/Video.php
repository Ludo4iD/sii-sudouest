<?php

namespace App\Entity;

use App\Repository\BookmarkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class Video extends Bookmark
{
    #[ORM\Column]
    #[Groups(["getBookmarks"])]
    private ?int $width = null;

    #[ORM\Column]
    #[Groups(["getBookmarks"])]
    private ?int $height = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getBookmarks"])]
    private ?string $duration = null;

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
    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;
        return $this;
    }
}
