<?php
/**
 * Test: IPub\DoctrineDynamicDiscriminatorMap\Extension
 * @testCase
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

namespace IPubTests\DoctrineDynamicDiscriminatorMap;

use Nette;

use Tester;
use Tester\Assert;

use IPub;
use IPub\DoctrineDynamicDiscriminatorMap;

require __DIR__ . '/../bootstrap.php';

/**
 * Registering doctrine dynamic discriminator map extension tests
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ExtensionTest extends Tester\TestCase
{
	public function testFunctional()
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('dynamicDiscriminatorMap.listener') instanceof DoctrineDynamicDiscriminatorMap\Events\DynamicDiscriminatorListener);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer()
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		DoctrineDynamicDiscriminatorMap\DI\DoctrineDynamicDiscriminatorMapExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
