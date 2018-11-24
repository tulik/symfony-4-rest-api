<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Traits\IdColumnTrait;
use App\Traits\TimeAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 *
 * @UniqueEntity({"isbn"}, message="Book with this ISBN already exists,")
 *
 * @JMS\ExclusionPolicy("ALL")
 */
class Book
{
    use IdColumnTrait;
    use TimeAwareTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $isbn;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $author;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $publicationDate;

    /**
     * @var ArrayCollection|Review[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="book", cascade={"all"})
     *
     * @JMS\Expose
     * @JMS\Groups("reviews")
     */
    protected $reviews;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="books", cascade={"all"})
     *
     * @JMS\Expose
     * @JMS\Groups("readers")
     */
    protected $readers;

    /**
     * Book constructor.
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->readers = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     *
     * @return Book
     */
    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Book
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Book
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publicationDate;
    }

    /**
     * @param \DateTimeInterface $publicationDate
     *
     * @return Book
     */
    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return Collection|Review[]
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * @param Review $review
     *
     * @return Book
     */
    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setBook($this);
        }

        return $this;
    }

    /**
     * @param Review $review
     *
     * @return Book
     */
    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getBook() === $this) {
                $review->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getReaders(): Collection
    {
        return $this->readers;
    }

    /**
     * @param User $reader
     *
     * @return Book
     */
    public function addReader(User $reader): self
    {
        if (!$this->readers->contains($reader)) {
            $this->readers[] = $reader;
            $reader->addBook($this);
        }

        return $this;
    }

    /**
     * @param User $reader
     *
     * @return Book
     */
    public function removeReader(User $reader): self
    {
        if ($this->readers->contains($reader)) {
            $this->readers->removeElement($reader);
            $reader->removeBook($this);
        }

        return $this;
    }
}
