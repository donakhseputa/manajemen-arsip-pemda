<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputTextareaForm extends Component
{
    public string $name;
    public string $label;
    public string $value;
    public bool $readonly;
    public bool $disabled;
    public bool $required;

    /**
     * Create a new component instance.
     *
     * @param string $name
     * @param string $label
     * @param string $value
     */
    public function __construct(string $name, string $label, string $value = '', $readonly = false, $disabled = false, $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.input-textarea-form');
    }
}
