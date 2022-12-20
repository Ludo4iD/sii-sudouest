<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BookmarkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BookmarkRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'person' => Bookmark::class,
    'video' => Video::class,
    'image' => Image::class,
])]
class Bookmark
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getBookmarks'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getBookmarks'])]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getBookmarks'])]
    private ?string $provider = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getBookmarks'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getBookmarks'])]
    private ?string $author = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['getBookmarks'])]
    private ?\DateTimeInterface $createdOn = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedOn = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedOn(): ?\DateTimeInterface
    {
        return $this->createdOn;
    }

    #[ORM\PrePersist]
    public function setCreatedOn(): self
    {
        $this->createdOn = new \DateTime();

        return $this;
    }

    public function getPublishedOn(): ?\DateTimeInterface
    {
        return $this->publishedOn;
    }

    public function setPublishedOn(?\DateTimeInterface $publishedOn): self
    {
        $this->publishedOn = $publishedOn;

        return $this;
    }

    #[ORM\PrePersist]
    public function setPublishedOnPrePersist(): void
    {
        if (null === $this->publishedOn) {
            $this->publishedOn = new \DateTime();
        }
    }
}
