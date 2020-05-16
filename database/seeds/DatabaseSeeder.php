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
         $this->call(AddressSeeder::class);
         $this->call(CategorySeeder::class);
        $this->call(ProducerSeeder::class);
        $this->call(PromotionSeeder::class);
        $this->call(PromotionProductSeeder::class);
    }
}
