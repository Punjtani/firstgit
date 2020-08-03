<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
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
                'name' => 'Site Admin',
                'email' => 'siteadmin@admin.com',
                'password' => bcrypt("11111111"),
                "role_id" => 1,
            ],
            [
                'name' => 'Trainer',
                'email' => 'trainer@admin.com',
                'password' => bcrypt("11111111"),
                "role_id" => 2,
                "is_trainer" => 1,
            ],
            [
                'name' => 'Site User',
                'email' => 'user@admin.com',
                'password' => bcrypt("11111111"),
                "role_id" => 3,
            ],
        ];

        $users_group = [

            [
                'title' => 'Admin',
                "description" => "Super Admin Can Access Every information ",
            ],
            [
                'title' => 'moderator',
                "description" => "moderator Can Access Every information ",
            ],
            [
                'title' => 'uni_admin',
                "description" => "uni_admin Can Access Every information ",
            ],
            [
                'title' => 'uni_academic',
                "description" => "uni_academic Can Access Every information ",
            ],
            [
                'title' => 'uni_admission',
                "description" => "uni_admission Can Access Every information ",
            ],
            [
                'title' => 'uni_marketing',
                "description" => "uni_marketing Can Access Every information ",
            ],
            [
                'title' => 'uni_alumni',
                "description" => "uni_alumni Can Access Every information ",
            ],
            [
                'title' => 'uni_interns',
                "description" => "uni_interns Can Access Every information ",
            ], [
                'title' => 'uni_representative',
                "description" => "uni_representative Can Access Every information ",
            ],
            [
                'title' => 'school_admin',
                "description" => "school_admin Can Access Every information ",
            ],
            [
                'title' => 'school_representative',
                "description" => "school_representative Can Access Every information ",
            ],
            [
                'title' => 'school_counselor',
                "description" => "school_counselor Can Access Every information ",
            ],
            [
                'title' => 'student',
                "description" => "student Can Access Every information ",
            ],
            [
                'title' => 'company',
                "description" => "company Can Access Every information ",
            ],
            [
                'title' => 'embassy_admin',
                "description" => "embassy_admin Can Access Every information ",
            ],
            [
                'title' => 'embassy_representative',
                "description" => "embassy_representative Can Access Every information ",
            ],
            [
                'title' => 'training_center_admin',
                "description" => "training_center_admin Can Access Every information ",
            ],
            [
                'title' => 'training_center_represetative',
                "description" => "training_center_represetative Can Access Every information ",
            ],
            [
                'title' => 'agency_admin',
                "description" => "agency_admin Can Access Every information ",
            ],
            [
                'title' => 'agency_represetative',
                "description" => "agency_represetative Can Access Every information ",
            ],

            ];

        foreach ($users as $user){
            $userModel = \App\User::create($user);
        }
        foreach ($users_group as $user_group){
            $userModel = \App\UserGroupModel::create($user_group);
        }
    }
}
