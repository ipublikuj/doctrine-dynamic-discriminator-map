<?php declare(strict_types = 1);

/**
 * DoctrineDynamicDiscriminatorMapExtension.php
 *
 * @copyright      More in LICENSE.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     DI
 * @since          0.1.0
 *
 * @date           06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\DI;

use Doctrine;
use IPub\DoctrineDynamicDiscriminatorMap\Events;
use Nette;
use Nette\DI;

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
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Define events
		$builder->addDefinition($this->prefix('subscriber'))
			->setType(Events\DynamicDiscriminatorSubscriber::class);
	}

	/**
	 * @return void
	 */
	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();

		/** @var string $emServiceName */
		$emServiceName = $builder->getByType(Doctrine\ORM\EntityManagerInterface::class, true);

		/** @var DI\Definitions\ServiceDefinition $emService */
		$emService = $builder->getDefinition($emServiceName);

		$emService
			->addSetup('?->getEventManager()->addEventSubscriber(?)', [
				'@self',
				$builder->getDefinition($this->prefix('subscriber')),
			]);
	}

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(
		Nette\Configurator $config,
		string $extensionName = 'dynamicDiscriminatorMap'
	): void {
		$config->onCompile[] = function (Nette\Configurator $config, DI\Compiler $compiler) use ($extensionName): void {
			$compiler->addExtension($extensionName, new DoctrineDynamicDiscriminatorMapExtension());
		};
	}

}
