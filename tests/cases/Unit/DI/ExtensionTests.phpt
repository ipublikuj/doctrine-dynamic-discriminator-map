<?php declare(strict_types = 1);

namespace Tests\Cases;

use IPub\DoctrineDynamicDiscriminatorMap\Events;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';
require_once __DIR__ . '/../BaseTestCase.php';

/**
 * @testCase
 */
final class ExtensionTests extends BaseTestCase
{

	public function testFunctional(): void
	{
		$dic = $this->createContainer();

		Assert::true($dic->getService('dynamicDiscriminatorMap.subscriber') instanceof Events\DynamicDiscriminatorSubscriber);
	}

}

$test_case = new ExtensionTests();
$test_case->run();
