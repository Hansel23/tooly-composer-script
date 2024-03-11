<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Decision;

use Hansel23\Tooly\Model\Tool;
use Hansel23\Tooly\Script\Decision\OnlyDevDecision;

class OnlyDevDecisionTest extends DecisionTestCase
{
	public function testOnlyDevToolInNonDevModeReturnsFalse(): void
	{
		$helper        = clone $this->helper;
		$configuration = clone $this->configuration;

		$configuration
			->expects( $this->once() )
			->method( 'isDevMode' )
			->willReturn( false );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->expects( $this->once() )
			->method( 'isOnlyDev' )
			->willReturn( true );

		$decision = new OnlyDevDecision( $configuration, $helper );
		$this->assertFalse( $decision->canProceed( $tool ) );
	}

	public function testNonOnlyDevToolInNonDevModeReturnsTrue(): void
	{
		$helper        = clone $this->helper;
		$configuration = clone $this->configuration;

		$configuration
			->expects( $this->once() )
			->method( 'isDevMode' )
			->willReturn( false );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->expects( $this->once() )
			->method( 'isOnlyDev' )
			->willReturn( false );

		$decision = new OnlyDevDecision( $configuration, $helper );
		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testNonOnlyDevToolInDevModeReturnsTrue(): void
	{
		$helper        = clone $this->helper;
		$configuration = clone $this->configuration;

		$configuration
			->expects( $this->once() )
			->method( 'isDevMode' )
			->willReturn( true );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->expects( $this->never() )
			->method( 'isOnlyDev' );

		$decision = new OnlyDevDecision( $configuration, $helper );
		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testCanGetReason(): void
	{
		$decision = new OnlyDevDecision( $this->configuration, $this->helper );
		$this->assertMatchesRegularExpression( '/comment/', $decision->getReason() );
	}
}
