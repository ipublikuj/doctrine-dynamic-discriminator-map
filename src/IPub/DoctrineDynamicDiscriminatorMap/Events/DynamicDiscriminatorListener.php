<?php
/**
 * DynamicDiscriminatorListener.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage	Events
 * @since		5.0
 *
 * @date		06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\Events;

use Nette;

use Doctrine;
use Doctrine\Common;
use Doctrine\ORM;

use Kdyby;
use Kdyby\Events;

use IPub;
use IPub\DoctrineDynamicDiscriminatorMap;

/**
 * Doctrine dynamic discriminator map listener
 *
 * @package		iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage	Events
 *
 * @author Adam Kadlec <adam.kadlec@fastybird.com>
 */
class DynamicDiscriminatorListener extends Nette\Object implements Events\Subscriber
{
	/**
	 * @var DoctrineDynamicDiscriminatorMap\Map
	 */
	protected $mapping;

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
	 * @param DoctrineDynamicDiscriminatorMap\Map $mapping
	 */
	public function __construct(DoctrineDynamicDiscriminatorMap\Map $mapping) {
		$this->mapping = $mapping;
	}

	/**
	 * @param ORM\Event\LoadClassMetadataEventArgs $eventArgs
	 */
	public function loadClassMetadata(ORM\Event\LoadClassMetadataEventArgs $eventArgs)
	{
		$metadata = $eventArgs->getClassMetadata();
		$class = $metadata->getReflectionClass();

		if ($class === NULL) {
			$class = new \ReflectionClass($metadata->getName());
		}

		foreach ($this->mapping as $mapItem) {
			if ($class->getName() == $mapItem->getEntity()) {
				$reader = new Common\Annotations\AnnotationReader;
				$discriminatorMap = [];

				if (NULL !== $discriminatorMapAnnotation = $reader->getClassAnnotation($class, 'Doctrine\ORM\Mapping\DiscriminatorMap')) {
					$discriminatorMap = $discriminatorMapAnnotation->value;
				}

				$discriminatorMap = array_merge($discriminatorMap, (array) $mapItem->getMaps());
				$discriminatorMap = array_merge($discriminatorMap, [$mapItem->getName() => $mapItem->getEntity()]);

				$metadata->setDiscriminatorMap($discriminatorMap);
			}
		}
	}
}