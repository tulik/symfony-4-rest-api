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
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 *
 * @JMS\ExclusionPolicy("ALL")
 */
class Movie
{
    use IdColumnTrait;
    use TimeAwareTrait;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
    protected $duration;

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
    protected $director;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Review", mappedBy="movie")
     *
     * @JMS\Expose
     * @JMS\Groups("reviews")
     */
    protected $reviews;

    /**
     * @var ArrayCollection|User[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="movies")
     *
     * @JMS\Expose
     * @JMS\Groups("audience")
     */
    protected $audience;

    /**
     * Movie constructor.
     */
    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->audience = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     *
     * @return Movie
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

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
     * @param string|null $title
     *
     * @return Movie
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
     * @return Movie
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirector(): ?string
    {
        return $this->director;
    }

    /**
     * @param string $director
     *
     * @return Movie
     */
    public function setDirector(string $director): self
    {
        $this->director = $director;

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
     * @return Movie
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
     * @return Movie
     */
    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setMovie($this);
        }

        return $this;
    }

    /**
     * @param Review $review
     *
     * @return Movie
     */
    public function removeReview(Review $review): self
    {
        if ($this->reviews->contains($review)) {
            $this->reviews->removeElement($review);
            // set the owning side to null (unless already changed)
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getAudience(): Collection
    {
        return $this->audience;
    }

    /**
     * @param User $audience
     *
     * @return Movie
     */
    public function addAudience(User $audience): self
    {
        if (!$this->audience->contains($audience)) {
            $this->audience[] = $audience;
            $audience->addMovie($this);
        }

        return $this;
    }

    /**
     * @param User $audience
     *
     * @return Movie
     */
    public function removeAudience(User $audience): self
    {
        if ($this->audience->contains($audience)) {
            $this->audience->removeElement($audience);
            $audience->removeMovie($this);
        }

        return $this;
    }
}
