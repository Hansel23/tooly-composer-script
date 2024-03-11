<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Hansel23\Tooly\Model\Tool;

class IsAccessibleDecision extends AbstractDecision
{
	public function canProceed( Tool $tool ): bool
	{
		if ( false === $this->helper->getDownloader()->isAccessible( $tool->getUrl() ) )
		{
			return $this->fallbackUrlIsAccessible( $tool );
		}

		if ( empty( $tool->getSignUrl() ) )
		{
			return true;
		}

		if ( false === $this->helper->getDownloader()->isAccessible( $tool->getSignUrl() ) )
		{
			return false;
		}

		return true;
	}

	public function getReason(): string
	{
		return '<error>At least one given URL are not accessible!</error>';
	}

	private function fallbackUrlIsAccessible( Tool $tool ): bool
	{
		$fallbackUrl = $tool->getFallbackUrl();

		return false === empty( $fallbackUrl ) && true === $this->helper->getDownloader()->isAccessible( $fallbackUrl );
	}
}
