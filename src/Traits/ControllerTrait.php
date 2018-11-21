<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Traits;

use App\Form\Handler\DefaultFormHandler;
use App\Service\Generic\ResponseCreator;
use App\Service\Generic\SerializationService;
use Doctrine\Common\Inflector\Inflector;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Form\FormFactoryInterface;

trait ControllerTrait
{
    /**
     * @var Inflector
     */
    protected $inflector;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var DefaultFormHandler
     */
    protected $formHandler;

    /**
     * @var ResponseCreator
     */
    protected $responseCreator;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var SerializationService
     */
    protected $serializationService;

    /**
     * @required
     *
     * @param Inflector $inflector
     */
    public function setInflector(Inflector $inflector): void
    {
        $this->inflector = $inflector;
    }

    /**
     * @required
     *
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @required
     *
     * @param DefaultFormHandler $formHandler
     */
    public function setFormHandler(DefaultFormHandler $formHandler): void
    {
        $this->formHandler = $formHandler;
    }

    /**
     * @required
     *
     * @param ResponseCreator $responseCreator
     */
    public function setResponseCreator(ResponseCreator $responseCreator): void
    {
        $this->responseCreator = $responseCreator;
    }

    /**
     * @required
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        if (!$serializer instanceof Serializer) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Serializer must be instance of %s but %s given',
                    Serializer::class,
                    \get_class($this->serializer)
                )
            );
        }

        $this->serializer = $serializer;
    }

    /**
     * @required
     *
     * @param SerializationService $serializationService
     */
    public function setSerializationService(SerializationService $serializationService): void
    {
        $this->serializationService = $serializationService;
    }
}
