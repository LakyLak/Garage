<?php

namespace App\Models;

class Garage
{
    const GARAGE_CAPACITY = 200;

    public $spaces_per_vehicle_type = [
        Vehicle::TYPE_A => 1,
        Vehicle::TYPE_B => 2,
        Vehicle::TYPE_C => 4,
    ];

    private static $instances = [];

    public static function getInstance(): Garage
    {
        $class = static::class;
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }

        return self::$instances[$class];
    }

    protected function __construct() { }

    protected function __clone() { }

    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public function getFreeSpaces()
    {
        return self::GARAGE_CAPACITY - $this->getAllocatedSpaces();
    }

    public function getAllocatedSpaces()
    {
        $vehicles         = Vehicle::all();
        $allocated_spaces = 0;

        foreach ($vehicles as $vehicle) {
            $allocated_spaces += $this->spaces_per_vehicle_type[$vehicle->vehicle_type];
        }

        return $allocated_spaces;
    }
}
