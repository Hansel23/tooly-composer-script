<?php declare(strict_types=1);

namespace Hansel23\Tooly\Tests\Script\Helper;

use Hansel23\Tooly\Script\Helper\Downloader;
use PHPUnit\Framework\TestCase;

class DownloaderTest extends TestCase
{
	public function testAccessibleTestWorksCorrect(): void
	{
		$downloader = new Downloader;

		$this->assertFalse( $downloader->isAccessible( 'foo' ) );
		$this->assertTrue( $downloader->isAccessible( 'https://google.com' ) );
	}

	public function testCanDownloadContentFromUrl(): void
	{
		$downloader = new Downloader;

		$this->assertMatchesRegularExpression(
			'/google/',
			$downloader->download( 'https://google.com' )
		);
	}
}
