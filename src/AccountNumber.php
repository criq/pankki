<?php

namespace Pankki;

class AccountNumber
{
	protected $number;

	public function __construct(string $number)
	{
		$this->setNumber($number);
	}

	public function __toString(): string
	{
		return $this->getStandardized();
	}

	public static function createFromString(string $string): AccountNumber
	{
		return new static(str_pad(preg_replace("/-/", "", $string), 16, 0, \STR_PAD_LEFT));
	}

	public function setNumber(string $number): AccountNumber
	{
		$this->number = $number;

		return $this;
	}

	public function getNumber(): string
	{
		return $this->number;
	}

	public function getPrefix(): string
	{
		return mb_substr($this->getStandardized(), 0, 6);
	}

	public function getFormattedPrefix(): ?string
	{
		return ltrim($this->getPrefix(), "0");
	}

	public function getBody(): string
	{
		return mb_substr($this->getStandardized(), 6, 10);
	}

	public function getFormattedBody(): string
	{
		return ltrim($this->getBody(), "0");
	}

	public function getStandardized(): string
	{
		return mb_str_pad($this->getNumber(), 16, 0, \STR_PAD_LEFT);
	}

	public function getFormatted(): string
	{
		return implode("/", array_filter([
			implode("-", array_filter([
				$this->getFormattedPrefix(),
				$this->getFormattedBody(),
			])),
		]));
	}
}
