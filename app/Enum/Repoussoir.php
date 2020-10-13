<?php

namespace App\Enum;

class Repoussoir extends Enumeration {
    const LOCAL = 'local';
    const NATIONAL = 'national';

    protected static $descriptions
        = [
            self::LOCAL => 'local',
            self::NATIONAL  => 'national',
        ];
}
