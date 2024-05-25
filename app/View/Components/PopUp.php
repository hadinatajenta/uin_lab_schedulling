<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class PopUp extends Component
{
    /**
     * Create a new component instance.
     */

    public $buttonName;
    public $id;
    public $action;
    public function __construct(
        $buttonName,
        $id,
        $action
    ) {
        $this->buttonName = $buttonName;
        $this->id = $id;
        $this->action = $action;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.pop-up');
    }
}
