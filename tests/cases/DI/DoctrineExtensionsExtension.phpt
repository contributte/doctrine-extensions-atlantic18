<?php

/**
 * Test: DI\DoctrineExtensionsExtension
 */

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nettrine\Extensions\DI\DoctrineExtensionsExtension;
use Tester\Assert;
use Tester\FileMock;

require_once __DIR__ . '/../../bootstrap.php';

test(function () {
	$loader = new ContainerLoader(TEMP_DIR, TRUE);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->addExtension('extensions', new DoctrineExtensionsExtension());
		$compiler->loadConfig(FileMock::create('
			extensions:
				annotations:
					loggable: false
					sluggable: false
					softDeleteable: false
					treeable: false
					blameable: false
					timestampable: false
					translatable: false
					uploadable: false
					sortable: false
					ipTraceable: false
		', 'neon'));
	}, '1a');

	/** @var Container $container */
	$container = new $class;
	Assert::type(Container::class, $container);
});
