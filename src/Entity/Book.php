<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Survos\CoreBundle\Entity\RouteParametersInterface;
use Survos\CoreBundle\Entity\RouteParametersTrait;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['book.export']],
    operations: [
        new GetCollection()
    ]
)]
class Book implements RouteParametersInterface
{
    public const STATUS_NOT_FOUND='not_found';
    public const STATUS_OK='ok';
    public const STATUS_MAYBE='maybe'; // isbn not found, regular search with first result

    use RouteParametersTrait;
    #[ORM\Id]
    #[ORM\Column(length: 19)]
    #[Groups(['book.export'])]
    private ?string $isbn = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book.export'])]
    private ?string $title = null;

    #[ORM\Column(nullable: true, options: ['jsonb' => true])]
    private ?array $info = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['book.export'])]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    #[ORM\JoinTable(name: 'book_author')]
    #[ORM\JoinColumn('book_isbn', 'isbn')]
    #[ORM\InverseJoinColumn(name: "author_olid", referencedColumnName: "ol_id")]
//    #[ORM\JoinColumn(referencedColumnName: 'isbn')]
    private Collection $authors;

    #[ORM\OneToMany(targetEntity: UserBook::class, mappedBy: 'book', orphanRemoval: true)]
    private Collection $userBooks;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true, options: ['jsonb' => true])]
    private ?array $openLibData = null;

    /**
     * @param string|null $isbn
     */
    public function __construct(?string $isbn)
    {
        $this->isbn = $isbn;
        $this->createdAt=new \DateTimeImmutable();
        $this->authors = new ArrayCollection();
        $this->userBooks = new ArrayCollection();
    }

    public function get(string $key): mixed
    {
        return $this->getInfo()[$key]??null;
    }

    public function getDescription(): ?string
    {
        return $this->getInfo()['description']['value']??null;
    }


    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): static
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getInfo(): ?array
    {
        return $this->info;
    }

    public function setInfo(?array $info): static
    {
        $this->info = $info;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUniqueIdentifiers(): array
    {
        return ['isbn' => $this->getIsbn()];

    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection<int, UserBook>
     */
    public function getUserBooks(): Collection
    {
        return $this->userBooks;
    }

    public function addUserBook(UserBook $userBook): static
    {
        if (!$this->userBooks->contains($userBook)) {
            $this->userBooks->add($userBook);
            $userBook->setBook($this);
        }

        return $this;
    }

    public function removeUserBook(UserBook $userBook): static
    {
        if ($this->userBooks->removeElement($userBook)) {
            // set the owning side to null (unless already changed)
            if ($userBook->getBook() === $this) {
                $userBook->setBook(null);
            }
        }

        return $this;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOpenLibData(): ?array
    {
        return $this->openLibData;
    }

    public function setOpenLibData(?array $openLibData): static
    {
        $this->openLibData = $openLibData;

        return $this;
    }
}
