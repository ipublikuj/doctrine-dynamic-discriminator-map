<?php declare(strict_types = 1);

/**
 * InvalidStateException.php
 *
 * @copyright      More in LICENSE.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           18.05.21
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\Exceptions;

use RuntimeException;

class InvalidStateException extends RuntimeException implements IException
{

}
