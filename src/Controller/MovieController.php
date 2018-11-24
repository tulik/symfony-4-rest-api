<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use App\Exception\ApiException;
use App\Form\Filter\MovieFilter;
use App\Form\MovieType;
use App\Interfaces\ControllerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/movies")
 */
class MovieController extends AbstractController implements ControllerInterface
{
    /**
     * MovieController constructor.
     */
    public function __construct()
    {
        parent::__construct(Movie::class);
    }

    /**
     * Get all Movies.
     *
     * @Route(name="api_movie_list", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Movie")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of movies",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Movie::class))
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
                MovieFilter::class
            )
        );
    }

    /**
     * Show single Movies.
     *
     * @Route(path="/{movie}", name="api_movie_show", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Movie")
     * @SWG\Response(
     *     response=200,
     *     description="Returns movie of given identifier.",
     *     @SWG\Schema(
     *         type="object",
     *         title="movie",
     *         @SWG\Items(ref=@Model(type=Movie::class))
     *     )
     * )
     *
     * @param Movie|null $movie
     *
     * @return JsonResponse
     */
    public function showAction(Movie $movie = null): JsonResponse
    {
        if (false === !!$movie) {
            return $this->createNotFoundResponse();
        }

        return $this->createResourceResponse($movie);
    }

    /**
     * Add new Movie.
     *
     * @Route(name="api_movie_create", methods={Request::METHOD_POST})
     *
     * @SWG\Tag(name="Movie")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Movie of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Movie::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Movie|null $movie
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_CREATE_MOVIE', movie)")
     */
    public function createAction(Request $request, Movie $movie = null): JsonResponse
    {
        if (false === !!$movie) {
            $movie = new Movie();
        }

        $form = $this->getForm(
            MovieType::class,
            $movie,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($movie, Response::HTTP_CREATED);
    }

    /**
     * Edit existing Movie.
     *
     * @Route(path="/{movie}", name="api_movie_edit", methods={Request::METHOD_PATCH})
     *
     * @SWG\Tag(name="Movie")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Movie of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Movie::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Movie|null $movie
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_UPDATE_MOVIE', movie)")
     */
    public function updateAction(Request $request, Movie $movie = null): JsonResponse
    {
        if (false === !!$movie) {
            return $this->createNotFoundResponse();
        }

        $form = $this->getForm(
            MovieType::class,
            $movie,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($movie);
    }

    /**
     * Delete Movie.
     *
     * @Route(path="/{movie}", name="api_movie_delete", methods={Request::METHOD_DELETE})
     *
     * @SWG\Tag(name="Movie")
     * @SWG\Response(
     *     response=200,
     *     description="Delete Movie of given identifier and returns the empty object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Movie::class))
     *     )
     * )
     *
     * @param Movie|null $movie
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_DELETE_MOVIE', movie)")
     */
    public function deleteAction(Movie $movie = null): JsonResponse
    {
        if (false === !!$movie) {
            return $this->createNotFoundResponse();
        }

        try {
            $this->entityManager->remove($movie);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            return $this->createGenericErrorResponse($exception);
        }

        return $this->createSuccessfulApiResponse(self::DELETED);
    }
}
