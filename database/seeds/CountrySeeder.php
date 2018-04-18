<?php

use Illuminate\Database\Seeder;
use App\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->truncate();

        $filename = dirname(__FILE__) . '/data/countries.csv';
        foreach(file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            
            $infoPais = explode(",", $line);
            $country = ['iatacode' => $infoPais[0],
                        'name' => $infoPais[1]];
            Country::create($country);
        }

    }
        
}