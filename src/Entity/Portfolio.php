<?php

namespace App\Entity;

use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PortfolioRepository::class)
 */
class Portfolio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;
    
    /**
     * @ORM\ManyToOne(targetEntity=Categories::class, inversedBy="portfolio")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity=PortfolioComment::class, mappedBy="article", orphanRemoval=true)
     */
    private $portfolioComment;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="portfolios")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    public function __construct()
    {
        $this->portfolioComment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection|PortfolioComment[]
     */
    public function getPortfolioComment(): Collection
    {
        return $this->portfolioComment;
    }

    public function addPortfolioComment(PortfolioComment $portfolioComment): self
    {
        if (!$this->portfolioComment->contains($portfolioComment)) {
            $this->portfolioComment[] = $portfolioComment;
            $portfolioComment->setArticle($this);
        }

        return $this;
    }

    public function removePortfolioComment(PortfolioComment $portfolioComment): self
    {
        if ($this->portfolioComment->contains($portfolioComment)) {
            $this->portfolioComment->removeElement($portfolioComment);
            // set the owning side to null (unless already changed)
            if ($portfolioComment->getArticle() === $this) {
                $portfolioComment->setArticle(null);
            }
        }

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): self
    {
        $this->users = $users;

        return $this;
    }
}
