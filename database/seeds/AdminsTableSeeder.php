<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $tbl_admins = array(
            array('id' => '1', 
                'name' => 'Super ', 
                'email' => 'super.admin@gmail.com', 
                'password' => '$2y$10$3dnvwApU2rMT.HB47O7TkOxMwqlOfQSs2.sQT2KEnQJ1PYtc4jhtq', 
                'email_verified_at' => null, 
                'remember_token' => null, 
                'created_at' => '2020-05-23 05:58:50', 
                'updated_at' => '2020-05-23 05:58:50', 
                'deleted_at' => null
            ),
        );
        DB::table('users')->insert($tbl_admins);
    }

}
