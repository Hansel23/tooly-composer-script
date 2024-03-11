<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Decision;

use Hansel23\Tooly\Model\Tool;
use Hansel23\Tooly\Script\Decision\FileAlreadyExistDecision;
use Hansel23\Tooly\Script\Helper\Downloader;

class FileAlreadyExistDecisionTest extends DecisionTestCase
{
	public function testIfFileIsAccessibleAndFileNotAlreadyExistReturnsTrue(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->disableOriginalConstructor()
			->getMock();

		$downloader
			->expects( $this->once() )
			->method( 'isAccessible' )
			->willReturn( true );

		$this->helper
			->expects( $this->once() )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$this->helper
			->expects( $this->once() )
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$decision = new FileAlreadyExistDecision( $this->configuration, $this->helper );
		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testIfFileNotAccessibleAndFileNotAlreadyExistReturnsTrue(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->disableOriginalConstructor()
			->getMock();

		$downloader
			->expects( $this->once() )
			->method( 'isAccessible' )
			->willReturn( false );

		$this->helper
			->expects( $this->once() )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$this->helper
			->expects( $this->once() )
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$decision = new FileAlreadyExistDecision( $this->configuration, $this->helper );
		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testIfFileIsAccessibleAndFileAlreadyExistReturnsFalse(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->disableOriginalConstructor()
			->getMock();

		$downloader
			->expects( $this->once() )
			->method( 'isAccessible' )
			->willReturn( true );

		$this->helper
			->expects( $this->once() )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$this->helper
			->expects( $this->once() )
			->method( 'isFileAlreadyExist' )
			->willReturn( true );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$decision = new FileAlreadyExistDecision( $this->configuration, $this->helper );
		$this->assertFalse( $decision->canProceed( $tool ) );
	}

	public function testIfFileNotAccessibleAndFileAlreadyExistReturnsFalse(): void
	{
		$downloader = $this
			->getMockBuilder( Downloader::class )
			->disableOriginalConstructor()
			->getMock();

		$downloader
			->expects( $this->once() )
			->method( 'isAccessible' )
			->willReturn( false );

		$this->helper
			->expects( $this->once() )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$this->helper
			->expects( $this->once() )
			->method( 'isFileAlreadyExist' )
			->willReturn( true );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$decision = new FileAlreadyExistDecision( $this->configuration, $this->helper );
		$this->assertFalse( $decision->canProceed( $tool ) );
	}

	public function testCanGetReason(): void
	{
		$decision = new FileAlreadyExistDecision( $this->configuration, $this->helper );
		$this->assertMatchesRegularExpression( '/info/', $decision->getReason() );
	}
}
