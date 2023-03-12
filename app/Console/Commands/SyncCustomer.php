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
            ->whereNull('c.deleted_at')
            ->whereNotNull('c.code')
            ->whereNotNull('c.email')
            ->whereNotNull('c.password')
            ->orderBy('c.id', 'asc');

        $total_data = $pos_customers->count();
        $count      = 0;

        foreach ($pos_customers->get() as $customer) {
            $count += 1;

            $this->info("Processing Data {$count}/{$total_data}");

            $shop_customer = Customer::where('code', $customer->code)->first();

            if (!$shop_customer) {
                $created_customer = Customer::create([
                    'external_id'         => $customer->id,
                    'code'                => $customer->code,
                    'name'                => $customer->name,
                    'store_name'          => $customer->store_name,
                    'password'            => $customer->password,
                    'latitude'            => $customer->latitude,
                    'longitude'           => $customer->longitude,
                    'phone'               => $customer->phone,
                    'email'               => $customer->email,
                    'email_verified_at'   => $customer->email_verified_at,
                    'gender'              => $customer->gender,
                    'is_active'           => $customer->is_active,
                    'is_branch'           => $customer->is_branch,
                    'is_default_password' => $customer->is_default_password,
                    'created_at'          => $customer->created_at,
                ]);

                $this->info("Customer {$created_customer->name} Created");
            } else {
                $this->info("Customer {$customer->name} Already Exists on Database");
            }
        }
    }
}
