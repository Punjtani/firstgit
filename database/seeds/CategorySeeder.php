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
        $users = [

            [
                'name' => 'Art',
                "logo" => "5eff64070c756Group363@2x.png",

            ],[
                'name' => 'It&computing',
                "logo" => "5eff64315cc21Group354@2x.png",

            ],[
                'name' => 'health & fitness',
                "logo" => "5eff645353bb3Group350@2x.png",

            ],[
                'name' => 'Interior design',
                "logo" => "5eff649227f66Group355@2x.png",

            ],[
                'name' => 'Language',
                "logo" => "5eff64fddbb46Group360@2x.png",

            ],[
                'name' => 'Sport',
                "logo" => "5eff651671896Group350@2x.png",

            ],[
                'name' => 'Photography',
                "logo" => "5eff653314235Group349@2x.png",

            ],[
                'name' => 'Education',
                "logo" => "5eff656656d0aGroup358@2x.png",

            ],[
                'name' => 'Engineering',
                "logo" => "5eff65a9e1a80Group351@2x.png",

            ],[
                'name' => 'Cooking',
                "logo" => "5eff65a031434Group364@2x.png",

            ],[
                'name' => 'Sales',
                "logo" => "5eff65c135894Group352@2x.png",

            ],[
                'name' => 'Graphic design',
                "logo" => "5eff65d639238Group357@2x.png",

            ],[
                'name' => 'Marketing',
                "logo" => "5eff663520e86Group353@2x.png",

            ],[
                'name' => 'Finance',
                "logo" => "5eff661c69c1dGroup365@2x.png",

            ],[
                'name' => 'Customer service',
                "logo" => "5eff6604be1d3Group359@2x.png",

            ],[
                'name' => 'Media',
                "logo" => "5eff664b531bbGroup362@2x.png",

            ],[
                'name' => 'Law',
                "logo" => "5eff665d4bb69Group361@2x.png",

            ],[
                'name' => 'Management',
                "logo" => "5eff666f4e1a6Group356@2x.png",

            ],[
                'name' => 'Accountancy',
                "logo" => "5eff65f43d98aGroup348@2x.png",

            ],[
                'name' => 'Skills',
                "logo" => "5eff66805ee22Group347@2x.png",

            ],
    ];
        foreach ($users as $user){
            $userModel = \App\Models\Category::create($user);
        }
    }
}
