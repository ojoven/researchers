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
        $diseases = $diseaseModel->getActiveDiseases();

        $pubMedAPIModel = new PubMedAPI();

        // For each disease, we get the list of available researches
        foreach ($diseases as $disease) {

            $researches = $pubMedAPIModel->addNewResearchesDisease($disease);

        }

    }

    /** GET RESEARCHES **/
    public function getResearchesCondition($conditionId) {

        $researchesCondition = ResearchCondition::where('condition_id', $conditionId);
        self::where('')->get();

    }

}