<?php
/**
 * Test: IPub\DoctrineDynamicDiscriminatorMap\Blameable
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

use IPubTests\DoctrineDynamicDiscriminatorMap\Models\StudentEntity;
use Nette;

use Tester;
use Tester\Assert;

use Doctrine;
use Doctrine\ORM;
use Doctrine\Common;

use IPub;
use IPub\DoctrineDynamicDiscriminatorMap;
use IPub\DoctrineDynamicDiscriminatorMap\Events;

require __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/models/PersonEntity.php';
require_once __DIR__ . '/models/StudentEntity.php';
require_once __DIR__ . '/models/TeacherEntity.php';

/**
 * Registering doctrine blameable functions tests
 *
 * @package        iPublikuj:DoctrineDynamicDiscriminatorMap!
 * @subpackage     Tests
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class Discriminator extends Tester\TestCase
{
	/**
	 * @var \Nette\DI\Container
	 */
	private $container;

	/**
	 * @var \Kdyby\Doctrine\EntityManager
	 */
	private $em;

	protected function setUp()
	{
		parent::setUp();

		$this->container = $this->createContainer();
		$this->em = $this->container->getByType('Kdyby\Doctrine\EntityManager');
	}

	public function testCreate()
	{
		$this->generateDbSchema();

		$student = new StudentEntity;
		$student->setUsername('student');

		$this->em->persist($student);
		$this->em->flush();

		$this->em->clear();

		$teacher = new StudentEntity;
		$teacher->setUsername('teacher');

		$this->em->persist($teacher);
		$this->em->flush();

		$this->em->clear();

		$persons = $this->em->getRepository('IPubTests\DoctrineDynamicDiscriminatorMap\Models\PersonEntity')->findAll();

		Assert::equal(2, count($persons));
	}

	private function generateDbSchema()
	{
		$schema = new ORM\Tools\SchemaTool($this->em);
		$schema->createSchema($this->em->getMetadataFactory()->getAllMetadata());
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer()
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5('withModel')]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . '/files/config.neon', !isset($config->defaultExtensions['nette']) ? 'v23' : 'v22');
		$config->addConfig(__DIR__ . '/files/entities.neon', $config::NONE);

		DoctrineDynamicDiscriminatorMap\DI\DoctrineDynamicDiscriminatorMapExtension::register($config);

		return $config->createContainer();
	}
}

\run(new Discriminator());
