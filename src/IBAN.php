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
}
