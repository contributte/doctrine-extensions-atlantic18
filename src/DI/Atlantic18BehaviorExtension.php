<?php declare(strict_types = 1);

namespace Nettrine\Extensions\Atlantic18\DI;

use Gedmo\Blameable\BlameableListener;
use Gedmo\IpTraceable\IpTraceableListener;
use Gedmo\Loggable\LoggableListener;
use Gedmo\Sluggable\SluggableListener;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Gedmo\Sortable\SortableListener;
use Gedmo\Timestampable\TimestampableListener;
use Gedmo\Translatable\TranslatableListener;
use Gedmo\Tree\TreeListener;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

/**
 * @property-read object{
 *   loggable: bool,
 *   sluggable: bool,
 *   softDeleteable: bool,
 *   treeable: bool,
 *   blameable: bool,
 *   timestampable: bool,
 *   translatable: object{
 *      translatable: string,
 *      default: string,
 *      translationFallback: bool,
 *      persistDefaultTranslation: bool,
 *      skipOnLoad: bool
 *   }|false,
 *   uploadable: bool,
 *   sortable: bool,
 *   ipTraceable: object{
 *      ipValue: string|array<string>
 *   }|false
 * } $config
 */
class Atlantic18BehaviorExtension extends CompilerExtension
{

	public const TAG_NAME = 'nettrine.extensions.atlantic18.listener';

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'loggable' => Expect::bool(false),
			'sluggable' => Expect::bool(false),
			'softDeleteable' => Expect::bool(false),
			'treeable' => Expect::bool(false),
			'blameable' => Expect::bool(false),
			'timestampable' => Expect::bool(false),
			'translatable' => Expect::anyOf(false, Expect::structure([
				'translatable' => Expect::string()->required(),
				'default' => Expect::string()->required(),
				'translationFallback' => Expect::bool(false),
				'persistDefaultTranslation' => Expect::bool(false),
				'skipOnLoad' => Expect::bool(false),
			]))->default(false),
			'uploadable' => Expect::bool(false),
			'sortable' => Expect::bool(false),
			'ipTraceable' => Expect::anyOf(false, Expect::structure([
				'ipValue' => Expect::anyOf(Expect::string(), Expect::array(), Expect::type(Statement::class))->required(),
			]))->default(false),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		if ($config->loggable) {
			$builder->addDefinition($this->prefix('loggable'))
				->setFactory(LoggableListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->sluggable) {
			$builder->addDefinition($this->prefix('sluggable'))
				->setFactory(SluggableListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->softDeleteable) {
			$builder->addDefinition($this->prefix('softDeleteable'))
				->setFactory(SoftDeleteableListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->treeable) {
			$builder->addDefinition($this->prefix('treeable'))
				->setFactory(TreeListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->blameable) {
			$builder->addDefinition($this->prefix('blameable'))
				->setFactory(BlameableListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->timestampable) {
			$builder->addDefinition($this->prefix('timestampable'))
				->setFactory(TimestampableListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->translatable !== false) {
			$translatableConfig = $config->translatable;
			$builder->addDefinition($this->prefix('translatable'))
				->setFactory(TranslatableListener::class)
				->addTag(self::TAG_NAME)
				->addSetup('setDefaultLocale', [$translatableConfig->default])
				->addSetup('setTranslatableLocale', [$translatableConfig->translatable])
				->addSetup('setPersistDefaultLocaleTranslation', [$translatableConfig->translationFallback])
				->addSetup('setTranslationFallback', [$translatableConfig->persistDefaultTranslation])
				->addSetup('setSkipOnLoad', [$translatableConfig->skipOnLoad]);
		}

		if ($config->sortable) {
			$builder->addDefinition($this->prefix('sortable'))
				->setFactory(SortableListener::class)
				->addTag(self::TAG_NAME);
		}

		if ($config->ipTraceable !== false) {
			$builder->addDefinition($this->prefix('ipTraceable'))
				->setFactory(IpTraceableListener::class)
				->addTag(self::TAG_NAME)
				->addSetup('setIpValue', [$config->ipTraceable->ipValue]);
		}
	}

}
