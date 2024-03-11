<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script;

use Composer\IO\IOInterface;
use Composer\Util\Platform;
use Hansel23\Tooly\Model\Tool;
use Hansel23\Tooly\Script\Decision\DoReplaceDecision;
use Hansel23\Tooly\Script\Decision\FileAlreadyExistDecision;
use Hansel23\Tooly\Script\Decision\IsAccessibleDecision;
use Hansel23\Tooly\Script\Decision\IsVerifiedDecision;
use Hansel23\Tooly\Script\Decision\OnlyDevDecision;

class Processor
{
	private IOInterface   $io;

	private Helper        $helper;

	private Configuration $configuration;

	public function __construct( IOInterface $io, Helper $helper, Configuration $configuration )
	{
		$this->io            = $io;
		$this->helper        = $helper;
		$this->configuration = $configuration;
	}

	/**
	 * Removes symlinks from composer's bin-dir and old phar's
	 * from own bin-dir.
	 */
	public function cleanUp(): void
	{
		$composerBinDirectory = $this->configuration->getComposerBinDirectory();

		if ( false === is_dir( $composerBinDirectory ) )
		{
			$this->helper->getFilesystem()->createDirectory( $composerBinDirectory );
		}

		$this->removeFromDir( $composerBinDirectory );
		$this->removeFromDir(
			$this->configuration->getBinDirectory(),
			array_keys( $this->configuration->getTools() )
		);
	}

	public function process( Tool $tool ): void
	{
		$this->io->write( sprintf( '<comment>Process tool "%s" ...</comment>', $tool->getName() ) );

		/* @var $decision \Hansel23\Tooly\Script\Decision\DecisionInterface */
		foreach ( $this->getDecisions() as $decision )
		{
			if ( true === $decision->canProceed( $tool ) )
			{
				continue;
			}

			$this->io->write( $decision->getReason() );

			return;
		}

		$data     = $this->helper->getDownloader()->download( $this->getDownloadUrl( $tool ) );
		$filename = $tool->getFilename();

		$this->helper->getFilesystem()->createFile( $filename, $data );

		$this->io->write(
			sprintf(
				'<info>File "%s" successfully downloaded!</info>',
				basename( $filename )
			)
		);
	}

	public function symlinkOrCopy( Tool $tool ): void
	{
		if ( true === $tool->isOnlyDev() && false === $this->configuration->isDevMode() )
		{
			return;
		}

		$filename = $tool->getFilename();
		if ( $tool->renameToConfigKey() )
		{
			$filename = $tool->getName();
		}
		$composerDir  = $this->configuration->getComposerBinDirectory();
		$composerPath = $composerDir . DIRECTORY_SEPARATOR . basename( $filename );

		if ( Platform::isWindows() )
		{
			$this->helper->getFilesystem()->copyFile( $filename, $composerPath );
		}
		else
		{
			$this->helper->getFilesystem()->symlinkFile( $filename, $composerPath );
		}
	}

	/**
	 * Each decision can interrupt the download of a tool.
	 *
	 * @return array
	 */
	private function getDecisions(): array
	{
		return [
			new OnlyDevDecision( $this->configuration, $this->helper ),
			new IsAccessibleDecision( $this->configuration, $this->helper ),
			new FileAlreadyExistDecision( $this->configuration, $this->helper ),
			new IsVerifiedDecision( $this->configuration, $this->helper ),
			new DoReplaceDecision( $this->configuration, $this->helper, $this->io ),
		];
	}

	private function removeFromDir( string $dir, array $excludeToolNames = [] ): void
	{
		// Get the tools managed by Hansel23\Tooly
		$tools = $this->configuration->getTools();

		// If no tools exist, there is nothing more to do here.
		if ( 0 === count( $tools ) )
		{
			return;
		}

		foreach ( scandir( $dir ) as $entry )
		{
			$path = $dir . DIRECTORY_SEPARATOR . $entry;

			if ( !str_contains( $path, '.phar' ) )
			{
				continue;
			}

			/* @var $tool Tool */
			foreach ( $tools as $tool )
			{
				// Check if the binary is a managed one by Hansel23\Tooly, if not - don't remove it
				if ( basename( $tool->getFilename() ) !== basename( $path ) )
				{
					continue 2;
				}
			}

			if ( true === in_array( basename( $entry, '.phar' ), $excludeToolNames, true ) )
			{
				continue;
			}

			$this->helper->getFilesystem()->remove( $path );
		}
	}

	private function getDownloadUrl( Tool $tool ): string
	{
		if ( false === $this->helper->getDownloader()->isAccessible( $tool->getUrl() ) )
		{
			return $tool->getFallbackUrl();
		}

		return $tool->getUrl();
	}
}
