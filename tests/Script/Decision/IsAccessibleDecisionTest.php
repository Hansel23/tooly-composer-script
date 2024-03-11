<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Decision;

use Hansel23\Tooly\Factory\ToolFactory;
use Hansel23\Tooly\Script\Decision\IsAccessibleDecision;
use Hansel23\Tooly\Script\Helper\Downloader;

class IsAccessibleDecisionTest extends DecisionTestCase
{
	public function testNotAccessibleToolUrlReturnsFalse(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->expects( $this->once() )
			->method( 'isAccessible' )
			->willReturn( false );

		$this->helper
			->expects( $this->once() )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$decision = new IsAccessibleDecision( $this->configuration, $this->helper );
		$this->assertFalse( $decision->canProceed( ToolFactory::createTool( 'tool', __DIR__, [ 'url' => '' ] ) ) );
	}

	public function testEmptySignUrlReturnsTrue(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->expects( $this->once() )
			->method( 'isAccessible' )
			->willReturn( true );

		$this->helper
			->expects( $this->once() )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$decision = new IsAccessibleDecision( $this->configuration, $this->helper );
		$this->assertTrue( $decision->canProceed( ToolFactory::createTool( 'tool', __DIR__, [ 'url' => '' ] ) ) );
	}

	public function testNotAccessibleToolSignUrlReturnsFalse(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->expects( $this->exactly( 2 ) )
			->method( 'isAccessible' )
			->willReturn( true, false );

		$this->helper
			->expects( $this->exactly( 2 ) )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$decision = new IsAccessibleDecision( $this->configuration, $this->helper );
		$this->assertFalse( $decision->canProceed( ToolFactory::createTool( 'tool', __DIR__, [
			'sign-url' => 'sign-url',
			'url'      => '',
		] ) ) );
	}

	public function testNotAccessibleToolUrlButAccessibleFallbackUrlReturnsTrue(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->expects( $this->exactly( 2 ) )
			->method( 'isAccessible' )
			->willReturn( false, true );

		$this->helper
			->expects( $this->exactly( 2 ) )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$decision = new IsAccessibleDecision( $this->configuration, $this->helper );
		$this->assertTrue( $decision->canProceed( ToolFactory::createTool( 'tool', __DIR__, [
			'fallback-url' => 'fallback-url',
			'url'          => '',
		] ) ) );
	}

	public function testAccessibleUrlsWillReturnTrue(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->expects( $this->exactly( 2 ) )
			->method( 'isAccessible' )
			->willReturn( true, true );

		$this->helper
			->expects( $this->exactly( 2 ) )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$decision = new IsAccessibleDecision( $this->configuration, $this->helper );
		$this->assertTrue( $decision->canProceed( ToolFactory::createTool( 'tool', __DIR__, [
			'sign-url' => 'sign-url',
			'url'      => '',
		] ) ) );
	}

	public function testCanGetReason(): void
	{
		$decision = new IsAccessibleDecision( $this->configuration, $this->helper );
		$this->assertMatchesRegularExpression( '/error/', $decision->getReason() );
	}
}
