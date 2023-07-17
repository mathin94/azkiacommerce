<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ComingSoonPage extends Component
{
    public $title;

    public $launching_date;

    public $body_content;

    public $background_image;

    public function mount()
    {
        $this->title            = soon()->body_title;
        $this->launching_date   = soon()->launching_date;
        $this->body_content     = soon()->body_content;
        $this->background_image = soon()->backgroundImage();
    }

    public function render()
    {
        return view('livewire.coming-soon-page')
            ->layout('layouts.base', [
                'title' => $this->title
            ]);
    }
}
