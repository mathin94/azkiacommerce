<?php

namespace App\Services\Shop;

use App\Models\Shop\Order;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Facades\Invoice;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class InvoicePdfGeneratorService
{
    private $invoiceItems = [];
    private $seller;
    private $customer;

    public function __construct(
        public Order $order
    ) {
        $this->order->load(['items', 'shipping', 'payment', 'customer']);

        $this->buildSeller();
    }

    public function generate()
    {
        if (empty($this->order)) {
            return false;
        }

        $this->invoiceItems = [];

        $this->buildInvoiceItems();
        $this->buildCustomer();

        $invoice = Invoice::make('invoice')
            ->status($this->order->status->description)
            ->series($this->order->number)
            ->seller($this->seller)
            ->buyer($this->customer)
            ->date($this->order->created_at)
            ->shipping($this->order->shipping_cost)
            ->dateFormat('d/m/Y')
            ->payUntilDays(1)
            ->currencySymbol('Rp')
            ->currencyCode('Rupiah')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename(series_to_filename($this->order->number))
            ->addItems($this->invoiceItems)
            ->logo(public_path('static/main-logo.png'))
            ->save('public');

        $invoice->url();

        return $invoice;
    }

    private function buildInvoiceItems()
    {
        $this->order->items->each(function ($item) {
            $this->invoiceItems[] = (new InvoiceItem())
                ->title($item->name)
                ->description($item->alternate_name)
                ->pricePerUnit($item->normal_price)
                ->quantity($item->quantity)
                ->discount($item->normal_price - $item->price);
        });
    }

    private function buildSeller()
    {
        $outlet = store_outlet();
        $store_address = store_full_address();

        $this->seller = new Party([
            'name'    => $outlet->name,
            'phone'   => $outlet->phone,
            'address' => $store_address
        ]);
    }

    private function buildCustomer()
    {
        $this->customer = new Party([
            'name' => $this->order->customer->name,
            'phone' => $this->order->customer->phone,
            'address' => $this->order->customer->full_main_address
        ]);
    }
}
