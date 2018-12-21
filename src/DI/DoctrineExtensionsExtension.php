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

class DoctrineExtensionsExtension extends CompilerExtension
{

	public const TAG_NETTRINE_SUBSCRIBER = 'nettrine.subscriber';

	/** @var mixed[] */
	private $defaults = [
		'loggable' => false,
		'sluggable' => false,
		'softDeleteable' => false,
		'treeable' => false,
		'blameable' => false,
		'timestampable' => false,
		'translatable' => false,
		'uploadable' => false,
		'sortable' => false,
		'ipTraceable' => false,
	];

	/** @var mixed[] */
	private $defaultsListeners = [
		'translatable' => [
			'translatable' => 'cs_CZ',
			'default' => 'cs_CZ',
			'translationFallback' => false,
			'persistDefaultTranslation' => false,
			'skipOnLoad' => false,
		],
	];

	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Loggable ==================================================

		if ($config['loggable'] !== false) {
			$builder->addDefinition($this->prefix('loggable'))
				->setFactory(LoggableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Sluggable =================================================

		if ($config['sluggable'] !== false) {
			$builder->addDefinition($this->prefix('sluggable'))
				->setFactory(SluggableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// SoftDeleteable ============================================

		if ($config['softDeleteable'] !== false) {
			$builder->addDefinition($this->prefix('softDeleteable'))
				->setFactory(SoftDeleteableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Treeable ==================================================

		if ($config['treeable'] !== false) {
			$builder->addDefinition($this->prefix('treeable'))
				->setFactory(TreeListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Blameable =================================================

		if ($config['blameable'] !== false) {
			$builder->addDefinition($this->prefix('blameable'))
				->setFactory(BlameableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Timestampable =============================================

		if ($config['timestampable'] !== false) {
			$builder->addDefinition($this->prefix('timestampable'))
				->setFactory(TimestampableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Translatable ==============================================

		if ($config['translatable'] !== false) {
			$translatableConfig = $this->validateConfig($this->defaultsListeners['translatable'], $config['translatable']);
			$builder->addDefinition($this->prefix('translatable'))
				->setFactory(TranslatableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addSetup('setDefaultLocale', [$translatableConfig['default']])
				->addSetup('setTranslatableLocale', [$translatableConfig['translatable']])
				->addSetup('setPersistDefaultLocaleTranslation', [$translatableConfig['translationFallback']])
				->addSetup('setTranslationFallback', [$translatableConfig['persistDefaultTranslation']])
				->addSetup('setSkipOnLoad', [$translatableConfig['skipOnLoad']])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// Sortable ==================================================

		if ($config['sortable'] !== false) {
			$builder->addDefinition($this->prefix('sortable'))
				->setFactory(SortableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}

		// IpTraceable ===============================================

		if ($config['ipTraceable'] !== false) {
			$builder->addDefinition($this->prefix('ipTraceable'))
				->setFactory(IpTraceableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::TAG_NETTRINE_SUBSCRIBER);
		}
	}

}
