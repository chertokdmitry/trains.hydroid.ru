<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Region;

class RegionsController extends Controller
{
    public $navStatus;

    public function index()
    {
        $this->navStatus = ['', '', '', 'active', ''];
        $data = Region::all();
        $view = view('regions', ['items' => $data, 'navStatus' => $this->navStatus])->render();
        return (new Response($view));
    }
}
