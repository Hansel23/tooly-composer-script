<?php declare(strict_types=1);

namespace Hansel23\Tooly\Model;

class Tool
{
	private string  $name;

	private string  $filename;

	private string  $url;

	private ?string $signUrl;

	private bool    $forceReplace = false;

	private bool    $onlyDev      = true;

	private bool    $rename       = false;

	private string  $fallbackUrl  = '';

	public function __construct( string $name, string $filename, string $url, ?string $signUrl = null )
	{
		$this->name     = $name;
		$this->filename = $filename;
		$this->url      = $url;
		$this->signUrl  = $signUrl;
	}

	public function activateForceReplace(): void
	{
		$this->forceReplace = true;
	}

	public function disableOnlyDev(): void
	{
		$this->onlyDev = false;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getFilename(): string
	{
		return $this->filename;
	}

	public function getUrl(): string
	{
		return $this->url;
	}

	public function getSignUrl(): ?string
	{
		return $this->signUrl;
	}

	public function isOnlyDev(): bool
	{
		return $this->onlyDev;
	}

	public function forceReplace(): bool
	{
		return $this->forceReplace;
	}

	public function setNameToToolKey(): void
	{
		$this->rename = true;
	}

	public function renameToConfigKey(): bool
	{
		return $this->rename;
	}

	public function setFallbackUrl( string $url ): void
	{
		$this->fallbackUrl = $url;
	}

	public function getFallbackUrl(): string
	{
		return $this->fallbackUrl;
	}
}
