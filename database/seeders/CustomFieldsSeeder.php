<?php

namespace Database\Seeders;

use App\Models\CustomField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomFieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomField::create([
            'name' => 'Company Name',
            'type' => 'text',
            'options'=> null,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
