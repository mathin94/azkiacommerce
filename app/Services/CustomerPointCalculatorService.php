<?php

namespace App\Services;

use App\Models\Shop\Customer;
use Illuminate\Support\Facades\DB;

class  CustomerPointCalculatorService
{
    private const CENTRAL_OUTLET_ID = 1;
    private const DISTRIBUTOR_PERPOINT = 150000;
    private const AGEN_RESELLER_PERPOINT = 100000;

    private int $divider = 0;

    public string $customer_type;
    public $sales;
    public float $total_amount;
    public float $total_point;

    public function __construct(
        private Customer $customer,
        private Int $year
    ) {
        $this->customer_type = $this->customer->customer_type['name'];
    }

    private function getTotalAmount(): void
    {
        $this->sales = DB::connection('mysql_pos')
            ->table('sales')
            ->where('sales.customer_id', $this->customer->resource_id)
            ->whereNull('sales.deleted_at')
            ->whereRaw("YEAR(sales.paid_date) = $this->year")
            ->where('sales.is_preorder', 0)
            ->where('sales.outlet_id', self::CENTRAL_OUTLET_ID);

        $this->total_amount = $this->sales->sum('total_amount') - $this->sales->sum('discount');
    }

    public function calculate(): Bool
    {
        $this->getTotalAmount();

        if ($this->customer->is_distributor) {
            $this->divider = self::DISTRIBUTOR_PERPOINT;
        }

        if ($this->customer->is_agen || $this->customer->is_reseller) {
            $this->divider = self::AGEN_RESELLER_PERPOINT;
        }

        if ($this->divider == 0) {
            $this->total_point = 0;
            return true;
        }

        $this->total_point = (float) $this->total_amount / $this->divider;
        return true;
    }
}
