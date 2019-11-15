<?php
/**
 * Test: IPub\DoctrineDynamicDiscriminatorMap\Extension
 *
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Tests
 * @since          1.0.1
 *
 * @date           07.01.16
 */

declare(strict_types = 1);

namespace IPubTests\DoctrineDynamicDiscriminatorMap;

use Nette;

use Tester;
use Tester\Assert;

use IPub\DoctrineDynamicDiscriminatorMap;

require __DIR__ . '/../bootstrap.php';

/**
 * Registering doctrine dynamic discriminator map extension tests
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class ExtensionTest extends Tester\TestCase
{
	public function testFunctional() : void
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('dynamicDiscriminatorMap.subscriber') instanceof DoctrineDynamicDiscriminatorMap\Events\DynamicDiscriminatorSubscriber);
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5((string) time())]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . '/files/config.neon');

		DoctrineDynamicDiscriminatorMap\DI\DoctrineDynamicDiscriminatorMapExtension::register($config);

		return $config->createContainer();
	}
}

\run(new ExtensionTest());
