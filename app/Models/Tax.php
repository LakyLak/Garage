<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    const DAY_TARIFF_TYPE   = 1;
    const NIGHT_TARIFF_TYPE = 2;

    const DAY_TARIFF_START   = 8;
    const NIGHT_TARIFF_START = 18;

    const CARD_SILVER   = 1;
    const CARD_GOLD     = 2;
    const CARD_PLATINUM = 3;

    public static $discount_per_card = [
        self::CARD_SILVER   => 10,
        self::CARD_GOLD     => 15,
        self::CARD_PLATINUM => 20,
    ];

    protected $fillable = ['vehicle_type', 'tariff', 'price'];

    protected static function getPriceForHour(int $vehicle_type, int $tariff)
    {
        $tax = Tax::where(
            [
                ['vehicle_type', $vehicle_type],
                ['tariff', $tariff],
            ]
        )->first();

        return $tax->price;
    }
}
