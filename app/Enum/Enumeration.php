<?php

namespace App\Enum;

use ReflectionClass;

abstract class Enumeration implements EnumerationInterface
{
    protected static $descriptions = [];
    protected $value;

    protected static function getValues(): array
    {
        static $constants = null;
        if (!$constants) {
            // Get constants
            $refl = new ReflectionClass(get_called_class());

            $constants = array_flip($refl->getConstants());
            ksort($constants);

            // Map descriptions
            foreach (static::$descriptions as $constValue => $description) {
                $constants[$constValue] = $description;
            }
        }

        return $constants;
    }

    public static function getIdByDescription(string $description): int
    {
        return array_search($description, static::getValues());
    }

    public static function getDescriptionById(int $id): string
    {
        $values = static::getValues();

        return isset($values[$id]) ? $values[$id] : false;
    }

    public static function getDescriptionByKey($id): string
    {
        $values = static::getValues();

        return isset($values[$id]) ? $values[$id] : false;
    }
    public static function getAll(): array
    {
        return static::getValues();
    }
}
