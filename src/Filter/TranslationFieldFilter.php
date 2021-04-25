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
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
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

    public function filter(ProxyQueryInterface $query, $alias, $field, $data): void
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

        \assert($query instanceof ProxyQuery);

        // verify if the join is not already done
        $aliasAlreadyExists = false;
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
            $this->applyGedmoFilters($query, $joinAlias, $alias, $field, $data);

            $this->active = true;
        } elseif (TranslationFilterMode::KNPLABS === $filterMode) {
            // search on translation OR on normal field when using Knp
            $this->applyKnplabsFilters($query, $joinAlias, $field, $data);

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

    protected function association(ProxyQueryInterface $query, $data): array
    {
        $alias = $query->entityJoin($this->getParentAssociationMappings());

        return [(string) $this->getOption('alias', $alias), $this->getFieldName()];
    }

    /**
     * @param mixed[] $data
     */
    private function applyGedmoFilters(ProxyQuery $query, string $joinAlias, string $alias, string $field, $data): void
    {
        $this->applyWhere($query, $query->getQueryBuilder()->expr()->orX(
            $query->getQueryBuilder()->expr()->andX(
                $query->getQueryBuilder()->expr()->eq($joinAlias.'.field', $query->getQueryBuilder()->expr()->literal($field)),
                $query->getQueryBuilder()->expr()->like(
                    $joinAlias.'.content',
                    $query->getQueryBuilder()->expr()->literal('%'.$data['value'].'%')
                )
            ),
            $query->getQueryBuilder()->expr()->like(
                sprintf('%s.%s', $alias, $field),
                $query->getQueryBuilder()->expr()->literal('%'.$data['value'].'%')
            )
        ));
    }

    /**
     * @param mixed[] $data
     */
    private function applyKnplabsFilters(ProxyQuery $query, string $joinAlias, string $field, $data): void
    {
        $this->applyWhere(
            $query,
            $query->getQueryBuilder()->expr()->andX(
                $query->getQueryBuilder()->expr()->like(
                    $joinAlias.".$field",
                    $query->getQueryBuilder()->expr()->literal('%'.$data['value'].'%')
                )
            )
        );
    }
}
