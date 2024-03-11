<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Helper;

use Composer\Util\Filesystem as ComposerFileSystem;
use Composer\Util\Silencer;

class Filesystem
{
	private ComposerFileSystem $filesystem;

	public function __construct( ?ComposerFileSystem $filesystem = null )
	{
		$this->filesystem = $filesystem ? : new ComposerFileSystem();
	}

	public function isFileAlreadyExist( string $filename ): bool
	{
		return file_exists( $filename );
	}

	public function createFile( string $filename, string $content ): bool
	{
		if ( false === $this->createDirectory( dirname( $filename ) ) )
		{
			return false;
		}

		Silencer::call( 'file_put_contents', $filename, $content );
		Silencer::call( 'chmod', $filename, 0755 );

		return true;
	}

	public function symlinkFile( string $sourceFile, string $file ): bool
	{
		if ( false === $this->createDirectory( dirname( $file ) ) )
		{
			return false;
		}

		if ( true === $this->isFileAlreadyExist( $file ) )
		{
			return true;
		}

		return $this->filesystem->relativeSymlink( $sourceFile, $file );
	}

	public function copyFile( string $sourceFile, string $file ): bool
	{
		if ( !$this->createDirectory( dirname( $file ) ) )
		{
			return false;
		}

		if ( $this->isFileAlreadyExist( $file ) )
		{
			return true;
		}

		return Silencer::call(
			'copy',
			$this->filesystem->normalizePath( $sourceFile ),
			$this->filesystem->normalizePath( $file )
		);
	}

	public function removeDirectory( string $directory ): bool
	{
		return $this->filesystem->removeDirectoryPhp( $directory );
	}

	public function remove( string $file ): bool
	{
		return Silencer::call( 'unlink', $file );
	}

	public function createDirectory( string $directory ): bool
	{
		if ( true === is_dir( $directory ) )
		{
			return true;
		}

		return Silencer::call( 'mkdir', $directory, 0777, true );
	}
}
