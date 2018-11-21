<?php

/*
 * (c) Lukasz D. Tulikowski <lukasz.tulikowski@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Form\Filter;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewFilter extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateTimeFieldOptions = [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
        ];

        $builder
            ->add('body', Filters\TextFilterType::class)
            ->add('rating', Filters\TextFilterType::class)
            ->add(
                'publicationDate',
                Filters\DateTimeRangeFilterType::class,
                [
                'left_datetime_options' => $dateTimeFieldOptions,
                'right_datetime_options' => $dateTimeFieldOptions,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation_groups' => ['filtering'],
        ]);
    }
}
