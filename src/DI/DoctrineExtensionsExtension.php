<?php declare(strict_types = 1);

namespace Nettrine\Extensions\DI;

use Doctrine\Common\Annotations\Reader;
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
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use stdClass;

/**
 * @property-read stdClass $config
 */
class DoctrineExtensionsExtension extends CompilerExtension
{

	public const TAG_NETTRINE_SUBSCRIBER = 'nettrine.subscriber';

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
			'ipTraceable' => Expect::bool(false),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->config;

		// Loggable ==================================================

		if ($config->loggable) {
			$builder->addDefinition($this->prefix('loggable'))
				->setFactory(LoggableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Sluggable =================================================

		if ($config->sluggable) {
			$builder->addDefinition($this->prefix('sluggable'))
				->setFactory(SluggableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// SoftDeleteable ============================================

		if ($config->softDeleteable) {
			$builder->addDefinition($this->prefix('softDeleteable'))
				->setFactory(SoftDeleteableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Treeable ==================================================

		if ($config->treeable) {
			$builder->addDefinition($this->prefix('treeable'))
				->setFactory(TreeListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Blameable =================================================

		if ($config->blameable) {
			$builder->addDefinition($this->prefix('blameable'))
				->setFactory(BlameableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Timestampable =============================================

		if ($config->timestampable) {
			$builder->addDefinition($this->prefix('timestampable'))
				->setFactory(TimestampableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Translatable ==============================================

		if ($config->translatable !== false) {
			$translatableConfig = $config->translatable;
			$builder->addDefinition($this->prefix('translatable'))
				->setFactory(TranslatableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addSetup('setDefaultLocale', [$translatableConfig->default])
				->addSetup('setTranslatableLocale', [$translatableConfig->translatable])
				->addSetup('setPersistDefaultLocaleTranslation', [$translatableConfig->translationFallback])
				->addSetup('setTranslationFallback', [$translatableConfig->persistDefaultTranslation])
				->addSetup('setSkipOnLoad', [$translatableConfig->skipOnLoad])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Sortable ==================================================

		if ($config->sortable) {
			$builder->addDefinition($this->prefix('sortable'))
				->setFactory(SortableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// IpTraceable ===============================================

		if ($config->ipTraceable) {
			$builder->addDefinition($this->prefix('ipTraceable'))
				->setFactory(IpTraceableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addSetup('setIpValue', !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null)
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}
	}

}
