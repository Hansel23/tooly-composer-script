<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Helper;

use Hansel23\Tooly\Script\Helper\Verifier;
use PHPUnit\Framework\TestCase;
use TM\GPG\Verification\Exception\VerificationException;
use TM\GPG\Verification\Verifier as GPGVerifier;

class VerifierTest extends TestCase
{
	public function testCanCheckIfFileSumsAreEqual(): void
	{
		$verifier = new Verifier;
		$this->assertTrue(
			$verifier->checkFileSum(
				__DIR__ . '/../../../resources/phpstorm-setting.png',
				__DIR__ . '/../../../resources/phpstorm-setting.png'
			)
		);
	}

	public function testNotExistTargetFileReturnsFalse(): void
	{
		$verifier = new Verifier;
		$this->assertFalse(
			$verifier->checkFileSum(
				__DIR__ . '/../../../resources/foo',
				__DIR__ . '/../../../resources/phpstorm-setting.png'
			)
		);
	}

	public function testIfNoVerifierGivenSignatureCheckReturnsTrue(): void
	{
		$verifier = new Verifier;
		$this->assertTrue( $verifier->checkGPGSignature( 'foo.sign', 'foo' ) );
	}
}
