<?php declare(strict_types = 1);

namespace Tests\Cases\E2E;

use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Gedmo\Blameable\BlameableListener;
use Gedmo\IpTraceable\IpTraceableListener;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Timestampable\TimestampableListener;
use Nette\DI\Compiler;
use Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension;
use ReflectionClass;
use Tester\Assert;
use Tests\Fixtures\DummyEntity;

require_once __DIR__ . '/../../bootstrap.php';

// Test: DummyEntity class exists and has correct attributes
Toolkit::test(static function (): void {
	$reflection = new ReflectionClass(DummyEntity::class);

	// Verify entity has expected ORM attributes
	$entityAttributes = $reflection->getAttributes();
	$attributeNames = array_map(static fn ($attr) => $attr->getName(), $entityAttributes);

	Assert::contains('Doctrine\ORM\Mapping\Entity', $attributeNames);
	Assert::contains('Doctrine\ORM\Mapping\Table', $attributeNames);
	Assert::contains('Gedmo\Mapping\Annotation\SoftDeleteable', $attributeNames);
});

// Test: DummyEntity properties have Gedmo attributes
Toolkit::test(static function (): void {
	$reflection = new ReflectionClass(DummyEntity::class);

	// Check slug property has Sluggable attribute
	$slugProperty = $reflection->getProperty('slug');
	$slugAttributes = array_map(static fn ($attr) => $attr->getName(), $slugProperty->getAttributes());
	Assert::contains('Gedmo\Mapping\Annotation\Slug', $slugAttributes);

	// Check createdAt property has Timestampable attribute
	$createdAtProperty = $reflection->getProperty('createdAt');
	$createdAtAttributes = array_map(static fn ($attr) => $attr->getName(), $createdAtProperty->getAttributes());
	Assert::contains('Gedmo\Mapping\Annotation\Timestampable', $createdAtAttributes);

	// Check updatedAt property has Timestampable attribute
	$updatedAtProperty = $reflection->getProperty('updatedAt');
	$updatedAtAttributes = array_map(static fn ($attr) => $attr->getName(), $updatedAtProperty->getAttributes());
	Assert::contains('Gedmo\Mapping\Annotation\Timestampable', $updatedAtAttributes);

	// Check createdBy property has Blameable attribute
	$createdByProperty = $reflection->getProperty('createdBy');
	$createdByAttributes = array_map(static fn ($attr) => $attr->getName(), $createdByProperty->getAttributes());
	Assert::contains('Gedmo\Mapping\Annotation\Blameable', $createdByAttributes);

	// Check createdFromIp property has IpTraceable attribute
	$createdFromIpProperty = $reflection->getProperty('createdFromIp');
	$createdFromIpAttributes = array_map(static fn ($attr) => $attr->getName(), $createdFromIpProperty->getAttributes());
	Assert::contains('Gedmo\Mapping\Annotation\IpTraceable', $createdFromIpAttributes);
});

// Test: Listeners for DummyEntity behaviors are registered
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					sluggable: true
					softDeleteable: true
					blameable: true
					timestampable: true
					ipTraceable:
						ipValue: '127.0.0.1'
			NEON
			));
		})
		->build();

	$container->initialize();

	// Verify all listeners needed for DummyEntity are registered
	Assert::type(SluggableListener::class, $container->getByType(SluggableListener::class));
	Assert::type(SoftDeleteableListener::class, $container->getByType(SoftDeleteableListener::class));
	Assert::type(BlameableListener::class, $container->getByType(BlameableListener::class));
	Assert::type(TimestampableListener::class, $container->getByType(TimestampableListener::class));
	Assert::type(IpTraceableListener::class, $container->getByType(IpTraceableListener::class));
});

// Test: DummyEntity can be instantiated
Toolkit::test(static function (): void {
	$entity = new DummyEntity();

	Assert::type(DummyEntity::class, $entity);
	Assert::null($entity->getCreatedBy());
	Assert::null($entity->getCreatedFromIp());
	Assert::null($entity->getDeletedAt());
});
