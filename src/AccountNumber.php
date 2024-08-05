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
		return mb_str_pad(mb_substr($this->getNumber(), 0, 6), 6, 0, \STR_PAD_LEFT);
	}

	public function getFormattedPrefix(): ?string
	{
		return ltrim($this->getPrefix(), "0");
	}

	public function getBody(): string
	{
		return mb_str_pad(mb_substr($this->getNumber(), 6, 10), 10, 0, \STR_PAD_LEFT);
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
