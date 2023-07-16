<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SharerCta extends Component
{
    public ?string $title;
    public ?string $url;
    public ?string $label;
    public ?string $class = 'social-icons justify-content-center mb-0';

    public function mount($title, $url, $class = null, ?string $label = '')
    {
        $this->title = $title;
        $this->url   = $url;
        $this->label = $label;

        if (!empty($class)) $this->class = $class;
    }

    public function render()
    {
        return view('livewire.sharer-cta');
    }
}
