<?php
/**
 * DoctrineDynamicDiscriminatorMapExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		06.12.15
 */

namespace IPub\DoctrineDynamicDiscriminatorMap\DI;

use IPub\DoctrineDynamicDiscriminatorMap\MapItem;
use Nette;
use Nette\DI;
use Nette\Utils;
use Nette\PhpGenerator as Code;

class DoctrineDynamicDiscriminatorMapExtension extends DI\CompilerExtension
{
	/**
	 * Extension default configuration
	 *
	 * @var array
	 */
	protected $defaults = [
		'mapping' => []
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('map'))
			->setClass('IPub\DoctrineDynamicDiscriminatorMap\Map');

		foreach($config['mapping'] as $entityName => $map) {
			$mapItem = new MapItem($entityName, $map['entity']);

			foreach($map['map'] as $name=>$entity) {
				$mapItem->addMap($name, $entity);
			}

			$builder->getDefinition($this->prefix('map'))
				->addSetup('addItem', [$mapItem]);
		}

		// Define events
		$builder->addDefinition($this->prefix('listeners.dynamicDiscriminatorListener'))
			->setClass('IPub\DoctrineDynamicDiscriminatorMap\Events\DynamicDiscriminatorListener')
			->addTag('kdyby.subscriber');
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