<?php

namespace Pankki;

class Currency
{
	protected $id;
	protected $code;

	public function __construct(string $code, int $id)
	{
		$this->setCode($code);
		$this->setId($id);
	}

	public function setCode(string $code): Currency
	{
		$this->code = $code;

		return $this;
	}

	public function getCode(): string
	{
		return $this->code;
	}

	public function setId(int $id): Currency
	{
		$this->id = $id;

		return $this;
	}

	public function getId(): int
	{
		return $this->id;
	}
}
