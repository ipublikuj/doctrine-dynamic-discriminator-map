<?php
/**
 * DynamicDiscriminatorSubscriber.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Events
 * @since          1.0.0
 *
 * @date           06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\Events;

use Nette;

use Doctrine;
use Doctrine\Common;
use Doctrine\ORM;

use IPub;
use IPub\DoctrineDynamicDiscriminatorMap;
use IPub\DoctrineDynamicDiscriminatorMap\Entities;
use IPub\DoctrineDynamicDiscriminatorMap\Exceptions;

/**
 * Doctrine dynamic discriminator map subscriber
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Events
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DynamicDiscriminatorSubscriber extends Nette\Object implements Common\EventSubscriber
{
	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @var array
	 */
	static private $discriminators = [];

	/**
	 * Register events
	 *
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return [
			'Doctrine\\ORM\\Event::loadClassMetadata'
		];
	}

	/**
	 * @param ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 */
	public function loadClassMetadata(ORM\Event\LoadClassMetadataEventArgs $eventArgs)
	{
		/** @var ORM\Mapping\ClassMetadata $metadata */
		$metadata = $eventArgs->getClassMetadata();
		$classReflection = $metadata->getReflectionClass();

		if ($classReflection === NULL) {
			$classReflection = new \ReflectionClass($metadata->getName());
		}

		$reader = new Common\Annotations\AnnotationReader;

		/** @var ORM\Mapping\DiscriminatorMap $discriminatorMap */
		$discriminatorMap = $reader->getClassAnnotation($classReflection, 'Doctrine\ORM\Mapping\DiscriminatorMap');

		$em = $eventArgs->getEntityManager();

		if ($discriminatorMap !== NULL && ($discriminatorMapExtension = $this->detectFromChildren($em, $classReflection)) && $discriminatorMapExtension !== []) {
			$extendedDiscriminatorMap = array_merge($discriminatorMap->value, $discriminatorMapExtension);

			$metadata->setDiscriminatorMap($extendedDiscriminatorMap);
		}
	}

	/**
	 * @param ORM\EntityManager $em
	 * @param \ReflectionClass $parentClassReflection
	 *
	 * @return array
	 */
	private function detectFromChildren(ORM\EntityManager $em, \ReflectionClass $parentClassReflection)
	{
		self::$discriminators = [];

		foreach ($em->getConfiguration()->getMetadataDriverImpl()->getAllClassNames() as $class) {
			$childrenClassReflection = new \ReflectionClass($class);

			if ($childrenClassReflection->getParentClass() === NULL) {
				continue;
			}

			if (!$childrenClassReflection->isSubclassOf($parentClassReflection->getName())) {
				continue;
			}

			if ($discriminator = $this->getDiscriminatorForClass($childrenClassReflection)) {
				self::$discriminators[$discriminator] = $class;
			}
		}

		return self::$discriminators;
	}

	/**
	 * @param \ReflectionClass $classReflection
	 *
	 * @return bool
	 */
	private function getDiscriminatorForClass(\ReflectionClass $classReflection)
	{
		if ($classReflection->isAbstract()) {
			return FALSE;
		}

		if ($classReflection->implementsInterface(Entities\IDiscriminatorProvider::CLASS_NAME)) {
			/** @var Entities\IDiscriminatorProvider $object */
			$object = $classReflection->newInstanceWithoutConstructor();

			$discriminator = $object->getDiscriminatorName();

			if (!$discriminator) {
				return FALSE;
			}

		} else {
			$discriminator = lcfirst($classReflection->getShortName());
		}

		$this->ensureDiscriminatorIsUnique($discriminator, $classReflection);

		return $discriminator;
	}

	/**
	 * @param string $discriminator
	 * @param \ReflectionClass $class
	 *
	 * @throws Exceptions\DuplicatedDiscriminatorException
	 */
	private function ensureDiscriminatorIsUnique($discriminator, \ReflectionClass $class)
	{
		if (in_array($discriminator, array_keys(self::$discriminators))) {
			throw new Exceptions\DuplicatedDiscriminatorException(sprintf('Found duplicate discriminator map entry "%s" in "%s".', $discriminator, $class->getName()));
		}
	}
}
