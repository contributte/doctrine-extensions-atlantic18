<?php declare(strict_types = 1);

namespace Tests\Nettrine\Extensions\Atlantic18\Unit\DI;

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nettrine\Extensions\Atlantic18\DI\Atlantic18BehaviorExtension;
use PHPUnit\Framework\TestCase;

final class Atlantic18BehaviorExtensionTest extends TestCase
{

	/**
	 * @doesNotPerformAssertions
	 */
	public function testDefault(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('extensions', new Atlantic18BehaviorExtension());
		}, '1a');

		$container = new $class();
		assert($container instanceof Container);
	}

}
