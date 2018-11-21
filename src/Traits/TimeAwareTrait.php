<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

trait TimeAwareTrait
{
    /**
     * @var DateTimeInterface
     *
     * @JMS\Expose
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var DateTimeInterface
     *
     * @JMS\Expose
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Set created.
     *
     * @param DateTimeInterface|null $created
     *
     * @return mixed
     */
    public function setCreated(DateTimeInterface $created = null)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created.
     *
     * @return DateTimeInterface
     */
    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Set updated.
     *
     * @param DateTimeInterface|null $updated
     *
     * @return mixed
     */
    public function setUpdated(DateTimeInterface $updated = null)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated.
     *
     * @return DateTimeInterface $updated
     */
    public function getUpdated(): DateTimeInterface
    {
        return $this->updated;
    }
}
