<?php

/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 11:20
 */
use Illuminate\Database\Seeder;
//use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory;

class CategorySeeder extends Seeder
{
    public function run(){
        if(env('DB_DRIVER') == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if(env('DB_DRIVER') == 'mysql')
        {
            DB::table('categories')->truncate();
        } else { //For PostgreSQL or anything else
            DB::statement("TRUNCATE TABLE categories CASCADE");
        }

        /** @var Faker\Generator $faker */
        $faker = Factory::create();

        for($i = 1; $i < 40; $i++){
            $parents = [
                0,
                intval($i/1),0,
                intval($i/2),0,
                intval($i/3),0,
                intval($i/4),0,
                intval($i/5),0,
                intval($i/6),0,
                intval($i/7),0,
                intval($i/8),0,
                intval($i/9),0,
                intval($i/10)
            ];
            $parent = $parents[rand(0,count($parents) - 1)];
            $new_category = [
                'id' => $i,
                'cat_title' => $faker->text(50),
                'cat_parent_id' => $parent,
                'cat_active' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];

            \App\Models\Category::create($new_category)->save();

            if($parent > 0){
                \App\Models\Category::find($parent)->update(['cat_has_child' => 1]);
            }
        }

    }
}