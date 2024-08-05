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

	// public function getPrefix(): string
	// {
	// 	return mb_substr($this->getAccountId(), 0, 6);
	// }

	// public function hasPrefix(): bool
	// {
	// 	return (int)$this->getPrefix();
	// }

	// public function getNumber(): string
	// {
	// 	return mb_substr($this->getAccountId(), 6, 10);
	// }

	public function getFormatted(): string
	{
		return implode("/", array_filter([
			$this->getAccountNumber()->getFormatted(),
			$this->getBankCode()->getFormatted(),
		]));
	}

	public function getIBAN(): string
	{
		$iban = new \PHP_IBAN\IBAN(implode([
			"CZ",
			"00",
			$this->getBankCode()->getStandardized(),
			$this->getAccountNumber()->getStandardized(),
		]));
		$iban->setChecksum();

		return $iban->iban;
	}
}
