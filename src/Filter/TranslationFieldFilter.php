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

use Doctrine\ORM\Query\Expr\Join;
use Sonata\AdminBundle\Filter\Model\FilterData;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;
use Sonata\TranslationBundle\Enum\TranslationFilterMode;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TranslationFieldFilter extends Filter
{
    public function __construct(private string $filterMode = TranslationFilterMode::GEDMO)
    {
    }

    public function filter(ProxyQueryInterface $query, string $alias, string $field, FilterData $data): void
    {
        if (!$data->hasValue() || null === $data->getValue()) {
            return;
        }

        $value = trim((string) $data->getValue());

        if ('' === $value) {
            return;
        }
        $joinAlias = 'tff';
        $filterMode = (string) $this->getOption('filter_mode');

        // verify if the join is not already done
        $aliasAlreadyExists = false;

        /** @var Join[] $joins */
        foreach ($query->getQueryBuilder()->getDQLParts()['join'] as $joins) {
            foreach ($joins as $join) {
                if ($join->getAlias() === $joinAlias) {
                    $aliasAlreadyExists = true;

                    break 2;
                }
            }
        }

        if (!$aliasAlreadyExists) {
            $query->getQueryBuilder()->leftJoin($alias.'.translations', $joinAlias);
        }

        if (TranslationFilterMode::GEDMO === $filterMode) {
            // search on translation OR on normal field when using Gedmo
            $this->applyGedmoFilters($query, $joinAlias, $alias, $field, $value);

            $this->setActive(true);
        } elseif (TranslationFilterMode::KNPLABS === $filterMode) {
            // search on translation OR on normal field when using Knp
            $this->applyKnplabsFilters($query, $joinAlias, $field, $value);

            $this->setActive(true);
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

    /**
     * @return array<string, mixed>
     */
    public function getFormOptions(): array
    {
        return [
            'field_type' => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'operator_type' => $this->getOption('operator_type'),
            'operator_options' => $this->getOption('operator_options'),
            'label' => $this->getLabel(),
        ];
    }

    protected function association(ProxyQueryInterface $query, FilterData $data): array
    {
        $defaultAlias = $query->entityJoin($this->getParentAssociationMappings());
        /** @var literal-string $alias */
        $alias = $this->getOption('alias', $defaultAlias);
        /** @var literal-string $fieldName */
        $fieldName = $this->getFieldName();

        return [$alias, $fieldName];
    }

    /**
     * @phpstan-param ProxyQueryInterface<object> $query
     */
    private function applyGedmoFilters(ProxyQueryInterface $query, string $joinAlias, string $alias, string $field, string $value): void
    {
        $this->applyWhere($query, $query->getQueryBuilder()->expr()->orX(
            $query->getQueryBuilder()->expr()->andX(
                $query->getQueryBuilder()->expr()->eq($joinAlias.'.field', $query->getQueryBuilder()->expr()->literal($field)),
                $query->getQueryBuilder()->expr()->like(
                    $joinAlias.'.content',
                    $query->getQueryBuilder()->expr()->literal('%'.$value.'%')
                )
            ),
            $query->getQueryBuilder()->expr()->like(
                sprintf('%s.%s', $alias, $field),
                $query->getQueryBuilder()->expr()->literal('%'.$value.'%')
            )
        ));
    }

    /**
     * @phpstan-param ProxyQueryInterface<object> $query
     */
    private function applyKnplabsFilters(ProxyQueryInterface $query, string $joinAlias, string $field, string $value): void
    {
        $this->applyWhere(
            $query,
            $query->getQueryBuilder()->expr()->andX(
                $query->getQueryBuilder()->expr()->like(
                    $joinAlias.".$field",
                    $query->getQueryBuilder()->expr()->literal('%'.$value.'%')
                )
            )
        );
    }
}
