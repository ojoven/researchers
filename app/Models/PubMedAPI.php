<?php

namespace App\Models;

use App\Models\Disease;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PubMedAPI extends Model {

    protected $urlBase = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json";
    protected $researchesPerPage = 1000;

    /** UPDATE RESEARCHES **/
    public function getNewResearchesDisease($disease) {

        $researchIds = $this->getResearchesDiseaseIds($disease);

    }

    public function getResearchesDiseaseIds($disease, $retstart = 0) {

        // URL to get the research IDs
        $urlBase = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json';

        // Get the JSON and convert it to an array
        $url = $urlBase . '&retstart=' . $retstart . '&retmax=' . $this->researchesPerPage . '&term=' . urlencode($disease);
        $jsonResponse = file_get_contents($url);
        $response = json_decode($jsonResponse, true);


        if (!isset($response['esearchresult'])) return array(); // Error, no response

        $researchIds = $response['esearchresult']['idlist'];

        // If there are more pages, we'll call to them
        if ($response['esearchresult']['count'] > ($retstart + $this->researchesPerPage)) {

            $retstart = $retstart + $this->researchesPerPage;

            $newPageResearchIds = $this->getResearchesDiseaseIds($disease, $retstart);
            $researchIds = array_merge($researchIds, $newPageResearchIds);

        }

        return $researchIds;

    }

}