<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Hansel23\Tooly\Model\Tool;

class FileAlreadyExistDecision extends AbstractDecision
{
	public function canProceed( Tool $tool ): bool
	{
		$url = $tool->getUrl();

		if ( false === $this->helper->getDownloader()->isAccessible( $url ) )
		{
			$url = $tool->getFallbackUrl();
		}

		return false === $this->helper->isFileAlreadyExist( $tool->getFilename(), $url );
	}

	public function getReason(): string
	{
		return '<info>File already exists in the given version.</info>';
	}
}
