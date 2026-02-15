<?php

namespace App\Livewire;

use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ConversionHistory extends Component
{
    use WithPagination;

    public function render()
    {
        $conversions = Conversion::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.conversion-history', [
            'conversions' => $conversions,
        ])->layout('layouts.app');
    }
}
