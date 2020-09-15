<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $firstname;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="users")
     */
    private $blog;

    /**
     * @ORM\OneToMany(targetEntity=Portfolio::class, mappedBy="users")
     */
    private $portfolio;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="users")
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity=Blog::class, mappedBy="users")
     */
    private $blogs;

    /**
     * @ORM\OneToMany(targetEntity=Portfolio::class, mappedBy="users")
     */
    private $portfolios;

    /**
     * @ORM\OneToMany(targetEntity=ArticlesComment::class, mappedBy="users")
     */
    private $articlesComments;

    /**
     * @ORM\OneToMany(targetEntity=BlogComment::class, mappedBy="users")
     */
    private $blogComments;

    /**
     * @ORM\OneToMany(targetEntity=PortfolioComment::class, mappedBy="users")
     */
    private $portfolioComments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->blog = new ArrayCollection();
        $this->portfolio = new ArrayCollection();
        $this->blogs = new ArrayCollection();
        $this->portfolios = new ArrayCollection();
        $this->articlesComments = new ArrayCollection();
        $this->blogComments = new ArrayCollection();
        $this->portfolioComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    // public function removeArticle(Articles $article): self
    // {
    //     if ($this->articles->contains($article)) {
    //         $this->articles->removeElement($article);
    //         // set the owning side to null (unless already changed)
    //         if ($article->getUsers() === $this) {
    //             $article->setUsers(null);
    //         }
    //     }

    //     return $this;
    // }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlog(): Collection
    {
        return $this->blog;
    }

    // public function addBlog(Blog $blog): self
    // {
    //     if (!$this->blog->contains($blog)) {
    //         $this->blog[] = $blog;
    //         $blog->setUsers($this);
    //     }

    //     return $this;
    // }

    // public function removeBlog(Blog $blog): self
    // {
    //     if ($this->blog->contains($blog)) {
    //         $this->blog->removeElement($blog);
    //         // set the owning side to null (unless already changed)
    //         if ($blog->getUsers() === $this) {
    //             $blog->setUsers(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $portfolio->setUsers($this);
        }

        return $this;
    }

    public function removePortfolio(Portfolio $portfolio): self
    {
        if ($this->portfolio->contains($portfolio)) {
            $this->portfolio->removeElement($portfolio);
            // set the owning side to null (unless already changed)
            if ($portfolio->getUsers() === $this) {
                $portfolio->setUsers(null);
            }
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
            $article->setUsers($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getUsers() === $this) {
                $article->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Blog[]
     */
    public function getBlogs(): Collection
    {
        return $this->blogs;
    }

    public function addBlog(Blog $blog): self
    {
        if (!$this->blogs->contains($blog)) {
            $this->blogs[] = $blog;
            $blog->setUsers($this);
        }

        return $this;
    }

    public function removeBlog(Blog $blog): self
    {
        if ($this->blogs->contains($blog)) {
            $this->blogs->removeElement($blog);
            // set the owning side to null (unless already changed)
            if ($blog->getUsers() === $this) {
                $blog->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Portfolio[]
     */
    public function getPortfolios(): Collection
    {
        return $this->portfolios;
    }

    // public function addPortfolio(Portfolio $portfolio): self
    // {
    //     if (!$this->portfolios->contains($portfolio)) {
    //         $this->portfolios[] = $portfolio;
    //         $portfolio->setUsers($this);
    //     }

    //     return $this;
    // }

    // public function removePortfolio(Portfolio $portfolio): self
    // {
    //     if ($this->portfolios->contains($portfolio)) {
    //         $this->portfolios->removeElement($portfolio);
    //         // set the owning side to null (unless already changed)
    //         if ($portfolio->getUsers() === $this) {
    //             $portfolio->setUsers(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection|ArticlesComment[]
     */
    public function getArticlesComments(): Collection
    {
        return $this->articlesComments;
    }

    public function addArticlesComment(ArticlesComment $articlesComment): self
    {
        if (!$this->articlesComments->contains($articlesComment)) {
            $this->articlesComments[] = $articlesComment;
            $articlesComment->setUsers($this);
        }

        return $this;
    }

    public function removeArticlesComment(ArticlesComment $articlesComment): self
    {
        if ($this->articlesComments->contains($articlesComment)) {
            $this->articlesComments->removeElement($articlesComment);
            // set the owning side to null (unless already changed)
            if ($articlesComment->getUsers() === $this) {
                $articlesComment->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BlogComment[]
     */
    public function getBlogComments(): Collection
    {
        return $this->blogComments;
    }

    public function addBlogComment(BlogComment $blogComment): self
    {
        if (!$this->blogComments->contains($blogComment)) {
            $this->blogComments[] = $blogComment;
            $blogComment->setUsers($this);
        }

        return $this;
    }

    public function removeBlogComment(BlogComment $blogComment): self
    {
        if ($this->blogComments->contains($blogComment)) {
            $this->blogComments->removeElement($blogComment);
            // set the owning side to null (unless already changed)
            if ($blogComment->getUsers() === $this) {
                $blogComment->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PortfolioComment[]
     */
    public function getPortfolioComments(): Collection
    {
        return $this->portfolioComments;
    }

    public function addPortfolioComment(PortfolioComment $portfolioComment): self
    {
        if (!$this->portfolioComments->contains($portfolioComment)) {
            $this->portfolioComments[] = $portfolioComment;
            $portfolioComment->setUsers($this);
        }

        return $this;
    }

    public function removePortfolioComment(PortfolioComment $portfolioComment): self
    {
        if ($this->portfolioComments->contains($portfolioComment)) {
            $this->portfolioComments->removeElement($portfolioComment);
            // set the owning side to null (unless already changed)
            if ($portfolioComment->getUsers() === $this) {
                $portfolioComment->setUsers(null);
            }
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
