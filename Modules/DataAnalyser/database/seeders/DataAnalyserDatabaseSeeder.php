<?php

namespace Modules\DataAnalyser\Database\Seeders;

use Illuminate\Database\Seeder;

class DataAnalyserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DomainSeeder::class,
            KeywordSeeder::class,
        ]);
    }
}
