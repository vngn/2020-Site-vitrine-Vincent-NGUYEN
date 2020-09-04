<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as  Gedmo;

/**
 * @ORM\Entity(repositoryClass=CategoriesRepository::class)
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="categories")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="categories")
     */
    private $blog;

    /**
     * @ORM\OneToMany(targetEntity=Portfolio::class, mappedBy="categories")
     */
    private $portfolio;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->blog = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(self $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(self $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            // set the owning side to null (unless already changed)
        }

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setCategories($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getCategories() === $this) {
                $article->setCategories(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlog(): Collection
    {
        return $this->blog;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blog->contains($blog)) {
            $this->blog[] = $blog;
            $blog->setCategories($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blog->contains($blog)) {
            $this->blog->removeElement($blog);
            // set the owning side to null (unless already changed)
            if ($blog->getCategories() === $this) {
                $blog->setCategories(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Portfolio[]
     */
    public function getPortfolio(): Collection
    {
        return $this->portfolio;
    }

    public function addPortfolio(Portfolio $portfolio): self
    {
        if (!$this->portfolio->contains($portfolio)) {
            $this->portfolio[] = $portfolio;
            $portfolio->setCategories($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        if ($this->portfolio->contains($portfolio)) {
            $this->portfolio->removeElement($portfolio);
            // set the owning side to null (unless already changed)
            if ($portfolio->getCategories() === $this) {
                $portfolio->setCategories(null);
            }
        }

        return $this;
    }
}
