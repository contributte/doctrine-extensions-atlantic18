<?php

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
use Kdyby\Doctrine\DI\IEntityProvider;
use Kdyby\Doctrine\DI\OrmExtension;
use Nette\DI\CompilerExtension;
use ReflectionClass;
use RuntimeException;

final class DoctrineExtensionsExtension extends CompilerExtension implements IEntityProvider
{

	// Constants
	const EVENTS_TAG = 'kdyby.subscriber';

	/** @var array */
	private $defaults = [
		'translatable' => [
			'translatable' => 'cs_CZ',
			'default' => 'cs_CZ',
			'translationFallback' => FALSE,
			'persistDefaultTranslation' => FALSE,
			'skipOnLoad' => FALSE,
		],
		'annotations' => [
			'loggable' => TRUE,
			'sluggable' => TRUE,
			'softDeleteable' => TRUE,
			'treeable' => TRUE,
			'blameable' => TRUE,
			'timestampable' => TRUE,
			'translatable' => TRUE,
			'uploadable' => TRUE,
			'sortable' => TRUE,
			'ipTraceable' => TRUE,
		],
	];

	/**
	 * Register services
	 *
	 * @return void
	 */
	public function loadConfiguration()
	{
		if (!class_exists(OrmExtension::class, TRUE)) {
			throw new RuntimeException('Kdyby\Doctrine\DI\OrmExtension is missing. Please register it first.');
		}

		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Loggable ==================================================

		if ($config['annotations']['loggable'] === TRUE) {
			$builder->addDefinition($this->prefix('loggable'))
				->setClass(LoggableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// Sluggable =================================================

		if ($config['annotations']['sluggable'] === TRUE) {
			$builder->addDefinition($this->prefix('sluggable'))
				->setClass(SluggableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// SoftDeleteable ============================================

		if ($config['annotations']['softDeleteable'] === TRUE) {
			$builder->addDefinition($this->prefix('softDeleteable'))
				->setClass(SoftDeleteableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// Treeable ==================================================

		if ($config['annotations']['treeable'] === TRUE) {
			$builder->addDefinition($this->prefix('treeable'))
				->setClass(TreeListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// Blameable =================================================

		if ($config['annotations']['blameable'] === TRUE) {
			$builder->addDefinition($this->prefix('blameable'))
				->setClass(BlameableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// Timestampable =============================================

		if ($config['annotations']['timestampable'] === TRUE) {
			$builder->addDefinition($this->prefix('timestampable'))
				->setClass(TimestampableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// Translatable ==============================================

		if ($config['annotations']['translatable'] === TRUE) {
			$builder->addDefinition($this->prefix('translatable'))
				->setClass(TranslatableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addSetup('setDefaultLocale', [$config['translatable']['default']])
				->addSetup('setTranslatableLocale', [$config['translatable']['translatable']])
				->addSetup('setPersistDefaultLocaleTranslation', [$config['translatable']['translationFallback']])
				->addSetup('setTranslationFallback', [$config['translatable']['persistDefaultTranslation']])
				->addSetup('setSkipOnLoad', [$config['translatable']['skipOnLoad']])
				->addTag(self::EVENTS_TAG);
		}

		// Sortable ==================================================

		if ($config['annotations']['sortable'] === TRUE) {
			$builder->addDefinition($this->prefix('sortable'))
				->setClass(SortableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}

		// IpTraceable ===============================================

		if ($config['annotations']['ipTraceable'] === TRUE) {
			$builder->addDefinition($this->prefix('ipTraceable'))
				->setClass(IpTraceableListener::class)
				->addSetup('setAnnotationReader', ['@' . Reader::class])
				->addTag(self::EVENTS_TAG);
		}
	}

	/**
	 * KDYBY DOCTRINE INTERFACE ************************************************
	 */

	/**
	 * @return array
	 */
	public function getEntityMappings()
	{
		$config = $this->validateConfig($this->defaults);
		$mappings = [];

		if ($config['annotations']['loggable'] === TRUE) {
			$loggable = new ReflectionClass(LoggableListener::class);
			$mappings['Gedmo\Loggable\Entity'] = dirname($loggable->getFileName()) . '/Entity';
		}

		if ($config['annotations']['translatable'] === TRUE) {
			$translatable = new ReflectionClass(TranslatableListener::class);
			$mappings['Gedmo\Translatable\Entity'] = dirname($translatable->getFileName()) . '/Entity';
		}

		if ($config['annotations']['treeable'] === TRUE) {
			$treeable = new ReflectionClass(TreeListener::class);
			$mappings['Gedmo\Tree\Entity'] = dirname($treeable->getFileName()) . '/Entity';
		}

		return $mappings;
	}

}
