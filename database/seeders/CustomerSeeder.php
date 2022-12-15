<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    var $arrPopCode = ['BV', 'LM', 'HB', 'SN',  'GN', 'XM', 'LD', 'LX',  'JP', 'JT', 'LJ'];

    public function run()
    {
        $pkg = DB::table('t_service_pkg')->where('sp_recycle', 2)->get();
        $susunPkg  = [];
        foreach ($pkg as $key => $value) {
            $susunPkg[$key] = $value->sp_name;
        }

        $faker = Faker::create('id_ID');
        $testArray = [];
        for ($i = 0; $i < 50; $i++) {
            
            $address = $faker->address;
            $phoneNumber = $faker->phoneNumber('-####');
            $email = $faker->email;
            $prefix = $faker->randomElement($this->arrPopCode);
            $custNumber = $prefix . sprintf('%06d', $faker->numberBetween(100, 1000), +1);

            $custData =
                [
                    'cust_number' => $custNumber,
                    'cust_name' => $faker->name, 
                    'cust_address' => $address,
                    'cust_phone' => $phoneNumber,
                    'cust_email' => $email,
                    'cust_bill_address' => $address,
                    'cust_bill_phone' => $phoneNumber,
                    'cust_bill_email' => $faker->email
                ];

            $custPkgData = [
                'cust_number' => $custNumber,
                'sp_code' => $faker->randomElement($susunPkg),
                'cupkg_svc_begin' => $faker->date(),
                'cupkg_acc_type' => 2,
                'cupkg_status' => $faker->randomElement(range(1, 10)),
                'cupkg_acct_manager' => $faker->name,
            ];

            DB::table('t_customer')->insert($custData);
            DB::table('trel_cust_pkg')->insert($custPkgData);
        }
    }
}
