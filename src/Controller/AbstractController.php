<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Interfaces\RepositoryInterface;
use App\Repository\AbstractRepository;
use App\Resource\PaginationResource;
use App\Traits\ControllerTrait;
use Doctrine\Common\Inflector\Inflector;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use JMS\Serializer\SerializationContext;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends Controller
{
    use ControllerTrait;

    public const DELETED = ['success' => 'Deleted.'];
    public const NOT_FOUND = ['error' => 'Resource not found.'];
    public const GENERAL_ERROR = ['error' => 'Something went wrong.'];

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var AbstractRepository
     */
    protected $repository;

    /**
     * BaseController constructor.
     *
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @required
     */
    public function setManager()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->entityManager->getRepository($this->entity);
    }

    /**
     * @param Pagerfanta $paginator
     *
     * @return JsonResponse
     */
    public function createCollectionResponse(PagerFanta $paginator): JsonResponse
    {
        $this->responseCreator->setCollectionData(
            $this->serializer->toArray(
                $paginator->getIterator()->getArrayCopy(),
                $this->serializationService->createBaseOnRequest()
            ),
            (new PaginationResource())->createFromPagerfanta($paginator)
        );

        return $this->responseCreator->getResponse(Response::HTTP_OK, $this->getEntityResponseField());
    }

    /**
     * @param $resource
     * @param int $status
     * @param SerializationContext|null $context
     *
     * @return JsonResponse
     */
    public function createResourceResponse($resource, $status = Response::HTTP_OK, SerializationContext $context = null): JsonResponse
    {
        if (!$context) {
            $context = $this->serializationService->createBaseOnRequest();
        }

        $this->responseCreator->setData($this->serializer->toArray($resource, $context));

        return $this->responseCreator->getResponse($status);
    }

    /**
     * @param array $data
     * @param int $status
     *
     * @return JsonResponse
     */
    public function createSuccessfulApiResponse(array $data = [], $status = Response::HTTP_OK): JsonResponse
    {
        $this->responseCreator->setData($data);

        return $this->responseCreator->getResponse($status, $this->getEntityResponseField());
    }

    /**
     * @return JsonResponse
     */
    public function createNotFoundResponse(): JsonResponse
    {
        $this->responseCreator->setData(self::NOT_FOUND);

        return $this->responseCreator->getResponse(Response::HTTP_NOT_FOUND, $this->getEntityResponseField());
    }

    /**
     * @return JsonResponse
     */
    public function createGenericErrorResponse(): JsonResponse
    {
        $this->responseCreator->setData(self::GENERAL_ERROR);

        return $this->responseCreator->getResponse(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $this->getEntityResponseField()
        );
    }

    /**
     * @param Request $request
     * @param Query $query
     *
     * @return Pagerfanta
     */
    public function createPaginator(Request $request, Query $query): Pagerfanta
    {
        //  Construct the doctrine adapter using the query.
        $adapter = new DoctrineORMAdapter($query);
        $paginator = new Pagerfanta($adapter);

        //  Set pages based on the request parameters.
        $paginator->setMaxPerPage($request->query->get('limit', 10));
        $paginator->setCurrentPage($request->query->get('page', 1));

        return $paginator;
    }

    /**
     * @param Request $request
     * @param string $filterForm
     *
     * @return Pagerfanta
     */
    public function handleFilterForm(Request $request, string $filterForm): Pagerfanta
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->getRepository();
        $queryBuilder = $repository->getQueryBuilder();

        $form = $this->getForm($filterForm);

        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));

            $queryBuilder = $this->get('lexik_form_filter.query_builder_updater')
                ->addFilterConditions($form, $queryBuilder);
        }

        $paginagor = $this->createPaginator($request, $queryBuilder->getQuery());

        return $paginagor;
    }

    /**
     * @return string
     */
    public function getEntityResponseField()
    {
        $path = explode('\\', $this->entity);

        return $this->inflector::pluralize(strtolower(array_pop($path)));
    }

    /**
     * @param string $type
     * @param $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function getForm(string $type, $data = null, array $options = []): FormInterface
    {
        $session = $this->get('session');
        $inflector = new Inflector();

        try {
            $reflectionClass = new \ReflectionClass($type);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        $name = $reflectionClass->getShortName();
        $options = array_merge($options, ['csrf_protection' => false, 'allow_extra_fields' => true]);

        if ($formData = $session->get($type, null)) {
            $data = $this->serializer
                ->deserialize($formData->getJson(), $formData->getClassName(), 'json');
        }

        $form = $this->formFactory
            ->createNamedBuilder($inflector->tableize($name), $type, $data, $options)
            ->getForm();

        return $form;
    }
}
