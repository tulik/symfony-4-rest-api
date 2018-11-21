<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Exception\ApiException;
use App\Form\Filter\UserFilter;
use App\Form\UserType;
use App\Interfaces\ControllerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/users")
 */
class UserController extends AbstractController implements ControllerInterface
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct(User::class);
    }

    /**
     * Get all Users.
     *
     * @Route(name="api_user_list", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the list of users",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=User::class))
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
                UserFilter::class
            )
        );
    }

    /**
     * Show single Users.
     *
     * @Route(path="/{user}", name="api_user_show", methods={Request::METHOD_GET})
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Returns user of given identifier.",
     *     @SWG\Schema(
     *         type="object",
     *         title="user",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
     *
     * @param User|null $user
     *
     * @return JsonResponse
     */
    public function showAction(User $user = null): JsonResponse
    {
        if (false === !!$user) {
            return $this->createNotFoundResponse();
        }

        return $this->createResourceResponse($user);
    }

    /**
     * Add new User.
     *
     * @Route(name="api_user_create", methods={Request::METHOD_POST})
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Updates User of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
     *
     * @param Request $request
     * @param User|null $user
     *
     * @throws \App\Exception\FormInvalidException
     *
     * @return JsonResponse
     */
    public function createAction(Request $request, User $user = null): JsonResponse
    {
        if (false === !!$user) {
            $user = new User();
        }

        $form = $this->getForm(
            UserType::class,
            $user,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getData(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($user, Response::HTTP_CREATED);
    }

    /**
     * Edit existing User.
     *
     * @Route(path="/{user}", name="api_user_edit", methods={Request::METHOD_PATCH})
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Updates User of given identifier and returns the updated object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )*
     *
     * @param Request $request
     * @param User|null $user
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_UPDATE_USER', user)")
     */
    public function updateAction(Request $request, User $user = null): JsonResponse
    {
        if (false === !!$user) {
            return $this->createNotFoundResponse();
        }

        $form = $this->getForm(
            UserType::class,
            $user,
            [
                'method' => $request->getMethod(),
            ]
        );

        try {
            $this->formHandler->process($request, $form);
        } catch (ApiException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return $this->createResourceResponse($user);
    }

    /**
     * Delete User.
     *
     * @Route(path="/{user}", name="api_user_delete", methods={Request::METHOD_DELETE})
     *
     * @SWG\Tag(name="User")
     * @SWG\Response(
     *     response=200,
     *     description="Delete User of given identifier and returns the empty object.",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Items(ref=@Model(type=User::class))
     *     )
     * )
     *
     * @param User $user
     *
     * @return JsonResponse
     *
     * @Security("is_granted('CAN_DELETE_USER', user)")
     */
    public function deleteAction(User $user = null): JsonResponse
    {
        if (false === !!$user) {
            return $this->createNotFoundResponse();
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->createSuccessfulApiResponse(self::DELETED);
    }
}
