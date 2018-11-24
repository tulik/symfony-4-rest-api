<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="app_user")
 *
 * @UniqueEntity({"email"}, message="Email already exists.")
 *
 * @JMS\ExclusionPolicy("ALL")
 */
class User extends AbstractUser implements UserInterface
{
    /**
     * @var ArrayCollection|Book[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Book", inversedBy="readers", cascade={"persist"})
     *
     * @JMS\Expose
     * @JMS\Groups("books")
     */
    protected $books;

    /**
     * @var ArrayCollection|Movie[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", inversedBy="audience", cascade={"persist"})
     *
     * @JMS\Expose
     * @JMS\Groups("movies")
     */
    protected $movies;

    /**
     * @var ArrayCollection|Review[]
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="author", cascade={"persist"})
     *
     * @JMS\Expose
     * @JMS\Groups("reviews")
     */
    protected $reviews;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->movies = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    /**
     * @return Book[]|Collection
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * @param Book $book
     *
     * @return User
     */
    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    /**
     * @param Book $book
     *
     * @return User
     */
    public function removeBook(Book $book): self
    {
        if ($this->books->contains($book)) {
            $this->books->removeElement($book);
        }

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    /**
     * @param Movie $movie
     *
     * @return User
     */
    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
        }

        return $this;
    }

    /**
     * @param Movie $movie
     *
     * @return User
     */
    public function removeMovie(Movie $movie): self
    {
        if ($this->movies->contains($movie)) {
            $this->movies->removeElement($movie);
        }

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
     * @return User
     */
    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Review $review
     *
     * @return User
     */
    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }

        return $this;
    }
}
