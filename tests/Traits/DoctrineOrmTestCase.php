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

namespace Sonata\TranslationBundle\Tests\Traits;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

/**
 * Base test case contains common mock objects
 * and functionality among all tests using
 * ORM entity manager.
 *
 * @author Dariusz Markowicz <dmarkowicz77@gmail.com>
 *
 * Inspired by BaseTestCaseORM
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 */
abstract class DoctrineOrmTestCase extends TestCase
{
    /**
     * EntityManager mock object together with
     * annotation mapping driver and pdo_sqlite
     * database in memory.
     *
     * @psalm-suppress DeprecatedMethod
     */
    final protected function getMockSqliteEntityManager(?EventManager $evm = null, ?Configuration $config = null): EntityManager
    {
        $conn = [
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ];

        $em = EntityManager::create($conn, $config ?? $this->getMockAnnotatedConfig(), $evm ?? new EventManager());

        $schema = array_map(
            static fn (string $class): ClassMetadata => $em->getClassMetadata($class),
            $this->getUsedEntityFixtures()
        );

        $schemaTool = new SchemaTool($em);
        $schemaTool->dropSchema([]);
        $schemaTool->createSchema($schema);

        return $em;
    }

    /**
     * Creates default mapping driver.
     */
    final protected function getMetadataDriverImplementation(): AnnotationDriver
    {
        return new AnnotationDriver(new AnnotationReader());
    }

    /**
     * Get a list of used fixture classes.
     *
     * @phpstan-return list<class-string>
     */
    abstract protected function getUsedEntityFixtures(): array;

    /**
     * Get annotation mapping configuration.
     */
    final protected function getMockAnnotatedConfig(): Configuration
    {
        $config = new Configuration();
        $config->setProxyDir(sys_get_temp_dir().'/sonata-translation-bundle');
        $config->setProxyNamespace('Proxy');
        $config->setMetadataDriverImpl($this->getMetadataDriverImplementation());

        return $config;
    }
}
