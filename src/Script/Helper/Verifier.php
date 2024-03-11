<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Helper;

use TM\GPG\Verification\Exception\VerificationException;
use TM\GPG\Verification\Verifier as GPGVerifier;

class Verifier
{
	private ?GPGVerifier $gpgVerifier;

	public function __construct( ?GPGVerifier $gpgVerifier = null )
	{
		$this->gpgVerifier = $gpgVerifier;
	}

	public function checkFileSum( string $targetFilename, string $filename ): bool
	{
		if ( !file_exists( $targetFilename ) )
		{
			return false;
		}

		return sha1_file( $targetFilename ) === sha1_file( $filename );
	}

	public function checkGPGSignature( string $signatureFile, string $file ): bool
	{
		if ( !$this->gpgVerifier instanceof GPGVerifier )
		{
			return true;
		}

		try
		{
			$this->gpgVerifier->verify( $signatureFile, $file );

			return true;
		}
		catch ( VerificationException $exception )
		{
			return false;
		}
	}
}
