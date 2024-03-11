<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Decision;

use Hansel23\Tooly\Model\Tool;
use Hansel23\Tooly\Script\Decision\IsVerifiedDecision;

class IsVerifiedDecisionTest extends DecisionTestCase
{
	public function testEmptySignUrlReturnsTrue(): void
	{
		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->expects( $this->once() )
			->method( 'getSignUrl' )
			->willReturn( null );

		$decision = new IsVerifiedDecision( $this->configuration, $this->helper );
		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testVerificationReturnsBool(): void
	{
		$helper = $this->helper;

		$helper
			->method( 'isVerified' )
			->willReturn( false );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->method( 'getUrl' )
			->willReturn( 'foo' );

		$tool
			->method( 'getSignUrl' )
			->willReturn( 'bar' );

		$decision = new IsVerifiedDecision( $this->configuration, $helper );

		$this->assertFalse( $decision->canProceed( $tool ) );
	}

	public function testCanGetReason(): void
	{
		$decision = new IsVerifiedDecision( $this->configuration, $this->helper );
		$this->assertMatchesRegularExpression( '/error/', $decision->getReason() );
	}
}
