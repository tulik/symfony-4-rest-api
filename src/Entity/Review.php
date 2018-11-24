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
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReviewRepository")
 *
 * @JMS\ExclusionPolicy("ALL")
 */
class Review
{
    use IdColumnTrait;
    use TimeAwareTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $body;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $rating;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reviews", cascade={"persist", "remove"})
     *
     * @JMS\Expose
     */
    protected $author;

    /**
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $publicationDate;

    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Book", inversedBy="reviews", cascade={"persist"})
     *
     * @JMS\Expose
     * @JMS\Groups("books")
     */
    protected $book;

    /**
     * @var Movie
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="reviews", cascade={"persist"})
     *
     * @JMS\Expose
     * @JMS\Groups("movies")
     */
    protected $movie;

    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string $body
     *
     * @return Review
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     *
     * @return Review
     */
    public function setRating(int $rating): self
    {
        $this->rating = $rating;

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
     * @return Review
     */
    public function setPublicationDate(?\DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     *
     * @return Review
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Book|null
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * @param Book|null $book
     *
     * @return Review
     */
    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    /**
     * @return Movie|null
     */
    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    /**
     * @param Movie|null $movie
     *
     * @return Review
     */
    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
