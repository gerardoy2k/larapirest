<?php

use Illuminate\Database\Seeder;
use App\Country;
use App\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->delete();

        $filename = dirname(__FILE__) . '/data/cities.csv';
        foreach(file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            
            $infoCity = explode(",", $line);
            $country = Country::where("iatacode","=",$infoCity[0])->first();
            if(!is_null($country))
            {
                $city = ['country_id' => $country->id,
                        'iatacode' => $infoCity[1],
                        'name' => $infoCity[2]];
                City::create($city);
            }
        }

    }
        
}