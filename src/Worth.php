<?php

namespace Pankki;

use NumberFormatter;

class Worth
{
	protected $amount;
	protected $currency;

	public function __construct(float $amount, Currency $currency)
	{
		$this->setAmount($amount);
		$this->setCurrency($currency);
	}

	public function __toString(): string
	{
		return implode(" ", [
			$this->getAmount(),
			$this->getCurrency()->getCode(),
		]);
	}

	public function setAmount(float $amount): Worth
	{
		$this->amount = $amount;

		return $this;
	}

	public function getAmount(): float
	{
		return $this->amount;
	}

	public function setCurrency(Currency $currency): Worth
	{
		$this->currency = $currency;

		return $this;
	}

	public function getCurrency(): Currency
	{
		return $this->currency;
	}

	public function getFormatted(?\Katu\Tools\Intl\Locale $locale = null): string
	{
		if ($locale) {
			$formatter = new NumberFormatter($locale->getCode(), NumberFormatter::CURRENCY);
			$formatter->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, (float)$this->getAmount() === (float)round($this->getAmount()) ? 0 : 2);

			return $formatter->formatCurrency($this->getAmount(), $this->getCurrency()->getCode());
		}

		return implode(" ", [
			$this->getAmount(),
			$this->getCurrency()->getCode(),
		]);
	}
}
