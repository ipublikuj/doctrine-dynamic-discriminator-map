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
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity extends PersonEntity
{

}
