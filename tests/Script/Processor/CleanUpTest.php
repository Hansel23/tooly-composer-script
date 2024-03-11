<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Processor;

use Composer\IO\ConsoleIO;
use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;
use Hansel23\Tooly\Script\Processor;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

class CleanUpTest extends TestCase
{
	private Configuration|\PHPUnit\Framework\MockObject\MockObject $configuration;

	private Helper|\PHPUnit\Framework\MockObject\MockObject        $helper;

	private \org\bovigo\vfs\vfsStreamDirectory                     $root;

	public function setUp(): void
	{
		$this->root = vfsStream::setup();

		mkdir( vfsStream::url( 'root/vendor/bin' ), 0777, true );
		mkdir( vfsStream::url( 'root/bin' ) );

		$this->configuration = $this
			->getMockBuilder( Configuration::class )
			->disableOriginalConstructor()
			->getMock();

		$this->configuration
			->method( 'getComposerBinDirectory' )
			->willReturn( vfsStream::url( 'root/vendor/bin' ) );

		$this->configuration
			->method( 'getBinDirectory' )
			->willReturn( vfsStream::url( 'root/bin' ) );

		$this->helper = $this
			->getMockBuilder( Helper::class )
			->disableOriginalConstructor()
			->getMock();
	}

	public function testEmptyDirectoryDoNothing(): void
	{
		$this->configuration
			->method( 'getTools' )
			->willReturn( [] );

		$this->helper
			->expects( $this->never() )
			->method( 'getFilesystem' );

		$processor = $this->getProcessor();
		$processor->cleanUp();
	}

	public function testPharFileWasRemoved(): void
	{
		$this->root
			->getChild( 'bin' )
			->addChild( vfsStream::newFile( 'tool.phar' ) );

		$this->configuration
			->method( 'getTools' )
			->willReturn( [] );

		$this->helper
			->expects( $this->never() )
			->method( 'getFilesystem' );

		$processor = $this->getProcessor();
		$processor->cleanUp();
	}

	private function getProcessor(): Processor
	{
		return $this
			->getMockBuilder( Processor::class )
			->setConstructorArgs( [
				$this->getMockBuilder( ConsoleIO::class )
				     ->disableOriginalConstructor()
				     ->getMock(),
				$this->helper,
				$this->configuration,
			] )
			->onlyMethods( [ 'process', 'symlinkOrCopy' ] )
			->getMock();
	}
}
