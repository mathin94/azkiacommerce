<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Enums\OrderStatus;
use App\Models\Shop\Order;
use App\Models\BankAccount;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\BaseComponent;
use App\Jobs\RecalculateProductRatingJob;
use App\Services\Shop\CancelOrderService;
use App\Services\RajaOngkir\TrackWaybillService;
use App\Services\Shop\CompleteOrderService;
use App\Services\Shop\UploadPaymentProofService;
use Illuminate\Support\Facades\Log;

class OrderList extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $customer, $tab, $detail, $orderPayment, $bankAccounts, $selectedBankAccount, $manifests;

    public $bankAccountId, $file, $toCompleteId, $reviews = [];

    protected $queryString = ['tab'];

    public function mount()
    {
        $this->customer = auth()->guard('shop')->user();

        $this->bankAccounts = cache()->remember('all_bank_account', 24 * 60 * 60, function () {
            return BankAccount::with('bank')->get();
        });
    }

    public function show($id)
    {
        $this->detail = $this->getOrder($id);

        $this->emit('open-modal');
    }

    public function setTab($tab = null)
    {
        $this->tab = $tab;
    }

    public function showPayment($id)
    {
        $this->orderPayment = $this->getOrder($id);
        $this->reset(['bankAccountId', 'file']);
        $this->emit('open-payment-modal');
    }

    public function trackingPackage($id)
    {
        $this->detail = $this->getOrder($id);

        $service = new TrackWaybillService($this->detail);

        if (!$service->execute()) {
            $this->emit('showAlert', [
                "alert" => "
                        <div class=\"white-popup\">
                            <h5>Gagal</h5>
                            <p>Tidak dapat melakukan lacak paket</p>
                        </div>
                    "
            ]);

            return;
        }

        $this->manifests = $service->details();

        $this->emit('open-tracking-modal');
    }

    public function updatedBankAccountId()
    {
        $this->selectedBankAccount = $this->bankAccounts->find($this->bankAccountId);
    }

    public function updatedReviews()
    {
        // TODO: Implement updatedReviews() method.
    }


    public function savePayment()
    {
        $this->validate([
            'bankAccountId' => 'required|exists:App\Models\BankAccount,id',
            'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'bankAccountId.required' => 'Nomor rekening tujuan harus di isi',
            'bankAccountId.exists' => 'Nomor rekening tujuan tidak ditemukan',
        ]);

        $service = new UploadPaymentProofService(
            order: $this->orderPayment,
            bankAccount: $this->selectedBankAccount,
            file: $this->file
        );

        $service->perform();

        $this->reset(['bankAccountId', 'file']);

        $this->emit('close-payment-modal');

        $this->emit('showAlert', [
            "alert" => "
                        <div class=\"white-popup\">
                            <h5>Berhasil</h5>
                            <p>Bukti pembayaran berhasil di upload</p>
                        </div>
                    "
        ]);
    }

    public function openCancelDialog($id)
    {
        $this->detail = $this->getOrder($id);

        $this->dispatchBrowserEvent('open-cancel-dialog');
    }

    public function cancelOrder()
    {
        if ($this->detail && $this->detail->customer_cancelable) {
            $service = new CancelOrderService($this->detail);

            if (!$service->perform()) {
                $this->emit('showAlert', [
                    "alert" => "
                                <div class=\"white-popup\">
                                    <h5>Gagal !</h5>
                                    <p>Terjadi Kesalahan, pesanan gagal dibatalkan</p>
                                </div>
                            "
                ]);

                return false;
            }

            $this->emit('showAlert', [
                "alert" => "
                                <div class=\"white-popup\">
                                    <h5>Dibatalkan !</h5>
                                    <p>Pesanan Berhasil Dibatalkan</p>
                                </div>
                            "
            ]);

            $this->emit('close-cancel-dialog');
        }
    }

    private function getOrder($id): Order
    {
        return $this->customer->orders()
            ->with(['items.productVariant.media', 'shipping', 'payment'])
            ->findOrFail($id);
    }

    public function openCompleteDialog($id)
    {
        $this->toCompleteId = $id;
        $this->dispatchBrowserEvent('open-complete-dialog');
    }

    public function complete()
    {
        $service = new CompleteOrderService($this->toCompleteId);

        if (!$service->execute()) {
            $msg = $service->getErrorMessage();
            $this->emit('showAlert', [
                "alert" => "
                        <div class=\"white-popup\">
                            <h5>Gagal !</h5>
                            <p>$msg</p>
                        </div>
                    "
            ]);
        }

        $this->emit('showAlert', [
            "alert" => "
                <div class=\"white-popup\">
                    <h5>Sukses !</h5>
                    <p>Pesanan Berhasil Diselesaikan</p>
                </div>
            "
        ]);
    }

    public function openReviewModal($id)
    {
        $this->detail = $this->getOrder($id);

        $this->emit('open-review-modal');
    }

    public function saveReview($id)
    {
        $item = $this->detail->items()->findOrFail($id);

        $review = $this->reviews[$id] ?? [];

        if (!blank($review)) {
            if (!array_key_exists('rating', $review)) {
                $this->emit('showAlert', [
                    "alert" => "
                        <div class=\"white-popup\">
                            <h5>Gagal !</h5>
                            <p>Rating tidak boleh kosong</p>
                        </div>
                    "
                ]);
                return;
            }

            $item->review()->updateOrCreate([
                'product_name'            => $item->name,
                'shop_product_variant_id' => $item->shop_product_variant_id,
                'shop_customer_id'        => $this->customer->id,
                'review'                  => array_key_exists('review', $review) ? $review['review'] : '',
                'rating'                  => array_key_exists('rating', $review) ? $review['rating'] : null
            ]);

            RecalculateProductRatingJob::dispatch($item->productVariant->shop_product_id);

            $this->detail->refresh();
        }
    }

    public function render()
    {
        $orders = $this->customer
            ->orders()
            ->with('items.productVariant.media')
            ->filterByStatus($this->tab);

        return view('livewire.account.order-list', [
            'orders' => $orders->latest()->paginate(10)
        ])->layout('layouts.dashboard', [
            'title' => 'Daftar Pesanan'
        ]);
    }
}
