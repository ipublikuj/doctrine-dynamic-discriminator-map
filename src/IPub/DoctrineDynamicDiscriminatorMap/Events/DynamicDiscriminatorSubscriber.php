<?php
/**
 * DynamicDiscriminatorSubscriber.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Events
 * @since          1.0.0
 *
 * @date           06.12.15
 */

declare(strict_types = 1);

namespace IPub\DoctrineDynamicDiscriminatorMap\Events;

use ReflectionClass;
use ReflectionException;

use Nette;

use Doctrine\Common;
use Doctrine\ORM;

use IPub\DoctrineDynamicDiscriminatorMap;
use IPub\DoctrineDynamicDiscriminatorMap\Entities;
use IPub\DoctrineDynamicDiscriminatorMap\Exceptions;

/**
 * Doctrine dynamic discriminator map subscriber
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Events
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class DynamicDiscriminatorSubscriber implements Common\EventSubscriber
{
	/**
	 * Implement nette smart magic
	 */
	use Nette\SmartObject;

	/**
	 * @var array
	 */
	static private $discriminators = [];

	/**
	 * Register events
	 *
	 * @return string[]
	 */
	public function getSubscribedEvents() : array
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
	 * @throws Common\Annotations\AnnotationException
	 * @throws ORM\ORMException
	 * @throws ReflectionException
	 */
	public function loadClassMetadata(ORM\Event\LoadClassMetadataEventArgs $eventArgs) : void
	{
		/** @var ORM\Mapping\ClassMetadata $metadata */
		$metadata = $eventArgs->getClassMetadata();
		$classReflection = $metadata->getReflectionClass();

		if ($classReflection === NULL) {
			$classReflection = new ReflectionClass($metadata->getName());
		}

		$reader = new Common\Annotations\AnnotationReader;

		/** @var ORM\Mapping\DiscriminatorMap $discriminatorMap */
		$discriminatorMap = $reader->getClassAnnotation($classReflection, ORM\Mapping\DiscriminatorMap::class);

		$em = $eventArgs->getEntityManager();

		if ($discriminatorMap !== NULL && ($discriminatorMapExtension = $this->detectFromChildren($em, $classReflection)) && $discriminatorMapExtension !== []) {
			$extendedDiscriminatorMap = $discriminatorMap->value;

			foreach ($discriminatorMapExtension as $name => $className) {
				if (array_search($className, $extendedDiscriminatorMap) === FALSE) {
					$extendedDiscriminatorMap[$name] = $className;
				}
			}

			$metadata->setDiscriminatorMap($extendedDiscriminatorMap);
		}
	}

	/**
	 * @param ORM\EntityManager $em
	 * @param ReflectionClass $parentClassReflection
	 *
	 * @return array
	 *
	 * @throws ORM\ORMException
	 * @throws ReflectionException
	 */
	private function detectFromChildren(ORM\EntityManager $em, ReflectionClass $parentClassReflection) : array
	{
		self::$discriminators = [];

		foreach ($em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames() as $class) {
			$childrenClassReflection = new ReflectionClass($class);

			if ($childrenClassReflection->getParentClass() === NULL) {
				continue;
			}

			if (!$childrenClassReflection->isSubclassOf($parentClassReflection->getName())) {
				continue;
			}

			$discriminator = $this->getDiscriminatorForClass($childrenClassReflection);

			if ($discriminator !== NULL) {
				self::$discriminators[$discriminator] = $class;
			}
		}

		return self::$discriminators;
	}

	/**
	 * @param ReflectionClass $classReflection
	 *
	 * @return string|NULL
	 */
	private function getDiscriminatorForClass(ReflectionClass $classReflection) : ?string
	{
		if ($classReflection->isAbstract()) {
			return NULL;
		}

		if ($classReflection->implementsInterface(Entities\IDiscriminatorProvider::class)) {
			/** @var Entities\IDiscriminatorProvider $object */
			$object = $classReflection->newInstanceWithoutConstructor();

			$discriminator = $object->getDiscriminatorName();

			if (!$discriminator) {
				return NULL;
			}

		} else {
			$discriminator = lcfirst($classReflection->getShortName());
		}

		$this->ensureDiscriminatorIsUnique($discriminator, $classReflection);

		return $discriminator;
	}

	/**
	 * @param string $discriminator
	 * @param ReflectionClass $class
	 *
	 * @return void
	 *
	 * @throws Exceptions\DuplicatedDiscriminatorException
	 */
	private function ensureDiscriminatorIsUnique($discriminator, ReflectionClass $class) : void
	{
		if (in_array($discriminator, array_keys(self::$discriminators))) {
			throw new Exceptions\DuplicatedDiscriminatorException(sprintf('Found duplicate discriminator map entry "%s" in "%s".', $discriminator, $class->getName()));
		}
	}
}
