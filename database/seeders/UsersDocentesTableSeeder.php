<?php

use Illuminate\Database\Seeder;
use App\Models\User_docente;

class UsersDocentesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Add user default
         *
         */
        User_docente::create([
            'empleado_id'                    => 703,
            'password'                       => Hash::make('DOCENTE123'),
            'token'                          => str_random(64),
        ]);
    }
}
