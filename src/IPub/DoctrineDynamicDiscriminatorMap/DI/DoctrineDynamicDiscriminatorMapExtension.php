<?php
/**
 * DoctrineDynamicDiscriminatorMapExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     DI
 * @since          5.0
 *
 * @date           06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

use Kdyby;
use Kdyby\Events as KdybyEvents;

use IPub\DoctrineDynamicDiscriminatorMap;
use IPub\DoctrineDynamicDiscriminatorMap\Events;

/**
 * Doctrine dynamic discriminator map extension container
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class DoctrineDynamicDiscriminatorMapExtension extends DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		// Define events
		$builder->addDefinition($this->prefix('listener'))
			->setClass(Events\DynamicDiscriminatorListener::CLASS_NAME)
			->addTag(KdybyEvents\DI\EventsExtension::TAG_SUBSCRIBER);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 */
	public static function register(Nette\Configurator $config, $extensionName = 'dynamicDiscriminatorMap')
	{
		$config->onCompile[] = function (Nette\Configurator $config, Nette\DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new DoctrineDynamicDiscriminatorMapExtension());
		};
	}
}
