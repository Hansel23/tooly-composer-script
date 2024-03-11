<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Hansel23\Tooly\Model\Tool;

class OnlyDevDecision extends AbstractDecision
{
	public function canProceed( Tool $tool ): bool
	{
		return !(false === $this->configuration->isDevMode() && true === $tool->isOnlyDev());
	}

	public function getReason(): string
	{
		return '<comment>... skipped! Only installed in Dev mode.</comment>';
	}
}
