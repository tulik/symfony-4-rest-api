<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Service\Form;

use Symfony\Component\Form\FormInterface;

class FormErrorsSerializer
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function serialize(FormInterface $form): array
    {
        $this->errors = $this->serializeErrors($form);

        return $this->errors;
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    protected function serializeErrors(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->serializeErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }
}
