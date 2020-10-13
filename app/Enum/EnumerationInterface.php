<?php

namespace App\Enum;

interface EnumerationInterface
{
	public static function getDescriptionById(int $id): string;

	public static function getIdByDescription(string $description): int;

	public static function getAll(): array;
}
