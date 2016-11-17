<?php

namespace App\Models;

use App\Models\Disease;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Research extends Model {

    protected $fillable = array('uid', 'pubdate', 'epubdate', 'lastauthor', 'title', 'sorttitle', 'volume', 'issue', 'pages', 'lang', 'nlmuniqueid', 'issn', 'essn',
        'pubtype', 'recordstatus', 'pubstatus', 'attributes', 'pmcrefcount', 'fulljournalname', 'elocationid', 'doctype', 'booktitle', 'medium', 'edition',
        'publisherlocation', 'publishername', 'srcdate', 'reportnumber', 'availablefromurl', 'locationlabel', 'docdate', 'bookname', 'chapter', 'sortpubdate',
        'sortfirstauthor', 'vernaculartitle');

    /** UPDATE RESEARCHES **/
    public function updateResearches() {

        // Get the list of diseases
        $diseaseModel = new Disease();
        $diseases = $diseaseModel->getListDiseases();

        $disease['name'] = 'Acanthosis nigricans';
        $disease['id'] = 21;

        $pubMedAPIModel = new PubMedAPI();
        $researches = $pubMedAPIModel->addNewResearchesDisease($disease);

        // For each disease, we get the list of available researches
        foreach ($diseases as $disease) {

            // Call to API

        }

        // For each element in the list

    }

}