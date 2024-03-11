<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Decision;

use Composer\IO\ConsoleIO;
use Hansel23\Tooly\Factory\ToolFactory;
use Hansel23\Tooly\Script\Decision\DoReplaceDecision;
use Hansel23\Tooly\Script\Helper\Filesystem;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;

class DoReplaceDecisionTest extends DecisionTestCase
{
	private ConsoleIO $io;

	private HelperSet $helperSet;

	public function setUp(): void
	{
		parent::setUp();

		$input           = new ArrayInput( [] );
		$output          = new StreamOutput( fopen( 'php://memory', 'wb' ) );
		$this->helperSet = new HelperSet;

		$this->io = new ConsoleIO( $input, $output, $this->helperSet );
	}

	public function testIfFileNotExistReturnsTrue(): void
	{
		$filesystem = $this
			->getMockBuilder( Filesystem::class )
			->disableOriginalConstructor()
			->getMock();

		$filesystem
			->expects( $this->once() )
			->method( 'isFileAlreadyExist' )
			->willReturn( false );

		$this->helper
			->expects( $this->once() )
			->method( 'getFilesystem' )
			->willReturn( $filesystem );

		$decision = new DoReplaceDecision( $this->configuration, $this->helper, $this->io );
		$tool     = ToolFactory::createTool( 'tool', __DIR__, [] );

		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testIfFileExistReturnsForceReplaceValue(): void
	{
		$filesystem = $this
			->getMockBuilder( Filesystem::class )
			->disableOriginalConstructor()
			->getMock();

		$filesystem
			->expects( $this->exactly( 2 ) )
			->method( 'isFileAlreadyExist' )
			->willReturn( true );

		$this->helper
			->expects( $this->exactly( 2 ) )
			->method( 'getFilesystem' )
			->willReturn( $filesystem );

		$decision = new DoReplaceDecision( $this->configuration, $this->helper, $this->io );

		$tool = ToolFactory::createTool( 'tool', __DIR__, [] );
		$this->assertFalse( $decision->canProceed( $tool ) );

		$tool->activateForceReplace();
		$this->assertTrue( $decision->canProceed( $tool ) );
	}

	public function testCanGetReason(): void
	{
		$decision = new DoReplaceDecision( $this->configuration, $this->helper, $this->io );
		$this->assertMatchesRegularExpression( '/info/', $decision->getReason() );
	}
}
