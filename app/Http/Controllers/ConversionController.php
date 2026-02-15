<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use Illuminate\Support\Facades\Auth;

class ConversionController extends Controller
{
    public function download(Conversion $conversion)
    {
        if ($conversion->user_id !== Auth::id()) {
            abort(403);
        }

        if ($conversion->status !== 'completed' || !file_exists($conversion->output_path)) {
            abort(404, 'Converted file not found.');
        }

        return response()->download($conversion->output_path);
    }
}
