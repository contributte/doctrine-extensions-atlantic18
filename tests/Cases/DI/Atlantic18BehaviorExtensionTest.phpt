<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

// Nothing
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// Loggable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					loggable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// Sluggable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					sluggable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// SoftDeleteable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					softDeleteable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// Treeable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					treeable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// Blameable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					blameable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// Timestampable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					timestampable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// Sortable
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					sortable: true
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});
