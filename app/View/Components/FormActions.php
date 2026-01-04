<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormActions extends Component
{
    public string $label;
    public string $cancelLabel;
    public string $cancelRoute;

    public function __construct(
        string $label = 'Simpan',
        string $cancelLabel = 'Batal',
        string $cancelRoute = ''
    ) {
        $this->label = $label;
        $this->cancelLabel = $cancelLabel;
        $this->cancelRoute = $cancelRoute;
    }

    public function render()
    {
        return view('components.form-actions');
    }
}