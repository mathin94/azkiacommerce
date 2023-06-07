<?php

namespace Database\Seeders;

use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'bank_code' => '002', // BRI
                'account_name' => 'Siti Musyarrofah Wahdah',
                'account_number' => '346401003702507',
                'branch' => 'KCP Cisaat'
            ],
            [
                'bank_code' => '008', // Mandiri
                'account_name' => 'Siti Musyarrofah Wahdah',
                'account_number' => '1820000714469',
                'branch' => 'KCP Cisaat'
            ],
            [
                'bank_code' => '014', // BCA
                'account_name' => 'Siti Musyarrofah Wahdah',
                'account_number' => '0380476461',
                'branch' => 'KCP Cisaat'
            ],
        ];

        foreach ($accounts as $key => $account) {
            $bank = Bank::whereCode($account['bank_code'])->first();

            if (empty($bank)) {
                continue;
            }

            BankAccount::updateOrCreate([
                'bank_id' => $bank->id,
                'account_number' => $account['account_number']
            ], [
                'account_name' => $account['account_name'],
                'branch' => $account['branch'],
            ]);
        }
    }
}
