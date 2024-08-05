<?php

namespace Pankki;

class Account
{
	protected $accountNumber;
	protected $bankCode;

	public function __construct(AccountNumber $accountNumber, BankCode $bankCode)
	{
		$this->setAccountNumber($accountNumber);
		$this->setBankCode($bankCode);
	}

	public function __toString(): string
	{
		return $this->getFormatted();
	}

	public function setAccountNumber(AccountNumber $accountNumber): Account
	{
		$this->accountNumber = $accountNumber;

		return $this;
	}

	public function getAccountNumber(): AccountNumber
	{
		return $this->accountNumber;
	}

	public function setBankCode(BankCode $bankCode): Account
	{
		$this->bankCode = $bankCode;

		return $this;
	}

	public function getBankCode(): BankCode
	{
		return $this->bankCode;
	}

	public function getFormatted(): string
	{
		return implode("/", array_filter([
			$this->getAccountNumber()->getFormatted(),
			$this->getBankCode()->getFormatted(),
		]));
	}

	public function getIBAN(): IBAN
	{
		$iban = new \PHP_IBAN\IBAN(implode([
			"CZ",
			"00",
			$this->getBankCode()->getStandardized(),
			$this->getAccountNumber()->getStandardized(),
		]));
		$iban->setChecksum();

		return new IBAN($iban->iban);
	}
}
