<?php declare(strict_types=1);

namespace Hansel23\Tooly;

use Composer\Script\Event;
use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;
use Hansel23\Tooly\Script\Helper\Downloader;
use Hansel23\Tooly\Script\Helper\Filesystem;
use Hansel23\Tooly\Script\Helper\Verifier;
use Hansel23\Tooly\Script\Mode;
use Hansel23\Tooly\Script\Processor;
use TM\GPG\Verification\Verifier as GPGVerifier;

class ScriptHandler
{
	public static function installPharTools( Event $event ): void
	{
		$gpgVerifier = null;
		$mode        = new Mode;

		if ( false === $event->isDevMode() )
		{
			$mode->setNoDev();
		}

		if ( false === $event->getIO()->isInteractive() )
		{
			$mode->setNonInteractive();
		}

		$configuration = new Configuration( $event->getComposer(), $mode );

		if ( true === class_exists( GPGVerifier::class ) )
		{
			$gpgVerifier = new GPGVerifier;
		}

		$helper    = new Helper( new Filesystem, new Downloader, new Verifier( $gpgVerifier ) );
		$processor = new Processor( $event->getIO(), $helper, $configuration );

		$processor->cleanUp();

		foreach ( $configuration->getTools() as $tool )
		{
			$processor->process( $tool );
			$processor->symlinkOrCopy( $tool );
		}
	}
}
