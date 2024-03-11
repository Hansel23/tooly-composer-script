<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script;

use Composer\Composer;
use Composer\Config;
use Composer\Package\Package;
use Composer\Package\RootPackage;
use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Mode;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
	public function testIfNoToolsSetEmptyToolSetIsGiven(): void
	{
		$composer      = $this->getPreparedComposerInstance( [], '' );
		$configuration = new Configuration( $composer, new Mode );

		$this->assertCount( 0, $configuration->getTools() );
	}

	public function testCanGetCorrectToolSet(): void
	{
		$extra = [
			'tools' => [
				'foo' => [
					'url' => 'foo',
				],
			],
		];

		$composer      = $this->getPreparedComposerInstance( $extra, '' );
		$configuration = new Configuration( $composer, new Mode );

		$this->assertCount( 1, $configuration->getTools() );
	}

	public function testCanCheckDevMode(): void
	{
		$composer      = $this->getPreparedComposerInstance( [], '' );
		$configuration = new Configuration( $composer, new Mode );

		$this->assertTrue( $configuration->isDevMode() );
	}

	public function testCanSetDevMode(): void
	{
		$mode = new Mode;
		$mode->setNoDev();

		$composer      = $this->getPreparedComposerInstance( [], '' );
		$configuration = new Configuration( $composer, $mode );

		$this->assertFalse( $configuration->isDevMode() );
	}

	public function testCanCheckInteractiveMode(): void
	{
		$composer      = $this->getPreparedComposerInstance( [], '' );
		$configuration = new Configuration( $composer, new Mode );

		$this->assertTrue( $configuration->isInteractiveMode() );
	}

	public function testCanSetInteractiveMode(): void
	{
		$mode = new Mode;
		$mode->setNonInteractive();

		$composer      = $this->getPreparedComposerInstance( [], '' );
		$configuration = new Configuration( $composer, $mode );

		$this->assertFalse( $configuration->isInteractiveMode() );
	}

	public function testCanGetCorrectComposerBinDirectory(): void
	{
		$binDir = __DIR__ . '/../../vendor/bin';

		$composer      = $this->getPreparedComposerInstance( [], $binDir );
		$configuration = new Configuration( $composer, new Mode );

		$this->assertEquals( $binDir, $configuration->getComposerBinDirectory() );
	}

	public function testCanGetCorrectBinDir(): void
	{
		$composer      = $this->getPreparedComposerInstance( [], '' );
		$configuration = new Configuration( $composer, new Mode );

		$this->assertEquals( realpath( __DIR__ . '/../../bin' ), $configuration->getBinDirectory() );
	}

	/**
	 * @param mixed $extra
	 * @param mixed $binDir
	 *
	 * @return Composer
	 */
	private function getPreparedComposerInstance( $extra, $binDir ): Composer
	{
		$package = $this
			->getMockBuilder( RootPackage::class )
			->disableOriginalConstructor()
			->getMock();

		$package
			->expects( $this->once() )
			->method( 'getExtra' )
			->willReturn( $extra );

		$configuration = $this
			->getMockBuilder( Config::class )
			->disableOriginalConstructor()
			->getMock();

		$configuration
			->expects( $this->once() )
			->method( 'get' )
			->with( $this->equalTo( 'bin-dir' ) )
			->willReturn( $binDir );

		$composer = $this
			->getMockBuilder( Composer::class )
			->disableOriginalConstructor()
			->getMock();

		$composer
			->expects( $this->once() )
			->method( 'getPackage' )
			->willReturn( $package );

		$composer
			->expects( $this->once() )
			->method( 'getConfig' )
			->willReturn( $configuration );

		return $composer;
	}
}
