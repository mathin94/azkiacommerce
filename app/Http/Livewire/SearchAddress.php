<?php

namespace App\Http\Livewire;

use Livewire\WithPagination;

class SearchAddress extends BaseComponent
{
    use WithPagination;

    public $paginationTheme = 'bootstrap';

    public $search;

    public int | null $selectedId;

    public $listeners = ['open-address-modal' => 'openAddressModal'];

    public function openAddressModal()
    {
        $this->reset('search');
    }

    public function select($id)
    {
        $this->selectedId = $id;
        $this->emit('select-address', $id);
    }

    public function render()
    {
        $addresses = $this->customer->addresses()->orderBy('is_main', 'desc');

        if ($this->search) {
            $addresses = $addresses->search($this->search);
        }

        return view('livewire.search-address', [
            'addresses' => $addresses->paginate(10)
        ]);
    }
}
