<?php

namespace Pankki;

class CountryCode
{
	protected $code;

	public function __construct(string $code)
	{
		$this->setCode($code);
	}

	public function __toString(): string
	{
		return $this->getStandardized();
	}

	public function setCode(string $code): CountryCode
	{
		$this->code = $code;

		return $this;
	}

	public function getCode(): string
	{
		return $this->code;
	}

	public function getStandardized(): string
	{
		return mb_strtoupper($this->getCode());
	}

	public function getFormatted(): string
	{
		return $this->getStandardized();
	}
}
