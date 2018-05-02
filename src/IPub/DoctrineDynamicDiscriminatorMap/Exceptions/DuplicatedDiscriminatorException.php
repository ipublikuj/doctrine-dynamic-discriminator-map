<?php
/**
 * DuplicatedDiscriminatorException.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Exceptions
 * @since          1.0.0
 *
 * @date           06.11.15
 */

declare(strict_types = 1);

namespace IPub\DoctrineDynamicDiscriminatorMap\Exceptions;

class DuplicatedDiscriminatorException extends \InvalidArgumentException implements IException
{
}
