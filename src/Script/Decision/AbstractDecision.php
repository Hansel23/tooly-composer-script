<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Hansel23\Tooly\Script\Configuration;
use Hansel23\Tooly\Script\Helper;

abstract class AbstractDecision implements DecisionInterface
{
	protected Configuration $configuration;

	protected Helper        $helper;

	public function __construct( Configuration $configuration, Helper $helper )
	{
		$this->configuration = $configuration;
		$this->helper        = $helper;
	}
}
