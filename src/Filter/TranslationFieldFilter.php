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

    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $value): void
    {
        if (!$value || !\is_array($value) || !\array_key_exists('value', $value) || null === $value['value']) {
            return;
        }

        $value['value'] = trim($value['value']);

        if (0 === \strlen($value['value'])) {
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
            $this->applyGedmoFilters($queryBuilder, $joinAlias, $alias, $field, $value);

            $this->active = true;
        } elseif (TranslationFilterMode::KNPLABS === $filterMode) {
            // search on translation OR on normal field when using Knp
            $this->applyKnplabsFilters($queryBuilder, $joinAlias, $field, $value);

            $this->active = true;
        } else {
            throw new \LogicException(sprintf('Invalid filter mode given: "%s"', $filterMode));
        }
    }

    public function getDefaultOptions(): array
    {
        return [
            'field_type' => TextType::class,
            'operator_type' => HiddenType::class,
            'filter_mode' => $this->filterMode,
            'operator_options' => [],
        ];
    }

    public function getRenderSettings(): array
    {
        return [DefaultType::class, [
            'field_type' => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'operator_type' => $this->getOption('operator_type'),
            'operator_options' => $this->getOption('operator_options'),
            'label' => $this->getLabel(),
        ]];
    }

    protected function association(ProxyQueryInterface $queryBuilder, $value): array
    {
        $alias = $queryBuilder->entityJoin($this->getParentAssociationMappings());

        return [$this->getOption('alias', $alias), $this->getFieldName()];
    }

    /**
     * @param mixed[] $value
     */
    private function applyGedmoFilters(ProxyQueryInterface $queryBuilder, string $joinAlias, string $alias, string $field, $value): void
    {
        $this->applyWhere($queryBuilder, $queryBuilder->expr()->orX(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq($joinAlias.'.field', $queryBuilder->expr()->literal($field)),
                $queryBuilder->expr()->like(
                    $joinAlias.'.content',
                    $queryBuilder->expr()->literal('%'.$value['value'].'%')
                )
            ),
            $queryBuilder->expr()->like(
                sprintf('%s.%s', $alias, $field),
                $queryBuilder->expr()->literal('%'.$value['value'].'%')
            )
        ));
    }

    /**
     * @param mixed[] $value
     */
    private function applyKnplabsFilters(ProxyQueryInterface $queryBuilder, string $joinAlias, string $field, $value): void
    {
        $this->applyWhere(
            $queryBuilder,
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->like(
                    $joinAlias.".$field",
                    $queryBuilder->expr()->literal('%'.$value['value'].'%')
                )
            )
        );
    }
}
