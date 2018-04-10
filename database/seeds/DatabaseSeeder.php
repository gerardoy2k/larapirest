<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Profile;
use App\Role;
use App\Category;

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
        User::truncate();
        Profile::truncate();
        Role::truncate();
        Category::truncate();
     	DB::table('role_user')->truncate();

     	$cantidadUsuarios = 200;
     	$cantidadPerfil = 200;

        //   Agregamos los roles fijos 
        Role::create(array('name' => 'admin'));
        Role::create(array('name' => 'evaluador'));
        Role::create(array('name' => 'consultor'));
        
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
        

        // $this->call(UsersTableSeeder::class);
    }
}
