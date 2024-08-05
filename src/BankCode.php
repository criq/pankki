<?php

namespace Pankki;

use Phan\Language\Element\Func;

class BankCode
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

	public function setCode(string $code): BankCode
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
		return mb_str_pad($this->getCode(), 4, 0, \STR_PAD_LEFT);
	}

	public function getFormatted(): string
	{
		return $this->getStandardized();
	}
}
