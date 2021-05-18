<?php declare(strict_types = 1);

/**
 * DuplicatedDiscriminatorException.php
 *
 * @copyright      More in LICENSE.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           06.11.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\Exceptions;

use InvalidArgumentException;

class DuplicatedDiscriminatorException extends InvalidArgumentException implements IException
{

}
