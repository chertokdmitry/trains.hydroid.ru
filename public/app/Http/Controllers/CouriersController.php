<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Courier;

class CouriersController extends Controller
{
    public function index()
    {
        $data = Courier::all();
        $view = view('couriers', ['items' => $data])->render();
        return (new Response($view));
    }
}
