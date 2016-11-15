<?php

namespace App\Models;

use App\Models\Disease;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Research extends Model {

    /** UPDATE RESEARCHES **/
    public function updateResearches() {

        // Get the list of diseases
        $diseaseModel = new Disease();
        $diseases = $diseaseModel->getListDiseases();

        // For each disease, we get the list of available researches
        foreach ($diseases as $disease) {

            // Call to API

        }

        // For each element in the list

    }

}