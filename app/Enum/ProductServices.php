<?php

namespace App\Enum;

class ProductServices extends Enumeration {
	const WITHOUT_TARGETING = 1;
	const WITH_GEOGRAPHIC_TARGETING = 2;
	const WITH_SOCIO_GEOGRAPHIC_TARGETING = 3;
	const WITH_SOCIO_GEOGRAPHIC_TARGETING_AND_INTENTION_CRITERIA = 4;

	protected static $descriptions
		= [
			self::WITHOUT_TARGETING                                      => 'Without targeting',
			self::WITH_GEOGRAPHIC_TARGETING                              => 'With geographic targeting',
			self::WITH_SOCIO_GEOGRAPHIC_TARGETING                        => 'With socio-geographic targeting',
			self::WITH_SOCIO_GEOGRAPHIC_TARGETING_AND_INTENTION_CRITERIA => 'With socio-geographic targeting + intention criteria / behaviors',
		];

    public static function getDescriptions(): array
    {
        return self::$descriptions;
    }
}
