<?php declare(strict_types = 1);

namespace Tests\Cases\E2E;

use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Gedmo\Blameable\BlameableListener;
use Gedmo\IpTraceable\IpTraceableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Sortable\SortableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Translatable\TranslatableListener;
use Gedmo\Tree\TreeListener;
use Nette\DI\Compiler;
use Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

// Test: All listeners are registered
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					loggable: true
					sluggable: true
					softDeleteable: true
					treeable: true
					blameable: true
					timestampable: true
					sortable: true
			NEON
			));
		})
		->build();

	$container->initialize();

	// Verify all listeners are registered
	Assert::type(LoggableListener::class, $container->getByType(LoggableListener::class));
	Assert::type(SluggableListener::class, $container->getByType(SluggableListener::class));
	Assert::type(SoftDeleteableListener::class, $container->getByType(SoftDeleteableListener::class));
	Assert::type(TreeListener::class, $container->getByType(TreeListener::class));
	Assert::type(BlameableListener::class, $container->getByType(BlameableListener::class));
	Assert::type(TimestampableListener::class, $container->getByType(TimestampableListener::class));
	Assert::type(SortableListener::class, $container->getByType(SortableListener::class));
});

// Test: Translatable listener with configuration
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					translatable:
						translatable: cs_CZ
						default: en_US
						translationFallback: true
						persistDefaultTranslation: true
						skipOnLoad: false
			NEON
			));
		})
		->build();

	$container->initialize();

	/** @var TranslatableListener $listener */
	$listener = $container->getByType(TranslatableListener::class);

	Assert::type(TranslatableListener::class, $listener);
	Assert::same('en_US', $listener->getDefaultLocale());
	Assert::same('cs_CZ', $listener->getListenerLocale());
});

// Test: IpTraceable listener with string IP
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				nettrine.extensions.atlantic18:
					ipTraceable:
						ipValue: '127.0.0.1'
			NEON
			));
		})
		->build();

	$container->initialize();

	/** @var IpTraceableListener $listener */
	$listener = $container->getByType(IpTraceableListener::class);

	Assert::type(IpTraceableListener::class, $listener);
});

// Test: No listeners registered when all disabled
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.atlantic18', new Atlantic18BehaviorExtension());
		})
		->build();

	$container->initialize();

	// Verify no listeners are registered
	Assert::null($container->getByType(LoggableListener::class, false));
	Assert::null($container->getByType(SluggableListener::class, false));
	Assert::null($container->getByType(SoftDeleteableListener::class, false));
	Assert::null($container->getByType(TreeListener::class, false));
	Assert::null($container->getByType(BlameableListener::class, false));
	Assert::null($container->getByType(TimestampableListener::class, false));
	Assert::null($container->getByType(TranslatableListener::class, false));
	Assert::null($container->getByType(SortableListener::class, false));
	Assert::null($container->getByType(IpTraceableListener::class, false));
});
