<?php
/**
 * Test: IPub\DoctrineDynamicDiscriminatorMap\Models
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Tests
 * @since          1.0.1
 *
 * @date           07.01.16
 */

declare(strict_types = 1);

namespace IPubTests\DoctrineDynamicDiscriminatorMap\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="person")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *      "person" = "PersonEntity"
 * })
 */
class PersonEntity
{
	/**
	 * @var int
	 *
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(type="string")
	 */
	private $username;

	/**
	 * @return string
	 */
	public function getUsername() : string
	{
		return $this->username;
	}

	/**
	 * @param string $username
	 *
	 * @return void
	 */
	public function setUsername(string $username) : void
	{
		$this->username = $username;
	}
}
