<?php declare(strict_types = 1);

namespace Tests\Nettrine\Extensions\Atlantic18\Unit\DI;

use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nettrine\Extensions\Atlantic18\DI\DoctrineExtensionsExtension;
use PHPUnit\Framework\TestCase;

final class DoctrineExtensionsExtensionTest extends TestCase
{

	/**
	 * @doesNotPerformAssertions
	 */
	public function testDefault(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('extensions', new DoctrineExtensionsExtension());
		}, '1a');

		$container = new $class();
		assert($container instanceof Container);
	}

}
