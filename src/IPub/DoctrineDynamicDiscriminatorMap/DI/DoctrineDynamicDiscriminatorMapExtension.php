<?php
/**
 * DoctrineDynamicDiscriminatorMapExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           06.12.15
 */

declare(strict_types = 1);

namespace IPub\DoctrineDynamicDiscriminatorMap\DI;

use Doctrine;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

use IPub\DoctrineDynamicDiscriminatorMap\Events;

/**
 * Doctrine dynamic discriminator map extension container
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
final class DoctrineDynamicDiscriminatorMapExtension extends DI\CompilerExtension
{
	/**
	 * @return void
	 */
	public function loadConfiguration() : void
	{
		$builder = $this->getContainerBuilder();

		// Define events
		$builder->addDefinition($this->prefix('subscriber'))
			->setClass(Events\DynamicDiscriminatorSubscriber::class);
	}

	/**
	 * @return void
	 */
	public function beforeCompile() : void
	{
		$builder = $this->getContainerBuilder();

		$builder->getDefinition($builder->getByType(Doctrine\ORM\EntityManagerInterface::class, TRUE))
			->addSetup('?->getEventManager()->addEventSubscriber(?)', ['@self', $builder->getDefinition($this->prefix('subscriber'))]);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(Nette\Configurator $config, string $extensionName = 'dynamicDiscriminatorMap') : void
	{
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName) {
			$compiler->addExtension($extensionName, new DoctrineDynamicDiscriminatorMapExtension);
		};
	}
}
