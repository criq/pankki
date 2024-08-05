<?php

namespace GPC;

class Account
{
	protected $accountId;
	protected $bankId;

	public function __construct(string $accountId, ?string $bankId = null)
	{
		$this->setAccountId($accountId);
		$this->setBankId($bankId);
	}

	public function setAccountId(string $accountId): Account
	{
		$this->accountId = $accountId;

		return $this;
	}

	public function getAccountId(): string
	{
		return $this->accountId;
	}

	public function setBankId(?string $bankId): Account
	{
		$this->bankId = $bankId;

		return $this;
	}

	public function getBankId(): ?string
	{
		return $this->bankId;
	}

	public function getPrefix(): string
	{
		return mb_substr($this->getAccountId(), 0, 6);
	}

	public function hasPrefix(): bool
	{
		return (int)$this->getPrefix();
	}

	public function getNumber(): string
	{
		return mb_substr($this->getAccountId(), 6, 10);
	}

	public function getFormatted(): string
	{
		return implode("/", array_filter([
			implode("-", array_filter([
				$this->hasPrefix() ? (int)$this->getPrefix() : null,
				$this->getNumber(),
			])),
			$this->getBankId(),
		]));
	}

	public function getIBAN(): string
	{
		$iban = new \PHP_IBAN\IBAN(implode([
			"CZ",
			"00",
			$this->getBankId(),
			str_pad($this->getPrefix(), 6, 0, \STR_PAD_LEFT),
			str_pad($this->getNumber(), 10, 0, \STR_PAD_LEFT),
		]));
		$iban->setChecksum();

		return $iban->iban;
	}
}
