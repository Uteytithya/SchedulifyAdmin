<?php


namespace App\View\Components;
use Illuminate\View\Component;

class Toast extends Component
{
    public $message;
    public $type;
    public $color;
    public $position;

    public function __construct($message, $type = 'success', $color = 'white', $position = 'bottom-end')
    {
        $this->message = $message;
        $this->type = $type;
        $this->color = $color;
        $this->position = $position;
    }

    public function render()
    {
        return view('components.toast');
    }
}

