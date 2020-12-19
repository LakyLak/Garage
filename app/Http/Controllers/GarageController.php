<?php

namespace App\Http\Controllers;

use App\Models\Garage;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class GarageController extends Controller
{
    public function space()
    {
        $result = $this->getFreeSpaces();

        return response()->json($result, 200);
    }

    public function price(Request $request)
    {
        $registration_number = $request->get('registration_number', null);

        $due_amount = $this->getCurrentPrice($registration_number);

        return response()->json($due_amount, 200);
    }

    public function enter(Request $request)
    {
        $parameters = $request->all();

        $garage = Garage::getInstance();
        if ($this->getFreeSpaces() < $garage->spaces_per_vehicle_type[$parameters['vehicle_type']]) {
            return 'No free spaces left';
        }

        if ($vehicle = Vehicle::create($parameters)) {
            return response()->json($vehicle, 201);
        }
    }

    public function exit(Request $request)
    {
        $registration_number = $request->input('registration_number');

        $due_amount = $this->getCurrentPrice($registration_number);

        Vehicle::where('registration_number', $registration_number)->delete();

        return response()->json($due_amount, 200);
    }

    protected function getFreeSpaces()
    {
        $garage = Garage::getInstance();

        return $garage->getFreeSpaces();
    }

    protected function getCurrentPrice(string $registration_number)
    {
        $vehicle = Vehicle::where('registration_number', $registration_number)->first();

        return $vehicle->getDueAmount();
    }
}
