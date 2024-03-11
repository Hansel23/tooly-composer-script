<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Processor;

use Composer\IO\ConsoleIO;
use Composer\Util\Platform;
use Hansel23\Tooly\Factory\ToolFactory;
use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;
use Hansel23\Tooly\Script\Helper\Filesystem;
use Hansel23\Tooly\Script\Processor;
use PHPUnit\Framework\TestCase;

class SymlinkTest extends TestCase
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

	public function testNoSymlinkCreatedIfOnlyDevToolInNoDevMode(): void
	{
		$this->configuration
			->expects( $this->once() )
			->method( 'isDevMode' )
			->willReturn( false );

		$this->helper
			->expects( $this->never() )
			->method( 'getFilesystem' );

		$tool = ToolFactory::createTool( 'tool', __DIR__, [] );

		$processor = new Processor( $this->io, $this->helper, $this->configuration );
		$processor->symlinkOrCopy( $tool );
	}
}
