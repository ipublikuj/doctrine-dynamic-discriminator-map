<?php declare(strict_types = 1);

namespace Tests\Cases;

use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../BaseTestCase.php';

require_once __DIR__ . '/../../../libs/models/valid/PersonEntity.php';
require_once __DIR__ . '/../../../libs/models/valid/AbstractEntity.php';
require_once __DIR__ . '/../../../libs/models/valid/StudentEntity.php';
require_once __DIR__ . '/../../../libs/models/valid/TeacherEntity.php';

/**
 * @testCase
 */
class DiscriminatorTest extends BaseTestCase
{

	/** @var string[] */
	protected array $additionalConfigs = [
		__DIR__ . DIRECTORY_SEPARATOR . 'entities.neon',
	];

	public function testMapping(): void
	{
		$this->generateDbSchema();

		$student = new Models\StudentEntity();
		$student->setUsername('student');

		$this->getEntityManager()->persist($student);
		$this->getEntityManager()->flush();

		$this->getEntityManager()->clear();

		$teacher = new Models\TeacherEntity();
		$teacher->setUsername('teacher');

		$this->getEntityManager()->persist($teacher);
		$this->getEntityManager()->flush();

		$this->getEntityManager()->clear();

		/** @var Models\PersonEntity[]|null $persons */
		$persons = $this->getEntityManager()->getRepository(Models\PersonEntity::class)
			->findAll();

		Assert::equal(2, count($persons));

		/** @var Models\StudentEntity[]|null $students */
		$students = $this->getEntityManager()->getRepository(Models\StudentEntity::class)
			->findAll();

		Assert::equal(1, count($students));
		Assert::equal('student', reset($students)->getUsername());

		$teacher = new Models\StudentParentEntity();
		$teacher->setUsername('student-parents');

		$this->getEntityManager()->persist($teacher);
		$this->getEntityManager()->flush();

		$this->getEntityManager()->clear();

		/** @var Models\PersonEntity[]|null $persons */
		$persons = $this->getEntityManager()->getRepository(Models\PersonEntity::class)
			->findAll();

		Assert::equal(3, count($persons));
	}

}

$test_case = new DiscriminatorTest();
$test_case->run();
