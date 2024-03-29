<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class UtilisateurTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Utilisateur::factory()->count(5)->create();
    }
}
