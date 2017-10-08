<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Filter;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;

final class TranslationFieldFilter extends Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }

        $data['value'] = trim($data['value']);

        if (strlen($data['value']) == 0) {
            return;
        }

        $joinAlias = 'tff';

        // verify if the join is not already done
        $aliasAlreadyExists = false;
        foreach ($queryBuilder->getDQLParts()['join'] as $joins) {
            foreach ($joins as $join) {
                if ($join->getAlias() === $joinAlias) {
                    $aliasAlreadyExists = true;

                    break 2;
                }
            }
        }

        if (!$aliasAlreadyExists) {
            $queryBuilder->leftJoin($alias.'.translations', $joinAlias);
        }

        // search on translation OR on normal field
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq($joinAlias.'.field', $queryBuilder->expr()->literal($field)),
                $queryBuilder->expr()->like(
                    $joinAlias.'.content',
                    $queryBuilder->expr()->literal('%'.$data['value'].'%')
                )
            ),
            $queryBuilder->expr()->like(
                sprintf('%s.%s', $alias, $field),
                $queryBuilder->expr()->literal('%'.$data['value'].'%')
            )
        ));

        $this->active = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return [
            'field_type' => method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
                ? 'Symfony\Component\Form\Extension\Core\Type\TextType'
                : 'text', // NEXT_MAJOR: Remove ternary (when requirement of Symfony is >= 2.8)
            'operator_type' => method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
                ? 'Symfony\Component\Form\Extension\Core\Type\HiddenType'
                : 'hidden', // NEXT_MAJOR: Remove ternary (when requirement of Symfony is >= 2.8)
            'operator_options' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderSettings()
    {
        // NEXT_MAJOR: Remove this line when drop Symfony <2.8 support
        $type = method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')
            ? 'Sonata\AdminBundle\Form\Type\Filter\DefaultType'
            : 'sonata_type_filter_default';

        return [$type, [
            'field_type' => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'operator_type' => $this->getOption('operator_type'),
            'operator_options' => $this->getOption('operator_options'),
            'label' => $this->getLabel(),
        ]];
    }

    /**
     * {@inheritdoc}
     */
    protected function association(ProxyQueryInterface $queryBuilder, $data)
    {
        $alias = $queryBuilder->entityJoin($this->getParentAssociationMappings());

        return [$this->getOption('alias', $alias), $this->getFieldName()];
    }
}
