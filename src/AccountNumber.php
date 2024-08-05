<?php

namespace Pankki;

class AccountNumber
{
	protected $number;

	public function __construct(string $number)
	{
		$this->setNumber($number);
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

	public function hasPrefix(): bool
	{
		return (int)$this->getPrefix();
	}

	public function getBody(): string
	{
		return mb_str_pad(mb_substr($this->getNumber(), 6, 10), 10, 0, \STR_PAD_LEFT);
	}

	public function getStandardized(): string
	{
		return mb_str_pad($this->getNumber(), 16, 0, \STR_PAD_LEFT);
	}

	public function getFormatted(): string
	{
		return implode("/", array_filter([
			implode("-", array_filter([
				$this->hasPrefix() ? (int)$this->getPrefix() : null,
				$this->getNumber(),
			])),
		]));
	}
}
