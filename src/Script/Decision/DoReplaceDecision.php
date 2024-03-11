<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Composer\IO\IOInterface;
use Hansel23\Tooly\Model\Tool;
use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;

class DoReplaceDecision extends AbstractDecision
{
	private IOInterface $io;

	public function __construct( Configuration $configuration, Helper $helper, IOInterface $io )
	{
		$this->io = $io;

		parent::__construct( $configuration, $helper );
	}

	public function canProceed( Tool $tool ): bool
	{
		if ( false === $this->helper->getFilesystem()->isFileAlreadyExist( $tool->getFilename() ) )
		{
			return true;
		}

		$doReplace = $tool->forceReplace();

		if ( true === $this->configuration->isInteractiveMode() )
		{
			$this->io->write( '<comment>Checksums are not equal!</comment>' );
			$this->io->write(
				sprintf(
					'<comment>Do you want to overwrite the existing file "%s"?</comment>',
					$tool->getName()
				)
			);

			$doReplace = $this->io->askConfirmation( '<question>[yes] or [no]?</question>', false );
		}

		return $doReplace;
	}

	public function getReason(): string
	{
		return '<info>No replace selected. Skipped.</info>';
	}
}
