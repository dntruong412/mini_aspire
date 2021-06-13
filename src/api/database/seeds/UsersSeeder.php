<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = new \DateTime();

        $users = [];
        for ($i=1; $i < 50; $i++) { 
            $users[] = [
                'name'       => "User $i",
                'created_at' => $date
            ];
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        DB::table('users')->insert($users);
    }
}
