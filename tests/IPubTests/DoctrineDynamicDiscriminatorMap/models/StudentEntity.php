<?php
/**
 * Test: IPub\DoctrineDynamicDiscriminatorMap\Models
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Tests
 * @since          1.0.1
 *
 * @date           07.01.16
 */

namespace IPubTests\DoctrineDynamicDiscriminatorMap\Models;

use Doctrine;
use Doctrine\ORM\Mapping as ORM;

use IPub\DoctrineDynamicDiscriminatorMap\Entities;

/**
 * @ORM\Entity
 */
class StudentEntity extends PersonEntity implements Entities\IDiscriminatorProvider
{

	/**
	 * @return string
	 */
	public function getDiscriminatorName()
	{
		return 'student';
	}
}
