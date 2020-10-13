<?php

namespace App\Enum;

class CampaignTag extends Enumeration
{
    const ASSURANCE_MUTUELLE = 1;
    const AUTOMOBILE = 2;
    const EMPLOI_FORMATION = 3;
    const FINANCE = 4;
    const JEUX_ENQUETES = 5;
    const MAISON_JARDIN = 6;
    const MEDIAS_PRESSE = 7;
    const MODE_BIJOUX = 8;
    const NOURRITURE_BOISSONS = 9;
    const SANTE_BEAUTE = 10;
    const SPORT_LOISIRS = 11;
    const TELECOMS_HIGH_TECH = 12;
    const TRAVAUX_DECO = 13;
    const VOYAGE = 14;
    const VOYANCE_SPIRITUALITE = 15;

    protected static $descriptions
        = [
            self::ASSURANCE_MUTUELLE    => 'Assurance / Mutuelle',
            self::AUTOMOBILE            => 'Automobile',
            self::EMPLOI_FORMATION      => 'Emploi / Formation',
            self::FINANCE               => 'Finance',
            self::JEUX_ENQUETES         => 'Jeux / Enquêtes',
            self::MAISON_JARDIN         => 'Maison / Jardin',
            self::MEDIAS_PRESSE         => 'Médias / Presse',
            self::MODE_BIJOUX           => 'Mode / Bijoux',
            self::NOURRITURE_BOISSONS   => 'Nourriture / Boissons',
            self::SANTE_BEAUTE          => 'Santé / Beauté',
            self::SPORT_LOISIRS         => 'Sport / Loisirs',
            self::TELECOMS_HIGH_TECH    => 'Télécoms / High-Tech',
            self::TRAVAUX_DECO          => 'Travaux / Déco',
            self::VOYAGE                => 'Voyage',
            self::VOYANCE_SPIRITUALITE  => 'Voyance spiritualité',
        ];
}
