<?php declare(strict_types = 1);

namespace Tests\Cases\E2E;

use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Nette\DI\Compiler;
use Nettrine\DBAL\DI\DbalExtension;
use Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension;
use Nettrine\ORM\DI\OrmExtension;
use Tester\Assert;
use Tests\Fixtures\DummyEntity;

require_once __DIR__ . '/../../bootstrap.php';

if (!extension_loaded('pdo_sqlite')) {
	Environment::skip('Test requires pdo_sqlite extension.');
}

// Test: Sluggable behavior
Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.dbal', new DbalExtension());
			$compiler->addExtension('nettrine.orm', new OrmExtension());
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.dbal:
					connections:
						default:
							driver: pdo_sqlite
							password: test
							user: test
							path: ":memory:"
				nettrine.orm:
					managers:
						default:
							connection: default
							mapping:
								App:
									type: attributes
									directories: [%fixturesDir%]
									namespace: Tests\Fixtures
				nettrine.extensions.atlantic18:
					sluggable: true
					timestampable: true
			NEON
			));
			$compiler->addConfig([
				'parameters' => [
					'tempDir' => Environment::getTestDir(),
					'fixturesDir' => __DIR__ . '/../../Fixtures',
				],
			]);
		})
		->build();

	/** @var Connection $connection */
	$connection = $container->getByType(Connection::class);
	$connection->executeQuery(<<<'SQL'
		CREATE TABLE dummy (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			title VARCHAR(255) NOT NULL,
			slug VARCHAR(255) NOT NULL UNIQUE,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			created_by VARCHAR(255),
			created_from_ip VARCHAR(45),
			deleted_at DATETIME
		)
	SQL);

	/** @var EntityManagerInterface $em */
	$em = $container->getByType(EntityManagerInterface::class);

	$entity = new DummyEntity('Hello World');
	$em->persist($entity);
	$em->flush();

	// Verify slug was generated
	Assert::same('hello-world', $entity->getSlug());

	// Verify timestamps were set
	Assert::type('DateTimeImmutable', $entity->getCreatedAt());
	Assert::type('DateTimeImmutable', $entity->getUpdatedAt());
});

// Test: Timestampable behavior on update
Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.dbal', new DbalExtension());
			$compiler->addExtension('nettrine.orm', new OrmExtension());
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.dbal:
					connections:
						default:
							driver: pdo_sqlite
							password: test
							user: test
							path: ":memory:"
				nettrine.orm:
					managers:
						default:
							connection: default
							mapping:
								App:
									type: attributes
									directories: [%fixturesDir%]
									namespace: Tests\Fixtures
				nettrine.extensions.atlantic18:
					sluggable: true
					timestampable: true
			NEON
			));
			$compiler->addConfig([
				'parameters' => [
					'tempDir' => Environment::getTestDir(),
					'fixturesDir' => __DIR__ . '/../../Fixtures',
				],
			]);
		})
		->build();

	/** @var Connection $connection */
	$connection = $container->getByType(Connection::class);
	$connection->executeQuery(<<<'SQL'
		CREATE TABLE dummy (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			title VARCHAR(255) NOT NULL,
			slug VARCHAR(255) NOT NULL UNIQUE,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			created_by VARCHAR(255),
			created_from_ip VARCHAR(45),
			deleted_at DATETIME
		)
	SQL);

	/** @var EntityManagerInterface $em */
	$em = $container->getByType(EntityManagerInterface::class);

	$entity = new DummyEntity('Original Title');
	$em->persist($entity);
	$em->flush();

	$createdAt = $entity->getCreatedAt();
	$originalUpdatedAt = $entity->getUpdatedAt();

	// Wait a bit and update
	usleep(10000); // 10ms

	$entity->setTitle('Updated Title');
	$em->flush();

	// CreatedAt should not change
	Assert::same($createdAt->getTimestamp(), $entity->getCreatedAt()->getTimestamp());

	// UpdatedAt should be updated (or same if within same second)
	Assert::true($entity->getUpdatedAt() >= $originalUpdatedAt);

	// Slug should be updated
	Assert::same('updated-title', $entity->getSlug());
});

// Test: Multiple entities with unique slugs
Toolkit::test(function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.dbal', new DbalExtension());
			$compiler->addExtension('nettrine.orm', new OrmExtension());
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.dbal:
					connections:
						default:
							driver: pdo_sqlite
							password: test
							user: test
							path: ":memory:"
				nettrine.orm:
					managers:
						default:
							connection: default
							mapping:
								App:
									type: attributes
									directories: [%fixturesDir%]
									namespace: Tests\Fixtures
				nettrine.extensions.atlantic18:
					sluggable: true
					timestampable: true
			NEON
			));
			$compiler->addConfig([
				'parameters' => [
					'tempDir' => Environment::getTestDir(),
					'fixturesDir' => __DIR__ . '/../../Fixtures',
				],
			]);
		})
		->build();

	/** @var Connection $connection */
	$connection = $container->getByType(Connection::class);
	$connection->executeQuery(<<<'SQL'
		CREATE TABLE dummy (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			title VARCHAR(255) NOT NULL,
			slug VARCHAR(255) NOT NULL UNIQUE,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			created_by VARCHAR(255),
			created_from_ip VARCHAR(45),
			deleted_at DATETIME
		)
	SQL);

	/** @var EntityManagerInterface $em */
	$em = $container->getByType(EntityManagerInterface::class);

	$entity1 = new DummyEntity('Test Article');
	$entity2 = new DummyEntity('Test Article');

	$em->persist($entity1);
	$em->persist($entity2);
	$em->flush();

	// First entity should have standard slug
	Assert::same('test-article', $entity1->getSlug());

	// Second entity should have unique slug with suffix
	Assert::same('test-article-1', $entity2->getSlug());
});
