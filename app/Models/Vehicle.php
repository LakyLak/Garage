<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    const TYPE_A = 1;
    const TYPE_B = 2;
    const TYPE_C = 3;

    const DAY_IN_HOURS = 24;

    protected $night_tariff_hours = 0;
    protected $day_tariff_hours   = 0;

    protected $fillable = ['registration_number', 'vehicle_type', 'card'];

    public function getDueAmount()
    {
        $tariff_duration = $this->setTariffDuration();
        if (!$tariff_duration) {
            return 0;
        }

        $price = $this->calculateAmount();
        $price = $this->applyDiscount($price);

        return $price;
    }

    protected function applyDiscount(int $price)
    {
        $discount = $price / 100 * Tax::$discount_per_card[$this->card];

        return $price - $discount;
    }

    protected function calculateAmount()
    {
        $price = 0;

        $price += $this->day_tariff_hours * Tax::getPriceForHour($this->vehicle_type, Tax::DAY_TARIFF_TYPE);
        $price += $this->night_tariff_hours * Tax::getPriceForHour($this->vehicle_type, Tax::NIGHT_TARIFF_TYPE);

        return $price;
    }

    protected function getStayDuration()
    {
        return floor(abs(strtotime($this->created_at) - time())/(60*60));
    }

    protected function setTariffDuration()
    {
        $stay_duration = $this->getStayDuration();
        if ($stay_duration == 0) {
            return false;
        }

        $additional_days = floor($stay_duration / self::DAY_IN_HOURS);

        $this->night_tariff_hours += 14 * $additional_days;
        $this->day_tariff_hours   += 10 * $additional_days;

        $starting_hour = date('H', strtotime($this->created_at));
        $difference    = date('H') - $starting_hour;

        $difference = $difference < 0 ? $difference + 24 : $difference;

        $this->setTariffHours($starting_hour, $difference);

        return true;
    }

    protected function setTariffHours(int $starting_hour, int $difference)
    {
        if ($starting_hour < Tax::DAY_TARIFF_START) {
            $night_hours              = Tax::DAY_TARIFF_START - $starting_hour;
            $this->night_tariff_hours += $night_hours;
            $starting_hour            = Tax::DAY_TARIFF_START;
            $difference               -= $night_hours;
            if ($difference > 0) {
                $this->setTariffHours($starting_hour, $difference);
            }
        }
        if ($starting_hour < Tax::NIGHT_TARIFF_START) {
            $day_hours              = Tax::NIGHT_TARIFF_START - $starting_hour;
            $this->day_tariff_hours += $day_hours;
            $starting_hour          = Tax::NIGHT_TARIFF_START;
            $difference             -= $day_hours;
            if ($difference > 0) {
                $this->setTariffHours($starting_hour, $difference);
            }
        }
        if ($starting_hour >= Tax::NIGHT_TARIFF_START) {
            $this->night_tariff_hours += $difference;
        }
    }
}

