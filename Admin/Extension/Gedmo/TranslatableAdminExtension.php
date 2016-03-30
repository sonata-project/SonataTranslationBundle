<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Admin\Extension\Gedmo;

use Gedmo\Translatable\TranslatableListener;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\TranslationBundle\Admin\Extension\AbstractTranslatableAdminExtension;
use Sonata\TranslationBundle\Checker\TranslatableChecker;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 */
class TranslatableAdminExtension extends AbstractTranslatableAdminExtension
{
    /**
     * @var TranslatableListener
     */
    protected $translatableListener;

    public function __construct(TranslatableChecker $translatableChecker, TranslatableListener $translatableListener = null)
    {
        parent::__construct($translatableChecker);
        $this->translatableListener = $translatableListener;
    }

    /**
     * @param AdminInterface $admin Deprecated, set TranslatableListener in the constructor instead.
     *
     * @return TranslatableListener
     */
    protected function getTranslatableListener(AdminInterface $admin)
    {
        if (null === $this->translatableListener) {
            $this->translatableListener = $this->getContainer($admin)->get(
                'stof_doctrine_extensions.listener.translatable'
            );
        }

        return $this->translatableListener;
    }

    /**
     * {@inheritdoc}
     */
    public function alterObject(AdminInterface $admin, $object)
    {
        if ($this->getTranslatableChecker()->isTranslatable($object)) {
            $translatableListener = $this->getTranslatableListener($admin);
            $translatableListener->setTranslatableLocale($this->getTranslatableLocale($admin));
            $translatableListener->setTranslationFallback('');

            $this->getContainer($admin)->get('doctrine')->getManager()->refresh($object);
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {
        $this->getTranslatableListener()->setTranslatableLocale($this->getTranslatableLocale($admin));
        $this->getTranslatableListener()->setTranslationFallback('');
    }

    /**
     * Search on normal field and on translation field
     * To use with a doctrine_orm_callback filter type.
     *
     * @param ProxyQuery $queryBuilder
     * @param string     $alias
     * @param string     $field
     * @param string     $value
     *
     * @return bool
     */
    public static function translationFieldFilter(ProxyQuery $queryBuilder, $alias, $field, $value)
    {
        if (!$value['value']) {
            return;
        }

        // verify if the join is not already done
        $aliasAlreadyExists = false;
        $joinDqlParts = $queryBuilder->getDQLParts()['join'];
        foreach ($joinDqlParts as $joins) {
            foreach ($joins as $join) {
                if ($join->getAlias() === 't') {
                    $aliasAlreadyExists = true;
                    break 2;
                }
            }
        }

        if ($aliasAlreadyExists === false) {
            $queryBuilder->leftJoin($alias.'.translations', 't');
        }

        // search on translation OR on normal field
        $queryBuilder->andWhere($queryBuilder->expr()->orX(
            $queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('t.field', $queryBuilder->expr()->literal($field)),
                $queryBuilder->expr()->like('t.content', $queryBuilder->expr()->literal('%'.$value['value'].'%'))
            ),
            $queryBuilder->expr()->like(
                sprintf('%s.%s', $alias, $field),
                $queryBuilder->expr()->literal('%'.$value['value'].'%')
            )
        ));

        return true;
    }
}
