<?php
/**
 * Map.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage	common
 * @since		5.0
 *
 * @date		06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap;

use Nette;
use Nette\Utils;

use IPub;
use IPub\DoctrineDynamicDiscriminatorMap\Exceptions;

/**
 * Entity discriminator map association
 *
 * @package		iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage	common
 *
 * @author Adam Kadlec <adam.kadlec@fastybird.com>
 */
class MapItem extends Nette\Object
{
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $entity;

	/**
	 * @var Utils\ArrayHash|[]
	 */
	protected $map;

	/**
	 * @param string $name
	 * @param string $entity
	 *
	 * @throw Exceptions\InvalidArgumentException
	 */
	public function __construct($name, $entity)
	{
		// Store map name
		$this->name = (string) $name;

		// Init maps collection
		$this->map = new Utils\ArrayHash;

		// Store parent entity
		$this->setEntity($entity);
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = (string) $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $entity
	 *
	 * @return $this
	 *
	 * @throw Exceptions\InvalidArgumentException
	 */
	public function setEntity($entity)
	{
		if (!class_exists($entity)) {
			throw new Exceptions\InvalidArgumentException('Provided entity "'. $entity .'" is not valid class.');
		}

		$this->entity = $entity;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getEntity()
	{
		return $this->entity;
	}

	/**
	 * @param string $name
	 * @param string $entity
	 *
	 * @return $this
	 *
	 * @throw Exceptions\InvalidArgumentException
	 */
	public function addMap($name, $entity)
	{
		if (!class_exists($entity)) {
			throw new Exceptions\InvalidArgumentException('Provided entity "'. $entity .'" is not valid class.');
		}

		$this->map->offsetSet($name, $entity);

		return $this;
	}

	/**
	 * @return Utils\ArrayHash|[]
	 */
	public function getMaps()
	{
		return $this->map;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->name;
	}
}