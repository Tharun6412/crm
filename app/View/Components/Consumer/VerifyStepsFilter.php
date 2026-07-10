<?php

namespace App\View\Components\Consumer;

use App\Models\Master\ConsumerVerificationStep;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VerifyStepsFilter extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $verify_steps = ConsumerVerificationStep::all();
        return view('components.consumer.verify-steps-filter',['verify_steps' => $verify_steps]);
    }
}
