<?php

namespace Pankki;

use App\Classes\Errors\Error;
use Katu\Tools\Strings\Code;
use Katu\Tools\Validation\Param;
use Katu\Tools\Validation\Validation;
use Katu\Tools\Validation\ValidationCollection;

class IBANCollection extends \ArrayObject
{
	public static function validate(Param $ibans): Validation
	{
		$array = array_filter((array)$ibans->getInput(), function ($iban) {
			return is_string($iban) && !empty(trim($iban));
		});

		$validation = (new ValidationCollection(array_map(function (string $input, int $index) use ($ibans) {
			$param = new Param("{$ibans->getKey()}.{$index}", $input);
			$validation = new Validation;

			$output = trim($input);
			if (!mb_strlen($output)) {
				$validation->setResponse(null)->addParam($param->setOutput(null));
			} else {
				try {
					$iban = new IBAN($output);
					if ($iban->getIsValid()) {
						$validation->setResponse($iban)->addParam($param->setOutput($iban));
					} else {
						$validation->addError((new Error("pankki.iban.invalid", new Code("INVALID_IBAN")))->addParam($param));
					}
				} catch (\Throwable $e) {
					$validation->addError((new Error("pankki.iban.invalid", new Code("INVALID_IBAN")))->addParam($param));
				}
			}

			return $validation;
		}, $array, array_keys($array))))->getMerged();

		if ($validation->hasErrors()) {
			return $validation;
		}

		$output = new IBANCollection(array_values(array_filter(array_map(function (Param $param) {
			return $param->getOutput();
		}, $validation->getParams()->getArrayCopy()))));

		$validation->setResponse($output)->addParam($ibans->setOutput($output));

		return $validation;
	}

	public function getAccounts(): AccountCollection
	{
		return new AccountCollection(array_map(function (IBAN $iban) {
			return $iban->getAccount();
		}, $this->getArrayCopy()));
	}
}
