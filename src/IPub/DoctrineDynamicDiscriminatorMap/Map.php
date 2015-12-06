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

/**
 * Dynamic discriminator map collection
 *
 * @package		iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage	common
 *
 * @author Adam Kadlec <adam.kadlec@fastybird.com>
 */
class Map extends Nette\Object implements \ArrayAccess, \Countable, \IteratorAggregate
{
	/**
	 * @var Utils\ArrayHash|[]
	 */
	protected $items;

	public function __construct()
	{
		$this->items = new Utils\ArrayHash;
	}

	/**
	 * @param MapItem $item
	 *
	 * @return $this
	 */
	public function addItem(MapItem $item)
	{
		$this->items[(string) $item] = $item;

		return $this;
	}

	/**
	 * @return Utils\ArrayHash
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * @param mixed $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return $this->items->offsetExists($offset);
	}

	/**
	 * @param mixed $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->items->offsetGet($offset);
	}

	/**
	 * @param mixed $offset
	 * @param MapItem $value
	 *
	 * @return $this
	 */
	public function offsetSet($offset, $value)
	{
		$this->addItem($value);

		return $this;
	}

	/**
	 * @param mixed $offset
	 *
	 * @return $this
	 */
	public function offsetUnset($offset)
	{
		$this->items->offsetUnset($offset);

		return $this;
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return $this->items->count();
	}

	/**
	 * @return \RecursiveArrayIterator
	 */
	public function getIterator()
	{
		return $this->items->getIterator();
	}
}