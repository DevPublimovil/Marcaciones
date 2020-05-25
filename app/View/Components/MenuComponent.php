<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\User;
use Auth;

class MenuComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $user = User::find(Auth::id());
        return view('components.menu-component', compact('user'));
    }
}
