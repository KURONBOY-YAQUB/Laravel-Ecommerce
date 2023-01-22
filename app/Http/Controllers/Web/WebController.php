<?php

namespace App\Http\Controllers\Web;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('status', '0')->get();
        return view('web.index', compact('sliders'));
    }
}
