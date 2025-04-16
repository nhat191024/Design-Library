<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Tag;
use App\Models\TagProduct;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $jsonFilePath = "./database/seeders/data.json";
        $jsonContent = file_get_contents($jsonFilePath);
        $dataArray = json_decode($jsonContent, true);

        foreach ($dataArray['users'] as $row) {
            User::create([
                "name" => $row['name'],
                "email" => $row['email'],
                "password" => Hash::make($row['password']),
                "role" => $row['role']
            ]);
        }

        foreach ($dataArray['categories'] as $row) {
            Category::create([
                "name" => $row['name'],
                "parent_id" => $row['parent_id'],
                "is_show" => $row['is_show']
            ]);
        }

        foreach ($dataArray['products'] as $row) {
            Product::create([
                "name" => $row['name'],
                "description" => $row['description'],
                "category_id" => $row['category_id'],
                "is_showcase" => $row['is_showcase'],
                "price" => $row['price'],
            ]);
        }

        foreach ($dataArray['images'] as $row) {
            Image::create([
                "product_id" => $row['product_id'],
                "url" => $row['url'],
            ]);
        }

        foreach ($dataArray['tags'] as $row) {
            Tag::create([
                "name" => $row['name'],
                "is_show" => $row['is_show']
            ]);
        }

        foreach ($dataArray['tag_products'] as $row) {
            TagProduct::create([
                "tag_id" => $row['tag_id'],
                "product_id" => $row['product_id']
            ]);
        }

    }
}
