<?php

namespace App\Http\Livewire;

use App\Http\Resources\API\SubdistrictResource;
use App\Models\Backoffice\Subdistrict;
use Livewire\Component;

class SubdistrictSearch extends Component
{
    public $selectedSubdistrict;
    public $componentClass;
    public $searchTerm = '';

    protected $listeners = ['subdistrictSelected'];

    public function subdistrictSelected($id)
    {
        if (is_null($id)) {
            $this->dispatchBrowserEvent('clear-subdistrict');
            return;
        }

        $subdistrict = Subdistrict::find($id);
        $this->selectedSubdistrict = $subdistrict;

        if ($subdistrict) {
            $this->dispatchBrowserEvent('set-subdistrict', new SubdistrictResource($subdistrict));
        }
    }

    public function render()
    {
        $subdistricts = Subdistrict::where('name', 'LIKE', "%$this->searchTerm%")->limit(10)->get();
        return view('livewire.subdistrict-search', [
            'subdistricts' => $subdistricts
        ]);
    }
}
