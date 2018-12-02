<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Courier;

class CouriersController extends Controller
{
    public $navStatus;

    public function index()
    {
        $this->navStatus = ['', '', 'active', '', ''];
        $data = Courier::paginate(10);
        $view = view('couriers', ['items' => $data, 'navStatus' => $this->navStatus])->render();
        return (new Response($view));
    }
}
