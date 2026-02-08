<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vinywaji = Category::create(['name' => 'Vinywaji Moto']);
        $vitafunwa = Category::create(['name'=> 'Vitafunwa']);

        MenuItem::create([
            'category_id'=> $vinywaji->id,
            'name'=>'Kahawa ya Maziwa (Latte)',
            'description'=>'Kahawa ya kusaga iliyochanganywa na maziwa ya moto.',
            'price'=> 5000,
        ]);

        MenuItem::create([
            'category_id' => $vinywaji->id,
            'name' => 'Chai ya Rangi',
            'description'=>'Chai asilia yenye harufu nzuri ya viungo',
            'price'=> 2000,
        ]);

        MenuItem::create([
            'category_id' => $vitafunwa->id,
            'name' => 'Mandazi',
            'description' => 'Mandazi laini ya kukaanga.',
            'price' => 1000,
        ]);

        MenuItem::create([
            'category_id' => $vitafunwa->id,
            'name' => 'Samosa ya Nyama',
            'description' => 'Samosa yenye ladha nzuri ya nyama ya ng’ombe.',
            'price' => 1500,
        ]);
    }
}
