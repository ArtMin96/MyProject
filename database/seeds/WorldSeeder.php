<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class WorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries_query = "./database/seeds/World/countries.sql";
        DB::unprepared(file_get_contents($countries_query));
        $this->command->info('Country table seeded!');

        $states_query = "./database/seeds/World/states.sql";
        DB::unprepared(file_get_contents($states_query));
        $this->command->info('States table seeded!');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $cities_query = "./database/seeds/World/cities.sql";
        DB::unprepared(file_get_contents($cities_query));
        $this->command->info('City table seeded!');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
