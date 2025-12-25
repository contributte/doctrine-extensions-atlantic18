<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
$class = $loader->load(static function (Compiler $compiler): void {
	$compiler->addExtension('extensions', new Atlantic18BehaviorExtension());
}, '1a');

$container = new $class();
Assert::type(Container::class, $container);
