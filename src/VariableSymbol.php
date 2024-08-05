<?php

namespace Pankki;

class VariableSymbol
{
	protected $value;

	public function __construct(?string $value)
	{
		$this->setValue($value);
	}

	public function __toString(): string
	{
		return $this->getStandardized();
	}

	public function setValue(?string $value): VariableSymbol
	{
		$this->value = $value;

		return $this;
	}

	public function getValue(): string
	{
		return $this->value;
	}

	public function getStandardized(): string
	{
		return mb_str_pad($this->getValue(), 10, 0, \STR_PAD_LEFT);
	}

	public function getFormatted(): ?string
	{
		return ltrim($this->getStandardized(), "0");
	}
}
