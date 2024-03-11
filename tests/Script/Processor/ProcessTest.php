<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Processor;

use Composer\IO\ConsoleIO;
use Hansel23\Tooly\Factory\ToolFactory;
use Hansel23\Tooly\Model\Tool;
use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;
use Hansel23\Tooly\Script\Helper\Downloader;
use Hansel23\Tooly\Script\Helper\Filesystem;
use Hansel23\Tooly\Script\Processor;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class ProcessTest extends TestCase
{
	private \PHPUnit\Framework\MockObject\MockObject|ConsoleIO     $io;

	private Helper|\PHPUnit\Framework\MockObject\MockObject        $helper;

	private Configuration|\PHPUnit\Framework\MockObject\MockObject $configuration;

	public function setUp(): void
	{
		$this->io = $this
			->getMockBuilder( ConsoleIO::class )
			->disableOriginalConstructor()
			->getMock();

		$this->helper = $this
			->getMockBuilder( Helper::class )
			->disableOriginalConstructor()
			->getMock();

		$this->configuration = $this
			->getMockBuilder( Configuration::class )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testCantProceedOnlyDevToolInNonDevMode(): void
	{
		$tool = ToolFactory::createTool( 'tool', __DIR__, [] );

		$this->configuration
			->method( 'isDevMode' )
			->willReturn( false );

		$this->io
			->expects( $this->exactly( 2 ) )
			->method( 'write' );

		$processor = new Processor( $this->io, $this->helper, $this->configuration );
		$processor->process( $tool );
	}

	public function testCantProceedToolWithUnAccessibleUrl(): void
	{
		$tool = ToolFactory::createTool( 'tool', __DIR__, [ 'url' => '' ] );

		$this->helper
			->method( 'getDownloader' )
			->willReturn( new Downloader );

		$this->io
			->expects( $this->exactly( 2 ) )
			->method( 'write' );

		$processor = new Processor( $this->io, $this->helper, $this->configuration );
		$processor->process( $tool );
	}

	public function testCanSuccessfullyDownloadATool(): void
	{
		vfsStream::setup( 'bin' );

		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->method( 'isAccessible' )
			->willReturn( true );

		$filesystem = $this
			->getMockBuilder( Filesystem::class )
			->getMock();

		$filesystem
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$this->helper
			->method( 'getFilesystem' )
			->willReturn( $filesystem );

		$this->helper
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$this->helper
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$this->io
			->expects( $this->exactly( 2 ) )
			->method( 'write' );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->method( 'getFilename' )
			->willReturn( vfsStream::url( 'bin/tool.phar' ) );

		$processor = new Processor( $this->io, $this->helper, $this->configuration );
		$processor->process( $tool );
	}

	public function testCanSuccessfullyDownloadAToolViaFallbackUrl(): void
	{
		vfsStream::setup( 'bin' );

		$downloader = $this
			->getMockBuilder( Downloader::class )
			->getMock();

		$downloader
			->expects( $this->exactly( 4 ) )
			->method( 'isAccessible' )
			->willReturn( false, true, false, false );

		$filesystem = $this
			->getMockBuilder( Filesystem::class )
			->getMock();

		$filesystem
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$this->helper
			->method( 'getFilesystem' )
			->willReturn( $filesystem );

		$this->helper
			->expects( $this->exactly( 5 ) )
			->method( 'getDownloader' )
			->willReturn( $downloader );

		$this->helper
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$this->io
			->expects( $this->exactly( 2 ) )
			->method( 'write' );

		$tool = $this
			->getMockBuilder( Tool::class )
			->disableOriginalConstructor()
			->getMock();

		$tool
			->method( 'getFallbackUrl' )
			->willReturn( '//test.html' );

		$processor = new Processor( $this->io, $this->helper, $this->configuration );
		$processor->process( $tool );
	}
}
