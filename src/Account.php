<?php

namespace Pankki;

class Account
{
  protected $accountNumber;
  protected $bankCode;
  protected $countryCode;

  public function __construct(AccountNumber $accountNumber, BankCode $bankCode, ?CountryCode $countryCode = null)
  {
    $this->setAccountNumber($accountNumber);
    $this->setBankCode($bankCode);
    $this->setCountryCode($countryCode);
  }

  public function __toString(): string
  {
    return $this->getFormatted();
  }

  public static function createFromString(string $string)
  {
    list($accountNumberString, $bankCodeString) = explode("/", $string);

    return new Account(
      AccountNumber::createFromString($accountNumberString),
      new BankCode($bankCodeString),
    );
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

  public static function getDefaultCountryCode(): CountryCode
  {
    return new CountryCode("CZ");
  }

  public function setCountryCode(?CountryCode $countryCode): Account
  {
    $this->countryCode = $countryCode;

    return $this;
  }

  public function getCountryCode(): ?CountryCode
  {
    return $this->countryCode;
  }

  public function getResolvedCountryCode(): ?CountryCode
  {
    return $this->getCountryCode() ?: $this->getDefaultCountryCode();
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
      $this->getResolvedCountryCode(),
      "00",
      $this->getBankCode()->getStandardized(),
      $this->getAccountNumber()->getStandardized(),
    ]));
    $iban->setChecksum();

    return new IBAN($iban->iban);
  }
}
