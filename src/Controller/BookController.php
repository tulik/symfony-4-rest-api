<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use App\Exception\ApiException;
use App\Form\BookType;
use App\Form\Filter\BookFilter;
use App\Interfaces\ControllerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/books")
 */
class BookController extends AbstractController implements ControllerInterface
{
    /**
     * BookController constructor.
     */
    public function __construct()
    {
        parent::__construct(Book::class);
    }

    /**
     * Get all Books.
     *
     * @Route(name="api_book_list", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of books.yml",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request): JsonResponse
    {
        return $this->createCollectionResponse(
            $this->handleFilterForm(
                $request,
                BookFilter::class
            )
        );
    }

    /**
     * Show single Books.
     *
     * @Route(path="/{book}", name="api_book_show", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Returns book of given identifier.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Book|null $book
     *
     * @return JsonResponse
     */
    public function showAction(Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            return $this->createNotFoundResponse();
        }

        return $this->createResourceResponse($book);
    }

    /**
     * Add new Book.
     *
     * @Route(name="api_book_create", methods={Request::METHOD_POST})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=201,
     *     description="Creates new Book and returns the created object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_CREATE_BOOK', book)")
     */
    public function createAction(Request $request, Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            $book = new Book();
        }

        $form = $this->getForm(
            BookType::class,
            $book,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getData(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($book, Response::HTTP_CREATED);
    }

    /**
     * Edit existing Book.
     *
     * @Route(path="/{book}", name="api_book_edit", methods={Request::METHOD_PATCH})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Book of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_UPDATE_BOOK', book)")
     */
    public function updateAction(Request $request, Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            return $this->createNotFoundResponse();
        }

        $form = $this->getForm(
            BookType::class,
            $book,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($book);
    }

    /**
     * Delete Book.
     *
     * @Route(path="/{book}", name="api_book_delete", methods={Request::METHOD_DELETE})
     *
     * @SWG\Tag(name="Book")
     * @SWG\Response(
     *     response=200,
     *     description="Delete Book of given identifier and returns the empty object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Book::class))
     *     )
     * )
     *
     * @param Book|null $book
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_DELETE_BOOK', book)")
     */
    public function deleteAction(Book $book = null): JsonResponse
    {
        if (false === !!$book) {
            return $this->createNotFoundResponse();
        }

        try {
            $this->entityManager->remove($book);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            return $this->createGenericErrorResponse($exception);
        }

        return $this->createSuccessfulApiResponse(self::DELETED);
    }
}
