<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Profile;
use App\Role;
use App\Category;
use App\Modelo;
use App\Country;
use App\City;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); /*Inactivamos el chequeo de claves foranes en la BD*/
        DB::table('role_user')->truncate();
        Profile::truncate();
        User::truncate();
        Role::truncate();
        Category::truncate();
        Country::truncate();
        City::truncate();
     	

        $cantidadUsuarios = 200;
        $cantidadModelos = 50; 
     	$cantidadPerfil = 200;

        //   Agregamos algunos paises fijos 
        Country::create(array('name' => 'Colombia','iatacode' => 'COL'));
        Country::create(array('name' => 'Perú','iatacode' => 'PER'));
        Country::create(array('name' => 'Venezuela','iatacode' => 'VEN'));
        //   Agregamos algunos paises fijos 
        City::create(array('name' => 'Caracas','iatacode' => 'CCS','country_id' => '3'));
        City::create(array('name' => 'Bogotá','iatacode' => 'BOG','country_id' => '1'));
        City::create(array('name' => 'Medellin','iatacode' => 'MED','country_id' => '1'));
        City::create(array('name' => 'Cali','iatacode' => 'CAL','country_id' => '1'));

        //   Agregamos los roles fijos 
        Role::create(array('name' => 'cliente'));
        Role::create(array('name' => 'modelo'));
        Role::create(array('name' => 'administrador'));
        
        factory(User::class, $cantidadUsuarios)->create()->each(
            function($user){
                $roles = Role::all()->random(mt_rand(1,2))->pluck('id');
                $user->roles()->attach($roles);
            }
        );
        factory(Profile::class, $cantidadPerfil)->create();

        //  Agregamos las categorias fijas 
        Category::create(array('name' => 'Mujeres'));
        Category::create(array('name' => 'Hombres'));
        Category::create(array('name' => 'Trans'));
        factory(Modelo::class, $cantidadModelos)->create();

        // $this->call(UsersTableSeeder::class);
    }
}
