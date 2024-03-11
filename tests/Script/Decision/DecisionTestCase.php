<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Decision;

use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;
use PHPUnit\Framework\TestCase;

abstract class DecisionTestCase extends TestCase
{
	protected Helper|\PHPUnit\Framework\MockObject\MockObject        $helper;

	protected Configuration|\PHPUnit\Framework\MockObject\MockObject $configuration;

	public function setUp(): void
	{
		$this->helper = $this->getMockBuilder( Helper::class )
		                     ->disableOriginalConstructor()
		                     ->getMock();

		$this->configuration = $this->getMockBuilder( Configuration::class )
		                            ->disableOriginalConstructor()
		                            ->getMock();
	}
}
