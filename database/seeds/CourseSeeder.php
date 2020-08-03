<?php

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [

            [
                'name' => 'example2',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "duration"=> "4500",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 1,

            ],[
                'name' => 'example17',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "duration"=> "4500",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 1,

            ],[
                'name' => 'example16',
                "duration"=> "4500",
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 1,

            ],[
                'name' => 'example14',
                "duration"=> "4500",
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 1,

            ],[
                'name' => 'example15',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "duration"=> "4500",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 1,

            ],[
                'name' => 'example14',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "duration"=> "4500",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 2,

            ],[
                "duration"=> "4500",
                'name' => 'example132',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 2,

            ],[
                'name' => 'example13',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "duration"=> "4500",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 2,

            ],[
                'name' => 'example14',
                "duration"=> "4500",
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 2,

            ],[
                'name' => 'example19',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "duration"=> "4500",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "user_id"=> 2,
                "category_id"=> 3,

            ],[
                'name' => 'example14',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "duration"=> "4500",
                "user_id"=> 2,
                "category_id"=> 3,

            ],[
                'name' => 'example10',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "duration"=> "4500",

                "user_id"=> 2,
                "category_id"=> 3,

            ],[
                'name' => 'example11',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "duration"=> "4500",

                "user_id"=> 2,
                "category_id"=> 3,

            ],[
                'name' => 'example12',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "duration"=> "4500",

                "user_id"=> 2,
                "category_id"=> 4,

            ],[
                'name' => 'example1',
                "thumbnail" => "5ef9c4df329fbScreenshot2020-06-29at3.05.08PM.png",
                "description"=> "best course to learn!",
                "price"=> "4500",
                "duration"=> "4500",
                "user_id"=> 2,
                "category_id"=> 4,

            ],
            ];
        foreach ($users as $user){
            $userModel = \App\Models\Course::create($user);
        }
    }
}
