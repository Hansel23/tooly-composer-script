<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Helper;

use Composer\Util\StreamContextFactory;

class Downloader
{
	/**
	 * @param string $url
	 *
	 * @return bool
	 */
	public function isAccessible( string $url ): bool
	{
		$context = $this->getContext( $url );

		return is_resource( @fopen( $url, 'rb', false, $context ) );
	}

	public function download( string $url ): string
	{
		$context = $this->getContext( $url );

		return file_get_contents( $url, false, $context );
	}

	private function getContext( string $url )
	{
		return StreamContextFactory::getContext( $url, [
			'http' => [ 'timeout' => 5 ],
		] );
	}
}
