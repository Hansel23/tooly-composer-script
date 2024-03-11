<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Hansel23\Tooly\Model\Tool;

interface DecisionInterface
{
	public function canProceed( Tool $tool ): bool;

	public function getReason(): string;
}
