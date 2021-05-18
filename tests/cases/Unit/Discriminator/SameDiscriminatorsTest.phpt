<?php declare(strict_types = 1);

namespace Tests\Cases;

use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../BaseTestCase.php';

require_once __DIR__ . '/../../../libs/models/invalid/PersonEntity.php';
require_once __DIR__ . '/../../../libs/models/invalid/StudentEntity.php';
require_once __DIR__ . '/../../../libs/models/invalid/SuperStudentEntity.php';

/**
 * @testCase
 */
class SameDiscriminatorsTest extends BaseTestCase
{

	/** @var string[] */
	protected array $additionalConfigs = [
		__DIR__ . DIRECTORY_SEPARATOR . 'wrong-entities.neon',
	];

	/**
	 * @throws IPub\DoctrineDynamicDiscriminatorMap\Exceptions\DuplicatedDiscriminatorException
	 */
	public function testMapping(): void
	{
		$this->generateDbSchema();

		/** @var Models\PersonEntity[]|null $persons */
		$persons = $this->getEntityManager()
			->getRepository(Models\PersonEntity::class)
			->findAll();

		Assert::equal(0, count($persons));
	}

}

$test_case = new SameDiscriminatorsTest();
$test_case->run();
