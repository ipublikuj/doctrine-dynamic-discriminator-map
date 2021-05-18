<?php declare(strict_types = 1);

/**
 * DynamicDiscriminatorSubscriber.php
 *
 * @copyright      More in LICENSE.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Events
 * @since          0.1.0
 *
 * @date           06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\Events;

use Doctrine\Common;
use Doctrine\ORM;
use IPub\DoctrineDynamicDiscriminatorMap\Entities;
use IPub\DoctrineDynamicDiscriminatorMap\Exceptions;
use Nette;
use ReflectionClass;
use ReflectionException;

/**
 * Doctrine dynamic discriminator map subscriber
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Events
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 *
 * @template TEntityClass of object
 */
final class DynamicDiscriminatorSubscriber implements Common\EventSubscriber
{

	use Nette\SmartObject;

	/** @var string[] */
	private static array $discriminators = [];

	/**
	 * Register events
	 *
	 * @return string[]
	 */
	public function getSubscribedEvents(): array
	{
		return [
			ORM\Events::loadClassMetadata,
		];
	}

	/**
	 * @param ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 *
	 * @return void
	 *
	 * @throws ORM\ORMException
	 * @throws ReflectionException
	 */
	public function loadClassMetadata(ORM\Event\LoadClassMetadataEventArgs $eventArgs): void
	{
		$metadata = $eventArgs->getClassMetadata();
		/** @phpstan-var ReflectionClass<TEntityClass> $classReflection */
		$classReflection = $metadata->getReflectionClass();

		$reader = new Common\Annotations\AnnotationReader();

		$discriminatorMap = $reader->getClassAnnotation($classReflection, ORM\Mapping\DiscriminatorMap::class);

		$em = $eventArgs->getEntityManager();

		$discriminatorMapExtension = $this->detectFromChildren($em, $classReflection);

		if ($discriminatorMap instanceof ORM\Mapping\DiscriminatorMap && $discriminatorMapExtension !== []) {
			$extendedDiscriminatorMap = $discriminatorMap->value;

			foreach ($discriminatorMapExtension as $name => $className) {
				if (array_search($className, $extendedDiscriminatorMap, true) === false) {
					$extendedDiscriminatorMap[$name] = $className;
				}
			}

			/**
			 * @var string $name
			 * @var class-string $classString
			 */
			foreach ($extendedDiscriminatorMap as $name => $classString) {
				$metadata->addDiscriminatorMapClass($name, $classString);
			}
		}
	}

	/**
	 * @param ORM\EntityManager $em
	 * @param ReflectionClass $parentClassReflection
	 *
	 * @return string[]
	 *
	 * @throws ORM\ORMException
	 * @throws Exceptions\InvalidStateException
	 * @throws ReflectionException
	 *
	 * @phpstan-param ReflectionClass<TEntityClass> $parentClassReflection
	 */
	private function detectFromChildren(ORM\EntityManager $em, ReflectionClass $parentClassReflection): array
	{
		self::$discriminators = [];

		$mappingDriver = $em->getConfiguration()->getMetadataDriverImpl();

		if ($mappingDriver === null) {
			throw new Exceptions\InvalidStateException('Entity manager mapping driver could not be loaded');
		}

		/** @phpstan-var class-string of object $class */
		foreach ($mappingDriver->getAllClassNames() as $class) {
			/** @phpstan-var ReflectionClass<TEntityClass> $childrenClassReflection */
			$childrenClassReflection = new ReflectionClass($class);

			if ($childrenClassReflection->getParentClass() === false) {
				continue;
			}

			if (!$childrenClassReflection->isSubclassOf($parentClassReflection->getName())) {
				continue;
			}

			$discriminator = $this->getDiscriminatorForClass($childrenClassReflection);

			if ($discriminator !== null) {
				self::$discriminators[$discriminator] = $class;
			}
		}

		return self::$discriminators;
	}

	/**
	 * @param ReflectionClass $classReflection
	 *
	 * @return string|null
	 *
	 * @throws ReflectionException
	 *
	 * @phpstan-param ReflectionClass<TEntityClass> $classReflection
	 */
	private function getDiscriminatorForClass(ReflectionClass $classReflection): ?string
	{
		if ($classReflection->isAbstract()) {
			return null;
		}

		if ($classReflection->implementsInterface(Entities\IDiscriminatorProvider::class)) {
			/** @var Entities\IDiscriminatorProvider $object */
			$object = $classReflection->newInstanceWithoutConstructor();

			$discriminator = $object->getDiscriminatorName();

		} else {
			$discriminator = lcfirst($classReflection->getShortName());
		}

		$this->ensureDiscriminatorIsUnique($discriminator, $classReflection);

		return $discriminator;
	}

	/**
	 * @param string $discriminator
	 * @param ReflectionClass $classReflection
	 *
	 * @return void
	 *
	 * @throws Exceptions\DuplicatedDiscriminatorException
	 *
	 * @phpstan-param ReflectionClass<TEntityClass> $classReflection
	 */
	private function ensureDiscriminatorIsUnique(string $discriminator, ReflectionClass $classReflection): void
	{
		if (in_array($discriminator, array_keys(self::$discriminators), true)) {
			throw new Exceptions\DuplicatedDiscriminatorException(sprintf('Found duplicate discriminator map entry "%s" in "%s".', $discriminator, $classReflection->getName()));
		}
	}

}
