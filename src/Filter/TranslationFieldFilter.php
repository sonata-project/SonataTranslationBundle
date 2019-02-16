<?php

declare(strict_types=1);

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
use Sonata\AdminBundle\Form\Type\Filter\DefaultType;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TranslationFieldFilter extends Filter
{
    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !\is_array($data) || !\array_key_exists('value', $data) || null === $data['value']) {
            return;
        }

        $data['value'] = trim($data['value']);

        if (0 === \strlen($data['value'])) {
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
        $this->applyWhere($queryBuilder, $queryBuilder->expr()->orX(
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
            'field_type' => TextType::class,
            'operator_type' => HiddenType::class,
            'operator_options' => [],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRenderSettings()
    {
        return [DefaultType::class, [
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
