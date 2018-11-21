<?php

declare(strict_types=1);

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

trait IdColumnTrait
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @JMS\Expose
     */
    protected $id;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getIdOrThrow(): int
    {
        if (!$this->id) {
            throw new \RuntimeException(sprintf(
                'Entity `%s` is not flushed, therefore its id is not known yet',
                __CLASS__
            ));
        }

        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function resetId()
    {
        $this->id = null;
    }
}
