<?php declare(strict_types=1);

namespace Hansel23\Tooly\Script;

class Mode
{
	private bool $isDev         = true;

	private bool $isInteractive = true;

	/**
	 * Set flag for composer dev-mode to false.
	 */
	public function setNoDev(): void
	{
		$this->isDev = false;
	}

	/**
	 * Set flag for CLI interaction to false.
	 */
	public function setNonInteractive(): void
	{
		$this->isInteractive = false;
	}

	/**
	 * Returns if composer runs in dev-mode.
	 *
	 * @return bool
	 */
	public function isDev(): bool
	{
		return $this->isDev;
	}

	/**
	 * Returns if the CLI can interact.
	 *
	 * @return bool
	 */
	public function isInteractive(): bool
	{
		return $this->isInteractive;
	}
}
