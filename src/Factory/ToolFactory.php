<?php declare(strict_types=1);

namespace Hansel23\Tooly\Factory;

use Hansel23\Tooly\Model\Tool;

class ToolFactory
{
	public static function createTool( string $name, string $directory, array $parameters ): Tool
	{
		$defaults = [
			'url'           => '',
			'sign-url'      => null,
			'only-dev'      => true,
			'force-replace' => false,
			'rename'        => false,
			'fallback-url'  => null,
		];

		$parameters = array_merge( $defaults, $parameters );

		$tool = new Tool(
			$name,
			self::getFilename( $name, $directory ),
			$parameters['url'],
			$parameters['sign-url']
		);

		if ( true === $parameters['force-replace'] )
		{
			$tool->activateForceReplace();
		}

		if ( false === $parameters['only-dev'] )
		{
			$tool->disableOnlyDev();
		}

		if ( true === $parameters['rename'] )
		{
			$tool->setNameToToolKey();
		}

		if ( null !== $parameters['fallback-url'] )
		{
			$tool->setFallbackUrl( $parameters['fallback-url'] );
		}

		return $tool;
	}

	public static function createTools( string $directory, array $data ): array
	{
		$tools = [];

		foreach ( $data as $name => $parameters )
		{
			$tools[ $name ] = self::createTool( $name, $directory, $parameters );
		}

		return $tools;
	}

	private static function getFilename( string $name, string $directory ): string
	{
		$filename = rtrim( $directory, DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR;
		$filename .= str_replace( '.phar', '', $name ) . '.phar';

		return $filename;
	}
}
