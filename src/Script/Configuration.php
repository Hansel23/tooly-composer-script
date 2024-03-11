<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script;

use Composer\Composer;
use Hansel23\Tooly\Factory\ToolFactory;

class Configuration
{
	private array  $data = [];

	private string $binDirectory;

	private string $composerBinDirectory;

	private Mode   $mode;

	public function __construct( Composer $composer, Mode $mode )
	{
		$extras = $composer->getPackage()->getExtra();

		if ( true === array_key_exists( 'tools', $extras ) )
		{
			$this->data = array_merge( [], $extras['tools'] );
		}

		$this->mode                 = $mode;
		$this->binDirectory         = dirname( __DIR__, 2 ) . '/bin';
		$this->composerBinDirectory = $composer->getConfig()->get( 'bin-dir' );
	}

	public function isDevMode(): bool
	{
		return $this->mode->isDev();
	}

	public function isInteractiveMode(): bool
	{
		return $this->mode->isInteractive();
	}

	public function getBinDirectory(): string
	{
		return $this->binDirectory;
	}

	public function getComposerBinDirectory(): string
	{
		return $this->composerBinDirectory;
	}

	public function getTools(): array
	{
		return ToolFactory::createTools( $this->binDirectory, $this->data );
	}
}
