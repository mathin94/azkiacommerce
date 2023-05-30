<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Shop\Customer;

class SyncCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Customer Data Between POS and Shop';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $pos_customers = DB::connection('mysql_pos')
            ->table('customers as c')
            ->join('customer_types as ct', 'c.customer_type_id', '=', 'ct.id')
            ->whereNull('c.deleted_at')
            ->whereNotNull('c.code')
            ->whereNotNull('c.email')
            ->whereNotNull('c.password')
            ->select(['c.*', 'ct.name as customer_type_name', 'ct.discount', 'ct.discount_extra', 'ct.min_order', 'ct.enable_reward'])
            ->orderBy('c.id', 'asc');

        $total_data = $pos_customers->count();
        $count      = 0;

        foreach ($pos_customers->get() as $customer) {
            $count += 1;

            $this->info("Processing Data {$count}/{$total_data}");

            $created_customer = Customer::firstOrNew(['resource_id' => $customer->id]);

            $created_customer->customer_type_id    = $customer->customer_type_id;
            $created_customer->code                = $customer->code;
            $created_customer->name                = $customer->name;
            $created_customer->store_name          = $customer->store_name;
            $created_customer->password            = $customer->password;
            $created_customer->latitude            = $customer->latitude;
            $created_customer->longitude           = $customer->longitude;
            $created_customer->phone               = $customer->phone;
            $created_customer->email               = $customer->email;
            $created_customer->email_verified_at   = $customer->email_verified_at;
            $created_customer->gender              = $customer->gender;
            $created_customer->is_active           = $customer->is_active;
            $created_customer->is_branch           = $customer->is_branch;
            $created_customer->is_default_password = $customer->is_default_password;
            $created_customer->created_at          = $customer->created_at;
            $created_customer->customer_type  = [
                'id'             => $customer->customer_type_id,
                'name'           => $customer->customer_type_name,
                'discount'       => $customer->discount,
                'discount_extra' => $customer->discount_extra,
                'min_order'      => $customer->min_order,
                'discount_extra' => $customer->discount_extra,
            ];

            $created_customer->save();

            $this->info("Customer {$created_customer->name} Created");
        }
    }
}
