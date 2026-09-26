<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SuperAdminLayout extends Component
{
    public function __construct(
        public ?string $title = 'Super Admin'
    ) {}

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.superadmin');
    }
}
