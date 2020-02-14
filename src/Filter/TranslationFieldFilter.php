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
use Sonata\TranslationBundle\Enum\TranslationFilterMode;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TranslationFieldFilter extends Filter
{
    /**
     * @var string
     */
    private $filterMode;

    public function __construct(string $filterMode = TranslationFilterMode::GEDMO)
    {
        $this->filterMode = $filterMode;
    }

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
        $filterMode = $this->getOption('filter_mode');

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

        if (TranslationFilterMode::GEDMO === $filterMode) {
            // search on translation OR on normal field when using Gedmo
            $this->applyGedmoFilters($queryBuilder, $joinAlias, $alias, $field, $data);

            $this->active = true;
        } elseif (TranslationFilterMode::KNPLABS === $filterMode) {
            // search on translation OR on normal field when using Knp
            $this->applyKnplabsFilters($queryBuilder, $joinAlias, $field, $data);

            $this->active = true;
        } else {
            throw new \LogicException(sprintf('Invalid filter mode given: "%s"', $filterMode));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        return [
            'field_type' => TextType::class,
            'operator_type' => HiddenType::class,
            'filter_mode' => $this->filterMode,
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

    /**
     * @param mixed[] $data
     */
    private function applyGedmoFilters(ProxyQueryInterface $queryBuilder, string $joinAlias, string $alias, string $field, $data): void
    {
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
    }

    /**
     * @param mixed[] $data
     */
    private function applyKnplabsFilters(ProxyQueryInterface $queryBuilder, string $joinAlias, string $field, $data): void
    {
        $this->applyWhere(
            $queryBuilder,
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->like(
                    $joinAlias.".$field",
                    $queryBuilder->expr()->literal('%'.$data['value'].'%')
                )
            )
        );
    }
}
