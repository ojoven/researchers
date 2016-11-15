<?php

namespace App\Http\Controllers;

use App\Lib\Functions;
use App\Models\Result;
use App\Models\Turn;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class IndexController extends Controller {

    public function index() {

        return view('index');

    }

    public function generateturn() {

        $params = array();
        $turnModel = new Turn();
        $turn = $turnModel->generateTurn($params);

        return response()->json($turn);
    }

    public function validateturn() {

    }

}
