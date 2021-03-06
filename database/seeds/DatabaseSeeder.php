<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(MiscsTableSeeder::class);
         $this->call(OriginsTableSeeder::class);
         $this->call(StatesTableSeeder::class);
         $this->call(RoleTableSeeder::class);
         #  $this->call(DepartamentsTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         #  $this->call(TaskTableSeeder::class);
    }
}
