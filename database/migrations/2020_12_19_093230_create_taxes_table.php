<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->integer('vehicle_type');
            $table->integer('tariff');
            $table->integer('price');
        });

        DB::table('taxes')->insert(
            [
                [
                    'vehicle_type' => 1,
                    'tariff'       => 1,
                    'price'        => 3,
                ],
                [
                    'vehicle_type' => 1,
                    'tariff'       => 2,
                    'price'        => 2,
                ],
                [
                    'vehicle_type' => 2,
                    'tariff'       => 1,
                    'price'        => 6,
                ],
                [
                    'vehicle_type' => 2,
                    'tariff'       => 2,
                    'price'        => 4,
                ],
                [
                    'vehicle_type' => 3,
                    'tariff'       => 1,
                    'price'        => 12,
                ],
                [
                    'vehicle_type' => 3,
                    'tariff'       => 2,
                    'price'        => 8,
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxes');
    }
}
