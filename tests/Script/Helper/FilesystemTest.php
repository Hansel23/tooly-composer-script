<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Helper;

use Composer\Util\Platform;
use Hansel23\Tooly\Script\Helper\Filesystem;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
	private Filesystem $filesystem;

	private string     $testDirectory;

	private string     $testFile;

	public function setUp(): void
	{
		$this->filesystem    = new Filesystem;
		$this->testDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test';
		$this->testFile      = $this->testDirectory . DIRECTORY_SEPARATOR . 'file';
	}

	public function tearDown(): void
	{
		if ( is_dir( $this->testDirectory ) )
		{
			$this->filesystem->removeDirectory( $this->testDirectory );
		}
	}

	public function testCanRelativeSymlinkAFile(): void
	{
		if ( Platform::isWindows() )
		{
			$this->markTestSkipped( 'Symlink not possible on Windows.' );
		}

		$symlink = $this->testDirectory . DIRECTORY_SEPARATOR . '/foo/symlinkOrCopy';

		$this->assertTrue( $this->filesystem->symlinkFile( $this->testFile, $symlink ) );
		$this->assertNotEquals( '/', substr( readlink( $symlink ), 0, 1 ) );
	}

	public function testCanCopyAFile(): void
	{
		$copy = $this->testDirectory . DIRECTORY_SEPARATOR . 'copy';

		$this->assertTrue( $this->filesystem->createFile( $this->testFile, '' ) );
		$this->assertTrue( $this->filesystem->copyFile( $this->testFile, $copy ) );
	}
}
