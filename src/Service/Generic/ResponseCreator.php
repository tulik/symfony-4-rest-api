<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Service\Generic;

use App\Resource\PaginationResource;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseCreator
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var SerializationService
     */
    protected $serializationService;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var PaginationResource
     */
    protected $pagination;

    /**
     * ResponseCreator constructor.
     *
     * @param ObjectManager $objectManager
     * @param SerializationService $serializationService
     * @param SerializerInterface $serializer
     */
    public function __construct(
        ObjectManager $objectManager,
        SerializationService $serializationService,
        SerializerInterface $serializer
    ) {
        $this->objectManager = $objectManager;
        $this->serializationService = $serializationService;
        $this->serializer = $serializer;
        $this->data = [];
        $this->pagination = null;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     * @param paginationResource $pagination
     */
    public function setCollectionData(array $data, PaginationResource $pagination): void
    {
        $this->data = $data;
        $this->pagination = $pagination;
    }

    /**
     * @param int $code
     * @param string $className
     *
     * @return JsonResponse
     */
    public function getResponse(int $code, string $className = null): JsonResponse
    {
        $context = $this->serializationService->createBaseOnRequest();

        $response = new JsonResponse(null, $code);
        $response->setContent($this->serializer->serialize($this->buildResponse($className), 'json', $context));

        return $response;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    protected function buildResponse(string $className = null): ?array
    {
        if (null === $className) {
            return $this->data;
        }

        $responseArray = [];
        $responseArray[$className] = $this->data ?: new \stdClass();

        if (null !== $this->pagination) {
            $responseArray['pagination'] = $this->pagination->toJsArray();
        }

        return $responseArray;
    }
}
