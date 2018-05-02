<?php
/**
 * Test: IPub\DoctrineDynamicDiscriminatorMap\SecondModels
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

namespace IPubTests\DoctrineDynamicDiscriminatorMap\SecondModels;

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
	public function getDiscriminatorName() : string
	{
		return 'student';
	}
}
