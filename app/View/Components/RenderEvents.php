<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RenderEvents extends Component
{


    public $events;

//    TODO pasar categorias de deportes con id y su nombre

    public $categorias;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($events)
    {
        $this->events = $events;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.render-events');
    }
}
