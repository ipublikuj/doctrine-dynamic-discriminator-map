<?php
/**
 * DynamicDiscriminatorListener.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Entities
 * @since          1.0.1
 *
 * @date           06.01.16
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\Entities;

/**
 * Doctrine dynamic discriminator map entity interface
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IDiscriminatorProvider
{
	/**
	 * Define class name
	 */
	const CLASS_NAME = __CLASS__;

	/**
	 * @return string
	 */
	function getDiscriminatorName();
}
