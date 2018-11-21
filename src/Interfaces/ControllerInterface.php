<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Interfaces;

use JMS\Serializer\SerializationContext;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ControllerInterface
{
    /**
     * @param Pagerfanta $paginator
     *
     * @return JsonResponse
     */
    public function createCollectionResponse(PagerFanta $paginator): JsonResponse;

    /**
     * @param $resource
     * @param int $status
     * @param SerializationContext|null $context
     *
     * @return JsonResponse
     */
    public function createResourceResponse($resource, $status = Response::HTTP_OK, SerializationContext $context = null): JsonResponse;

    /**
     * @param array $data
     * @param int $status
     *
     * @return mixed
     */
    public function createSuccessfulApiResponse(array $data, $status = Response::HTTP_OK);

    /**
     * @return JsonResponse
     */
    public function createNotFoundResponse(): JsonResponse;

    /**
     * @return JsonResponse
     */
    public function createGenericErrorResponse(): JsonResponse;

    /**
     * @param Request $request
     * @param string $filterForm
     *
     * @return Pagerfanta
     */
    public function handleFilterForm(Request $request, string $filterForm): Pagerfanta;

    /**
     * @param string $type
     * @param array|null $data
     * @param array $options
     *
     * @return FormInterface
     */
    public function getForm(string $type, $data = null, array $options = []): FormInterface;
}
