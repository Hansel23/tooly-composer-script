<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script\Decision;

use Hansel23\Tooly\Model\Tool;

class IsVerifiedDecision extends AbstractDecision
{
	public function canProceed( Tool $tool ): bool
	{
		if ( null === $tool->getSignUrl() )
		{
			return true;
		}

		return $this->helper->isVerified( $tool->getSignUrl(), $tool->getUrl() );
	}

	public function getReason(): string
	{
		return '<error>
				Verification failed! Please download the files manually and run the command 
				$ gpg --verify --status-fd 1 /path/to/tool.phar.sign /path/to/tool.phar
				to get more details. In most cases you need to add the public key of the file author.
				So please take a look at the documentation on 
				> https://www.gnupg.org/gph/en/manual/book1.html
				</error>';
	}
}
