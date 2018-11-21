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
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFilter extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Allows filter Users movie title they watched.
        $builder
            ->add('email', Filters\TextFilterType::class)
            ->add('movies', Filters\TextFilterType::class, [
            'apply_filter' => function (QueryInterface $filterQuery, $field, $values) {
                if (empty($values['value'])) {
                    return null;
                }

                $query = $filterQuery->getQueryBuilder();
                $query->innerJoin($field, 't');

                $paramName = sprintf('p_%s', str_replace('.', '_', $field));
                $expression = $filterQuery->getExpr()->eq('t.title', ':'.$paramName);
                $parameters = [$paramName => $values['value']];

                return $filterQuery->createCondition($expression, $parameters);
            },
        ]);
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
