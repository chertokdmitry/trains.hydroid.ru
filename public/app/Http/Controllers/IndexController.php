<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Schedule;
use Rakit\Validation\Validator;

class IndexController extends Controller
{
    public $html;
    public $navStatus;

    public function __construct()
    {
        $this->navStatus = ['active', '', '', '', ''];
    }

    public function index()
    {
        $data = [];
        $view = view('welcome', ['items' => $data, 'navStatus' => $this->navStatus])->render();

        return (new Response($view));
    }

    public function show(Request $request)
    {
        $data = $request->all();

        if ($this->checkForm($data, [
                'date1' => 'required',
                'date2' => 'required'
            ])) {

        $schedule = Schedule::where([
          ['date', '>=', $data['date1']],
          ['date', '<=', $data['date2']]
        ])->get();

        $view = view('schedule', ['items' => $schedule, 'navStatus' => $this->navStatus])->render();
        return (new Response($view));

        } else {
            $view = view('result', ['html' => $this->html, 'navStatus' => $this->navStatus])->render();
            return (new Response($view));
        }
    }

    private function checkForm($data, $fieldsToCheck)
    {
        $validator = new Validator;
        $validation = $validator->validate($data, $fieldsToCheck);

        if ($validation->fails()) {
            $errors = $validation->errors();
            $errs = $errors->firstOfAll();
            $this->html = 'Заполните все поля!';
            foreach($errs as $key=>$err) {
                $this->html .= $err;
                $this->html .= '<br>';
            }

            return false;
        } else {
            return true;
        }
    }
}
