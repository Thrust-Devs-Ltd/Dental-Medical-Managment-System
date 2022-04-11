<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 200; $i++) {
            DB::table('patients')->insert([
                'surname' => Str::random(10),
                'othername' => Str::random(10),
                'gender' => 'Male',
                'date_of_birth' => '2019-03-12',
                'email' => Str::random(10) . '@gmail.com',
                '_who_added' => '2',
            ]);
        }
    }
}
