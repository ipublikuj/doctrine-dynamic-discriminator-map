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

/**
 * @ORM\Entity
 */
class NotMappedEntity extends PersonEntity
{

}
