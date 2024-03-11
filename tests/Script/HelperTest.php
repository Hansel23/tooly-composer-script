<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script;

use Hansel23\Tooly\Script\Helper;
use Hansel23\Tooly\Script\Helper\Downloader;
use Hansel23\Tooly\Script\Helper\Filesystem;
use Hansel23\Tooly\Script\Helper\Verifier;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
	public function testCanCheckIfFileAlreadyExist(): void
	{
		$filesystem = $this->getMockBuilder( Filesystem::class )
		                   ->onlyMethods( [ 'isFileAlreadyExist' ] )
		                   ->getMock();

		$filesystem
			->expects( $this->exactly( 2 ) )
			->method( 'isFileAlreadyExist' )
			->willReturnOnConsecutiveCalls( true, false );

		$verifier = $this
			->getMockBuilder( Verifier::class )
			->onlyMethods( [ 'checkFileSum' ] )
			->getMock();

		$verifier
			->expects( $this->exactly( 2 ) )
			->method( 'checkFileSum' )
			->willReturnOnConsecutiveCalls( true, false );

		$helper = new Helper( $filesystem, new Downloader, $verifier );

		$this->assertTrue( $helper->isFileAlreadyExist( 'foo', 'bar' ) );
		$this->assertFalse( $helper->isFileAlreadyExist( 'foo', 'bar' ) );
	}

	public function testCanSymlinkAFile(): void
	{
		$filesystem = $this->getMockBuilder( Filesystem::class )->getMock();

		$filesystem
			->expects( $this->once() )
			->method( 'symlinkFile' )
			->willReturn( true );

		$helper = new Helper( $filesystem, new Downloader, new Verifier );
		$this->assertTrue( $helper->getFilesystem()->symlinkFile( 'foo', 'bar' ) );
	}

	public function testCanGetDownloader(): void
	{
		$helper = new Helper( new Filesystem, new Downloader, new Verifier );
		$this->assertInstanceOf( Downloader::class, $helper->getDownloader() );
	}

	public function testCanGetVerifier(): void
	{
		$helper = new Helper( new Filesystem, new Downloader, new Verifier );
		$this->assertInstanceOf( Verifier::class, $helper->getVerifier() );
	}
}
