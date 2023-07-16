<?php

namespace App\Http\Livewire;

use App\Models\FAQ;
use Livewire\Component;

class FAQPage extends Component
{
    public function render()
    {
        $faqs = FAQ::orderBy('index', 'asc')->get();

        return view('livewire.faqs-page', [
            'faqs' => $faqs
        ])
            ->layout('layouts.frontpage', [
                'title' => 'FAQ'
            ]);
    }
}
