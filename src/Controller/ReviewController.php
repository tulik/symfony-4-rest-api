<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Review;
use App\Exception\ApiException;
use App\Form\Filter\ReviewFilter;
use App\Form\ReviewType;
use App\Interfaces\ControllerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/reviews")
 */
class ReviewController extends AbstractController implements ControllerInterface
{
    /**
     * ReviewController constructor.
     */
    public function __construct()
    {
        parent::__construct(Review::class);
    }

    /**
     * Get all Reviews.
     *
     * @Route(name="api_review_list", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Review")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of reviews",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Review::class))
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
                ReviewFilter::class
            )
        );
    }

    /**
     * Show single Reviews.
     *
     * @Route(path="/{review}", name="api_review_show", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="Review")
     * @SWG\Response(
     *     response=200,
     *     description="Returns review of given identifier.",
     *     @SWG\Schema(
     *         type="object",
     *         title="review",
     *         @SWG\Items(ref=@Model(type=Review::class))
     *     )
     * )
     *
     * @param Review|null $review
     *
     * @return JsonResponse
     */
    public function showAction(Review $review = null): JsonResponse
    {
        if (false === !!$review) {
            return $this->createNotFoundResponse();
        }

        return $this->createResourceResponse($review);
    }

    /**
     * Add new Review.
     *
     * @Route(name="api_review_create", methods={Request::METHOD_POST})
     *
     * @SWG\Tag(name="Review")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Review of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Review::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Review $review
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_CREATE_REVIEW', review)")
     */
    public function createAction(Request $request, Review $review = null): JsonResponse
    {
        if (false === !!$review) {
            $review = new Review();
            $review->setAuthor($this->getUser());
        }

        $form = $this->getForm(
            ReviewType::class,
            $review,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getData(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($review, Response::HTTP_CREATED);
    }

    /**
     * Edit existing Review.
     *
     * @Route(path="/{review}", name="api_review_edit", methods={Request::METHOD_PATCH})
     *
     * @SWG\Tag(name="Review")
     * @SWG\Response(
     *     response=200,
     *     description="Updates Review of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Review::class))
     *     )
     * )
     *
     * @param Request $request
     * @param Review|null $review
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_UPDATE_REVIEW', review)")
     */
    public function updateAction(Request $request, Review $review = null): JsonResponse
    {
        if (false === !!$review) {
            return $this->createNotFoundResponse();
        }

        $form = $this->getForm(
            ReviewType::class,
            $review,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($review);
    }

    /**
     * Delete Review.
     *
     * @Route(path="/{review}", name="api_review_delete", methods={Request::METHOD_DELETE})
     *
     * @SWG\Tag(name="Review")
     * @SWG\Response(
     *     response=200,
     *     description="Delete Review of given identifier and returns the empty object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=Review::class))
     *     )
     * )
     *
     * @param Review|null $review
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_DELETE_REVIEW', review)")
     */
    public function deleteAction(Review $review = null): JsonResponse
    {
        if (false === !!$review) {
            return $this->createNotFoundResponse();
        }

        $this->entityManager->remove($review);
        $this->entityManager->flush();

        return $this->createSuccessfulApiResponse(self::DELETED);
    }
}
