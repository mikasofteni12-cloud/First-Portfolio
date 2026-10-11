<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'IT & Software',
            'Hospitality & Food',
            'Healthcare & Medical',
            'Childcare & Nanny',
            'Education & Teaching',
            'Customer Support',
            'Retail & Sales',
            'Administrative & Office',
            'Creative & Design',
            'Labor & Logistics',
            'Construction',
            'Other',
        ];

        foreach ($categories as $category){
            Category::firstOrCreate(['name' => $category]);
        }
    }
}
