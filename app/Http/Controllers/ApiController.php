<?php

namespace App\Http\Controllers;

use App\Models\Research;
use Illuminate\Http\Request;

use App\Http\Requests;

class ApiController extends Controller {

	public function updateResearches() {

		$researchModel = new Research();
		$researchModel->updateResearches();
	}

}
