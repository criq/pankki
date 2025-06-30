<?php

namespace Pankki;

class IBAN
{
	protected $iban;

	public function __construct(string $iban)
	{
		$this->setIBAN($iban);
	}

	public function __toString(): string
	{
		return $this->getIBAN();
	}

	public function setIBAN(string $iban): IBAN
	{
		$this->iban = $iban;

		return $this;
	}

	public function getIBAN(): string
	{
		return $this->iban;
	}

	public function getPHPIBAN(): \PHP_IBAN\IBAN
	{
		return new \PHP_IBAN\IBAN($this->getIBAN());
	}

	public function getAccountNumber(): AccountNumber
	{
		return new AccountNumber(implode([
			mb_str_pad($this->getPHPIBAN()->Branch(), 6, 0, \STR_PAD_LEFT),
			mb_str_pad($this->getPHPIBAN()->Account(), 10, 0, \STR_PAD_LEFT),
		]));
	}

	public function getBankCode(): BankCode
	{
		return new BankCode($this->getPHPIBAN()->Bank());
	}

	public function getCountryCode(): CountryCode
	{
		return new CountryCode($this->getPHPIBAN()->Country());
	}

	public function getAccount(): Account
	{
		return new Account(
			$this->getAccountNumber(),
			$this->getBankCode(),
			$this->getCountryCode(),
		);
	}

	public function getIsValid(): bool
	{
		return $this->getPHPIBAN()->Verify();
	}
}
