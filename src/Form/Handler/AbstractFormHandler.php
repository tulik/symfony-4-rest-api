<?php

declare(strict_types=1);

namespace App\Form\Handler;

use App\Exception\Enum\ApiErrorEnumType;
use App\Exception\FormInvalidException;
use App\Service\Form\FormErrorsSerializer;
use App\Service\Generic\ResponseCreator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{
    /**
     * @var ResponseCreator
     */
    protected $responseCreator;

    /**
     * @var FormErrorsSerializer
     */
    protected $formErrorsSerializer;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * AbstractFormHandler constructor.
     *
     * @param ResponseCreator $responseCreator
     * @param FormErrorsSerializer $formErrorsSerializer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ResponseCreator $responseCreator,
        FormErrorsSerializer $formErrorsSerializer,
        EntityManagerInterface $entityManager
    ) {
        $this->responseCreator = $responseCreator;
        $this->formErrorsSerializer = $formErrorsSerializer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     *
     * @throws FormInvalidException
     *
     * @return mixed
     */
    public function process(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = Request::METHOD_PATCH !== $request->getMethod();

        $form->submit($data, $clearMissing);

        if ($form->isValid()) {
            return $this->onSuccess($form->getData());
        }

        throw new FormInvalidException(
            ApiErrorEnumType::FORM_INVALID,
            0,
            $this->formErrorsSerializer->serialize($form)
        );
    }

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    abstract protected function onSuccess($object);
}
