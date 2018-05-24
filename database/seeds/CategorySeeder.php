<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories =  factory(\App\Category::class, rand(1, 5))->create()->each(function ($category) {
            $category->products()->saveMany(factory(App\Product::class, rand(1, 10))->make());
        });

        foreach ($categories as $category) {
            $subcategories = factory(\App\Category::class, rand(1, 5))->create();

            foreach ($subcategories as $subcategory) {
                $subcategory->products()->saveMany(factory(\App\Product::class, rand(1,10))->make());
                $subcategory->makeChildOf($category);
            }
        }
    }
}
