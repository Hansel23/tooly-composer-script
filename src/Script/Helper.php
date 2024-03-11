<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script;

use Hansel23\Tooly\Script\Helper\Downloader;
use Hansel23\Tooly\Script\Helper\Filesystem;
use Hansel23\Tooly\Script\Helper\Verifier;

class Helper
{
	private Filesystem $filesystem;

	private Downloader $downloader;

	private Verifier   $verifier;

	public function __construct( Filesystem $filesystem, Downloader $downloader, Verifier $verifier )
	{
		$this->filesystem = $filesystem;
		$this->downloader = $downloader;
		$this->verifier   = $verifier;
	}

	public function isFileAlreadyExist( string $filename, string $targetFile ): bool
	{
		$alreadyExist = $this->filesystem->isFileAlreadyExist( $filename );
		$verification = $this->verifier->checkFileSum( $filename, $targetFile );

		return true === $alreadyExist && true === $verification;
	}

	public function isVerified( string $signatureUrl, string $fileUrl ): bool
	{
		$data          = $this->downloader->download( $fileUrl );
		$signatureData = $this->downloader->download( $signatureUrl );

		$tmpFile = rtrim( sys_get_temp_dir(), '/' ) . '/_tool';
		$this->filesystem->createFile( $tmpFile, $data );

		$tmpSignFile = rtrim( sys_get_temp_dir(), '/' ) . '/_tool.sign';
		$this->filesystem->createFile( $tmpSignFile, $signatureData );

		$result = $this->verifier->checkGPGSignature( $tmpSignFile, $tmpFile );

		unlink( $tmpFile );
		unlink( $tmpSignFile );

		return $result;
	}

	public function getFilesystem(): Filesystem
	{
		return $this->filesystem;
	}

	public function getDownloader(): Downloader
	{
		return $this->downloader;
	}

	public function getVerifier(): Verifier
	{
		return $this->verifier;
	}
}
