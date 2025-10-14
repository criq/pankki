# Pankki Library - AI Agent Documentation

> **Agent Protocol: Keep This Document Updated**
> All agents are required to update this document with any new, relevant information discovered during their work on the Pankki library. This includes changes in banking functionality, new payment methods, updated IBAN handling, or newly established banking patterns.

This document provides comprehensive technical documentation for the Pankki library, a PHP banking and payment processing library used in the Hnutí DUHA IS application. The library handles bank account management, IBAN generation, payment symbols, and currency operations.

---

## Table of Contents

1. [Library Overview](#1-library-overview)
2. [Core Architecture](#2-core-architecture)
3. [Account Management](#3-account-management)
4. [IBAN Operations](#4-iban-operations)
5. [Payment Symbols](#5-payment-symbols)
6. [Currency Handling](#6-currency-handling)
7. [Country Codes](#7-country-codes)
8. [Common Patterns](#8-common-patterns)
9. [Troubleshooting](#9-troubleshooting)
10. [Development Guidelines](#10-development-guidelines)
11. [API Reference](#11-api-reference)

---

## 1. Library Overview

### 1.1. Purpose

The Pankki library is responsible for:

- **Bank Account Management**: Account number and bank code handling
- **IBAN Generation**: International Bank Account Number creation and validation
- **Payment Symbols**: Variable, constant, and specific symbol management
- **Currency Operations**: Currency formatting and conversion
- **Country Code Handling**: ISO country code standardization
- **Payment Processing**: Bank transfer and payment symbol generation

### 1.2. Key Features

- **Account Management**: Complete bank account handling with prefix and body
- **IBAN Support**: Full IBAN generation and validation
- **Payment Symbols**: Variable, constant, and specific symbol management
- **Currency Formatting**: Localized currency display
- **Country Standardization**: ISO country code handling
- **REST API Support**: JSON response generation
- **Validation**: Comprehensive input validation and error handling

### 1.3. Core Components

- **Account**: Bank account management with IBAN generation
- **AccountNumber**: Account number parsing and formatting
- **BankCode**: Bank code standardization
- **IBAN**: International Bank Account Number operations
- **Symbol**: Payment symbol base class
- **VariableSymbol**: Variable symbol management
- **Currency**: Currency code and ID management
- **CountryCode**: Country code standardization
- **Worth**: Monetary value with currency

### 1.4. Dependencies

- **PHP_IBAN**: IBAN validation and generation
- **NumberFormatter**: Currency formatting
- **Katu Framework**: REST response and options handling

---

## 2. Core Architecture

### 2.1. Account Class (`Pankki\Account`)

**Location**: `src/Account.php`

Central bank account management class:

```php
class Account implements RestResponseInterface
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
}
```

**Key Features**:

- **Account Management**: Complete bank account handling
- **IBAN Generation**: Automatic IBAN creation from account details
- **Country Code Support**: Default and custom country codes
- **REST API**: JSON response generation
- **String Conversion**: Formatted account display

### 2.2. AccountNumber Class (`Pankki\AccountNumber`)

**Location**: `src/AccountNumber.php`

Account number parsing and formatting:

```php
class AccountNumber
{
    protected $number;

    public function __construct(string $number)
    {
        $this->setNumber($number);
    }
}
```

**Key Features**:

- **Number Parsing**: Parse account numbers from various formats
- **Prefix/Body Separation**: Split account into prefix and body
- **Standardization**: 16-digit standardized format
- **Formatting**: Human-readable account display

### 2.3. IBAN Class (`Pankki\IBAN`)

**Location**: `src/IBAN.php`

International Bank Account Number operations:

```php
class IBAN
{
    protected $iban;

    public function __construct(string $iban)
    {
        $this->setIBAN($iban);
    }
}
```

**Key Features**:

- **IBAN Validation**: Verify IBAN format and checksum
- **Account Extraction**: Extract account details from IBAN
- **Country Detection**: Determine country from IBAN
- **Bank Code Extraction**: Extract bank code from IBAN

---

## 3. Account Management

### 3.1. Account Creation

#### Basic Account Creation

```php
// Create account with account number and bank code
$accountNumber = new AccountNumber("1234567890");
$bankCode = new BankCode("0300");
$account = new Account($accountNumber, $bankCode);

// Create account with country code
$countryCode = new CountryCode("CZ");
$account = new Account($accountNumber, $bankCode, $countryCode);
```

#### Account from String

```php
// Create account from formatted string
$account = Account::createFromString("1234567890/0300");

// Account with prefix
$account = Account::createFromString("123456-7890/0300");
```

### 3.2. Account Properties

#### Account Information

```php
// Get account components
$accountNumber = $account->getAccountNumber();
$bankCode = $account->getBankCode();
$countryCode = $account->getCountryCode();

// Get resolved country code (with default)
$resolvedCountry = $account->getResolvedCountryCode();
```

#### Account Formatting

```php
// Get formatted account string
$formatted = $account->getFormatted(); // "1234567890/0300"

// Get IBAN
$iban = $account->getIBAN(); // IBAN object
$ibanString = (string)$iban; // "CZ6503000000001234567890"
```

### 3.3. Account Methods

#### Account Operations

```php
// Set account components
$account->setAccountNumber($accountNumber);
$account->setBankCode($bankCode);
$account->setCountryCode($countryCode);

// Get default country code
$defaultCountry = Account::getDefaultCountryCode(); // "CZ"
```

#### REST API Response

```php
// Get REST response
$response = $account->getRestResponse($request, $options);

// Response format:
// {
//     "iban": "CZ6503000000001234567890",
//     "accountNumber": "0000001234567890",
//     "bankCode": "0300",
//     "countryCode": "CZ",
//     "formatted": "1234567890/0300"
// }
```

---

## 4. IBAN Operations

### 4.1. IBAN Creation

#### IBAN from Account

```php
// Create IBAN from account
$account = new Account($accountNumber, $bankCode, $countryCode);
$iban = $account->getIBAN();
```

#### IBAN from String

```php
// Create IBAN from string
$iban = new IBAN("CZ6503000000001234567890");
```

### 4.2. IBAN Properties

#### IBAN Information

```php
// Get IBAN string
$ibanString = $iban->getIBAN();

// Get PHP_IBAN object
$phpIban = $iban->getPHPIBAN();

// Check validity
$isValid = $iban->getIsValid();
```

#### Account Extraction

```php
// Extract account details from IBAN
$accountNumber = $iban->getAccountNumber();
$bankCode = $iban->getBankCode();
$countryCode = $iban->getCountryCode();
$account = $iban->getAccount();
```

### 4.3. IBAN Validation

#### Validation Methods

```php
// Check IBAN validity
$isValid = $iban->getIsValid();

// Get PHP_IBAN object for advanced operations
$phpIban = $iban->getPHPIBAN();
$isValid = $phpIban->Verify();
```

---

## 5. Payment Symbols

### 5.1. Symbol Base Class

#### Symbol Management

```php
abstract class Symbol
{
    abstract public function getLength(): int;

    protected $value;

    public function __construct(?string $value = null)
    {
        $this->setValue($value);
    }
}
```

#### Symbol Operations

```php
// Set symbol value
$symbol->setValue("1234567890");

// Get symbol value
$value = $symbol->getValue();

// Get standardized format
$standardized = $symbol->getStandardized();

// Get formatted display
$formatted = $symbol->getFormatted();

// Check if symbol is filled
$isFilled = $symbol->getIsFilled();
```

### 5.2. Variable Symbol

#### Variable Symbol Management

```php
class VariableSymbol extends Symbol
{
    public function getLength(): int
    {
        return 10;
    }
}
```

#### Variable Symbol Usage

```php
// Create variable symbol
$variableSymbol = new VariableSymbol("1234567890");

// Get standardized format (10 digits)
$standardized = $variableSymbol->getStandardized(); // "1234567890"

// Get formatted display
$formatted = $variableSymbol->getFormatted(); // "1234567890"
```

### 5.3. Symbol Types

#### Available Symbol Types

- **VariableSymbol**: 10-digit variable symbol
- **ConstantSymbol**: Constant symbol for payments
- **SpecificSymbol**: Specific symbol for payments

#### Symbol Inheritance

```php
// All symbols extend the base Symbol class
class VariableSymbol extends Symbol
class ConstantSymbol extends Symbol
class SpecificSymbol extends Symbol
```

---

## 6. Currency Handling

### 6.1. Currency Class

#### Currency Management

```php
class Currency
{
    protected $id;
    protected $code;

    public function __construct(string $code, int $id)
    {
        $this->setCode($code);
        $this->setId($id);
    }
}
```

#### Currency Operations

```php
// Create currency
$currency = new Currency("CZK", 203);

// Get currency properties
$code = $currency->getCode(); // "CZK"
$id = $currency->getId(); // 203

// Set currency properties
$currency->setCode("EUR");
$currency->setId(978);
```

### 6.2. Worth Class

#### Monetary Value Management

```php
class Worth
{
    protected $amount;
    protected $currency;

    public function __construct(float $amount, Currency $currency)
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }
}
```

#### Worth Operations

```php
// Create monetary value
$worth = new Worth(100.50, $currency);

// Get amount and currency
$amount = $worth->getAmount(); // 100.50
$currency = $worth->getCurrency(); // Currency object

// Format with locale
$formatted = $worth->getFormatted($locale); // "100,50 CZK"
```

### 6.3. Currency Formatting

#### Localized Formatting

```php
// Format with NumberFormatter
$formatter = new NumberFormatter($locale->getCode(), NumberFormatter::CURRENCY);
$formatted = $formatter->formatCurrency($amount, $currency->getCode());

// Format without locale
$formatted = $worth->getFormatted(); // "100.5 CZK"
```

---

## 7. Country Codes

### 7.1. CountryCode Class

#### Country Code Management

```php
class CountryCode
{
    protected $code;

    public function __construct(string $code)
    {
        $this->setCode($code);
    }
}
```

#### Country Code Operations

```php
// Create country code
$countryCode = new CountryCode("CZ");

// Get country code
$code = $countryCode->getCode(); // "CZ"

// Get standardized format
$standardized = $countryCode->getStandardized(); // "CZ"

// Get formatted display
$formatted = $countryCode->getFormatted(); // "CZ"
```

### 7.2. Country Code Standardization

#### Standardization Methods

```php
// Standardize country code
$countryCode = new CountryCode("cz");
$standardized = $countryCode->getStandardized(); // "CZ"

// Set country code
$countryCode->setCode("SK");
```

---

## 8. Common Patterns

### 8.1. Account Creation

```php
// Create account from components
$accountNumber = new AccountNumber("1234567890");
$bankCode = new BankCode("0300");
$countryCode = new CountryCode("CZ");
$account = new Account($accountNumber, $bankCode, $countryCode);

// Create account from string
$account = Account::createFromString("1234567890/0300");
```

### 8.2. IBAN Generation

```php
// Generate IBAN from account
$account = new Account($accountNumber, $bankCode, $countryCode);
$iban = $account->getIBAN();
$ibanString = (string)$iban;

// Validate IBAN
$iban = new IBAN($ibanString);
$isValid = $iban->getIsValid();
```

### 8.3. Payment Symbols

```php
// Create variable symbol
$variableSymbol = new VariableSymbol("1234567890");
$standardized = $variableSymbol->getStandardized();
$formatted = $variableSymbol->getFormatted();
```

### 8.4. Currency Operations

```php
// Create currency
$currency = new Currency("CZK", 203);

// Create monetary value
$worth = new Worth(100.50, $currency);
$formatted = $worth->getFormatted($locale);
```

---

## 9. Troubleshooting

### 9.1. Common Issues

#### Account Creation Failures

- Check account number format (16 digits)
- Verify bank code format (4 digits)
- Validate country code (2 letters)
- Check IBAN generation

#### IBAN Validation Issues

- Verify IBAN format and length
- Check country code validity
- Validate checksum calculation
- Ensure proper account number format

#### Symbol Management Issues

- Check symbol length requirements
- Validate symbol format
- Ensure proper padding
- Check symbol type compatibility

### 9.2. Debugging

#### Account Information

```php
// Get account details
$account = new Account($accountNumber, $bankCode, $countryCode);
echo "Account: " . $account->getFormatted();
echo "IBAN: " . $account->getIBAN();
echo "Country: " . $account->getResolvedCountryCode();
```

#### IBAN Debugging

```php
// Check IBAN validity
$iban = new IBAN($ibanString);
echo "IBAN: " . $iban->getIBAN();
echo "Valid: " . ($iban->getIsValid() ? "Yes" : "No");

// Extract account details
$account = $iban->getAccount();
echo "Account: " . $account->getFormatted();
```

#### Symbol Debugging

```php
// Check symbol details
$symbol = new VariableSymbol("1234567890");
echo "Value: " . $symbol->getValue();
echo "Standardized: " . $symbol->getStandardized();
echo "Formatted: " . $symbol->getFormatted();
echo "Filled: " . ($symbol->getIsFilled() ? "Yes" : "No");
```

---

## 10. Development Guidelines

### 10.1. Account Management

**Requirements**:

- Always use proper account number format
- Validate bank codes
- Handle country code defaults
- Implement proper error handling

### 10.2. IBAN Operations

**Best Practices**:

- Validate IBAN before use
- Handle IBAN generation errors
- Implement proper checksum validation
- Use standardized formats

### 10.3. Payment Symbols

**Symbol Guidelines**:

- Use appropriate symbol types
- Validate symbol lengths
- Implement proper padding
- Handle symbol formatting

### 10.4. Currency Handling

**Currency Requirements**:

- Use proper currency codes
- Implement localized formatting
- Handle currency conversion
- Validate monetary values

---

## 11. API Reference

### 11.1. Account Class

```php
class Account implements RestResponseInterface
{
    // Constructor
    public function __construct(AccountNumber $accountNumber, BankCode $bankCode, ?CountryCode $countryCode = null);

    // Static methods
    public static function createFromString(string $string): ?Account;
    public static function getDefaultCountryCode(): CountryCode;

    // Properties
    public function setAccountNumber(AccountNumber $accountNumber): Account;
    public function getAccountNumber(): AccountNumber;
    public function setBankCode(BankCode $bankCode): Account;
    public function getBankCode(): BankCode;
    public function setCountryCode(?CountryCode $countryCode): Account;
    public function getCountryCode(): ?CountryCode;
    public function getResolvedCountryCode(): ?CountryCode;

    // Operations
    public function getFormatted(): string;
    public function getIBAN(): IBAN;
    public function getRestResponse(?ServerRequestInterface $request = null, ?OptionCollection $options = null): RestResponse;
}
```

### 11.2. AccountNumber Class

```php
class AccountNumber
{
    // Constructor
    public function __construct(string $number);

    // Static methods
    public static function createFromString(string $string): ?AccountNumber;

    // Properties
    public function setNumber(string $number): AccountNumber;
    public function getNumber(): string;

    // Operations
    public function getPrefix(): string;
    public function getFormattedPrefix(): ?string;
    public function getBody(): string;
    public function getFormattedBody(): string;
    public function getStandardized(): string;
    public function getFormatted(): string;
}
```

### 11.3. IBAN Class

```php
class IBAN
{
    // Constructor
    public function __construct(string $iban);

    // Properties
    public function setIBAN(string $iban): IBAN;
    public function getIBAN(): string;

    // Operations
    public function getPHPIBAN(): \PHP_IBAN\IBAN;
    public function getAccountNumber(): AccountNumber;
    public function getBankCode(): BankCode;
    public function getCountryCode(): CountryCode;
    public function getAccount(): Account;
    public function getIsValid(): bool;
}
```

### 11.4. IBANCollection Class

```php
class IBANCollection extends \ArrayObject
{
    // Static methods
    public static function validate(Param $ibans): Validation;

    // Instance methods
    public function getAccounts(): AccountCollection;
}
```

**Key Features**:
- **IBAN Validation**: Validates arrays of IBAN strings
- **Account Conversion**: Simple method to convert IBANs to Account objects
- **Error Handling**: Provides context-agnostic error messages
- **Collection Management**: Handles arrays of IBAN strings

**Usage Examples**:
```php
// Validate IBANs and return IBANCollection
$validation = IBANCollection::validate($param);
$ibans = $validation->getResponse(); // IBANCollection

// Convert IBANs to Accounts
$accounts = $ibans->getAccounts(); // AccountCollection
```

### 11.5. Symbol Classes

```php
abstract class Symbol
{
    // Constructor
    public function __construct(?string $value = null);

    // Abstract methods
    abstract public function getLength(): int;

    // Properties
    public function setValue(?string $value): Symbol;
    public function getValue(): ?string;

    // Operations
    public function getStandardized(): string;
    public function getFormatted(): ?string;
    public function getIsFilled(): bool;
}

class VariableSymbol extends Symbol
{
    public function getLength(): int; // Returns 10
}
```

### 11.5. Currency Classes

```php
class Currency
{
    // Constructor
    public function __construct(string $code, int $id);

    // Properties
    public function setCode(string $code): Currency;
    public function getCode(): string;
    public function setId(int $id): Currency;
    public function getId(): int;
}

class Worth
{
    // Constructor
    public function __construct(float $amount, Currency $currency);

    // Properties
    public function setAmount(float $amount): Worth;
    public function getAmount(): float;
    public function setCurrency(Currency $currency): Worth;
    public function getCurrency(): Currency;

    // Operations
    public function getFormatted(?\Katu\Tools\Intl\Locale $locale = null): string;
}
```

### 11.6. CountryCode Class

```php
class CountryCode
{
    // Constructor
    public function __construct(string $code);

    // Properties
    public function setCode(string $code): CountryCode;
    public function getCode(): string;

    // Operations
    public function getStandardized(): string;
    public function getFormatted(): string;
}
```

### 11.7. Usage Examples

```php
// Create account
$accountNumber = new AccountNumber("1234567890");
$bankCode = new BankCode("0300");
$countryCode = new CountryCode("CZ");
$account = new Account($accountNumber, $bankCode, $countryCode);

// Generate IBAN
$iban = $account->getIBAN();
$ibanString = (string)$iban;

// Create variable symbol
$variableSymbol = new VariableSymbol("1234567890");
$standardized = $variableSymbol->getStandardized();

// Create monetary value
$currency = new Currency("CZK", 203);
$worth = new Worth(100.50, $currency);
$formatted = $worth->getFormatted($locale);
```

This comprehensive documentation covers the Pankki library, providing AI agents with detailed information about bank account management, IBAN operations, payment symbols, currency handling, and country code management.
