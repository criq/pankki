<?php

namespace Pankki;

abstract class Symbol
{
	abstract public function getLength(): int;

	protected $value;

	public function __construct(?string $value = null)
	{
		$this->setValue($value);
	}

	public function __toString(): string
	{
		return $this->getStandardized();
	}

	public function setValue(?string $value): Symbol
	{
		$this->value = $value;

		return $this;
	}

	public function getValue(): ?string
	{
		return $this->value;
	}

	public function getStandardized(): string
	{
		return mb_str_pad((string)$this->getValue(), $this->getLength(), 0, \STR_PAD_LEFT);
	}

	public function getFormatted(): ?string
	{
		return ltrim($this->getStandardized(), "0");
	}

	public function getIsFilled(): bool
	{
		return (int)$this->getStandardized() > 0;
	}
}
