<?php

namespace Database\Seeders;

use App\Common\LastProduct;
use App\Enums\ActiveStatus;
use App\Enums\GroupRole;
use App\Enums\IsSalesStatus;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->addUsers();
        $this->addCustomers();
        $this->addProducts();
    }

    /**
     * addUsers
     *
     * @return void
     */
    public function addUsers()
    {
        DB::table('mst_users')->truncate();
        for ($i=1; $i<60; $i++) {
            User::create([
                'name'     => 'First Admin' . $i,
                'email'    => 'admin' . $i . '@gmail.com',
                'password' =>  Hash::make('Admin123@'),
                'is_active' => ActiveStatus::ACTIVE,
                'group_role' => GroupRole::ADMIN
            ]);
        }
    }

    /**
     * addCustomer
     *
     * @return void
     */
    public function addCustomers()
    {
        DB::table('mst_customers')->truncate();
        for ($i=1; $i<60; $i++) {
            Customer::create([
                'customer_name'  => 'Customer ' . $i,
                'email' => 'customer' . $i . '@gmail.com',
                'tel_num' => '09999999 ' . $i,
                'is_active' => ActiveStatus::ACTIVE,
                'address' => 'address ' . $i
            ]);
        }
    }

    /**
     * addProducts
     *
     * @return void
     */
    public function addProducts()
    {
        DB::table('mst_products')->truncate();
        Setting::where('key', LastProduct::KEY_LAST_PRODUCT_ID)->delete();
        Setting::create(['key' => LastProduct::KEY_LAST_PRODUCT_ID, 'value' => 1]);

        for ($i=1; $i<60; $i++) {
            Product::create([
                'product_id' => 'P' . str_pad(LastProduct::getLastProductId(), 9, '0', STR_PAD_LEFT),
                'product_name'  => 'Product ' . $i,
                'product_price' => rand(0,999999),
                'is_sales' => IsSalesStatus::ONE_SALE,
                'description' => 'description ' . $i
            ]);
        }
    }
}
