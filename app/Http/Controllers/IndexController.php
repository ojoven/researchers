<?php

namespace App\Http\Controllers;

use App\Lib\Functions;
use App\Models\Research;
use App\Models\Result;
use App\Models\Turn;
use Illuminate\Http\Request;
use App\Http\Requests;

// Models

class IndexController extends Controller {

    public function index() {

        return view('index');

    }

    public function condition() {

        $apiKey = config('settings.ncbiApiKey');

        return view('index');
    }

}
