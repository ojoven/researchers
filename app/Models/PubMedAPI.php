<?php

namespace App\Models;

use App\Models\Research;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PubMedAPI extends Model {

    protected $urlBase = "https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json";
    protected $researchesPerPage = 1000;
    protected $researchInfoChunks = 100;

    /** ADD NEW RESEARCHES **/
    public function addNewResearchesCondition($condition) {

        $conditionName = $condition['name'];

        // Get all the research IDs from the API
        $researchIds = $this->getResearchesConditionIds($conditionName);

        // Get the previous researches to compare with
        $researchModel = new Research();
        $previousResearches = $researchModel->whereIn('uid', $researchIds)->get()->toArray();
        $previousResearchIds = Functions::createSubArrayFromIndex($previousResearches, 'uid');

        // Extract the new ones (all - previous)
        $newResearchIds = array_diff($researchIds, $previousResearchIds);

        // Get new researches info
        $researches = $this->getResearchesInfoFromIds($newResearchIds);

        // Add researches into DB
        $response = $this->addResearchesInfoToDB($researches, $condition);

        return $response;

    }

    /** GET RESEARCH IDS **/
    public function getResearchesConditionIds($condition, $retstart = 0) {

        // URL to get the research IDs
        $urlBase = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&retmode=json';

        // Get the JSON and convert it to an array
        $url = $urlBase . '&retstart=' . $retstart . '&retmax=' . $this->researchesPerPage . '&term=' . urlencode($condition);
        $url .= '&api_key=' . config('settings.ncbiApiKey');
        $jsonResponse = Functions::getFileContentsRetryIfFail($url);
        $response = json_decode($jsonResponse, true);

        if (!isset($response['esearchresult'])) return array(); // Error, no response

        // Get the research IDs from the response
        $researchIds = $response['esearchresult']['idlist'];

        // If there are more pages, we'll call to them
        if ($response['esearchresult']['count'] > ($retstart + $this->researchesPerPage)) {

            $retstart = $retstart + $this->researchesPerPage;

            $newPageResearchIds = $this->getResearchesConditionIds($condition, $retstart);
            $researchIds = array_merge($researchIds, $newPageResearchIds);

            return $researchIds;

        }

        return $researchIds;

    }

    /** GET RESEARCHES INFO FROM IDS **/
    public function getResearchesInfoFromIds($researchIds) {

        $researchChunks = array_chunk($researchIds, $this->researchInfoChunks);

        $allResearches = array();

        foreach ($researchChunks as $chunk) {

            // URL to get the research IDs
            $urlBase = 'https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&retmode=json&rettype=abstract&id=';

            // Get the JSON and convert it to an array
            $url = $urlBase . implode(',', $chunk);
            $url .= '&api_key=' . config('settings.ncbiApiKey');
            $jsonResponse = Functions::getFileContentsRetryIfFail($url);
            $response = json_decode($jsonResponse, true);

            if (!isset($response['result'])) return array(); // Error, no response

            $researches = $response['result'];
            unset($researches['uids']);

            $allResearches = array_merge($allResearches, $researches);

            return $allResearches;
        }

        return $allResearches;

    }

    /** ADD RESEARCHES INFO TO DB **/
    public function addResearchesInfoToDB($researches, $condition) {

        $researchModel = new Research();
        foreach ($researches as $research) {

            // Research model DB
            $arrayResearch = array(
                'uid' => $research['uid'],
                'pubdate' => $research['pubdate'],
                'epubdate' => $research['epubdate'],
                'source' => $research['source'],
                'lastauthor' => $research['lastauthor'],
                'title' => $research['title'],
                'sorttitle' => $research['sorttitle'],
                'volume' => $research['volume'],
                'issue' => $research['issue'],
                'pages' => $research['pages'],
                'lang' => is_array($research['lang']) ? implode(',', $research['lang']) : '',
                'nlmuniqueid' => $research['nlmuniqueid'],
                'issn' => $research['issn'],
                'essn' => $research['essn'],
                'pubtype' => is_array($research['pubtype']) ? implode(',', $research['pubtype']) : '',
                'recordstatus' => $research['recordstatus'],
                'pubstatus' => $research['pubstatus'],
                'attributes' => is_array($research['attributes']) ? implode(',', $research['attributes']) : '',
                'pmcrefcount' => $research['pmcrefcount'],
                'fulljournalname' => $research['fulljournalname'],
                'elocationid' => $research['elocationid'],
                'doctype' => $research['doctype'],
                'booktitle' => $research['booktitle'],
                'medium' => $research['medium'],
                'edition' => $research['edition'],
                'publisherlocation' => $research['publisherlocation'],
                'publishername' => $research['publishername'],
                'srcdate' => $research['srcdate'],
                'reportnumber' => $research['reportnumber'],
                'availablefromurl' => $research['availablefromurl'],
                'locationlabel' => $research['locationlabel'],
                'docdate' => $research['docdate'],
                'bookname' => $research['bookname'],
                'chapter' => $research['chapter'],
                'sortpubdate' => $research['sortpubdate'],
                'sortfirstauthor' => $research['sortfirstauthor'],
                'vernaculartitle' => $research['vernaculartitle'],
            );

            // Add research to DB
            $researchModel->create($arrayResearch);

            // Add research Authors
            $researchAuthorModel = new ResearchAuthor();
            foreach ($research['authors'] as $author) {

                $researchAuthorArray = array(
                    'uid' => $research['uid'],
                    'author_name' => $author['name'],
                );

                $researchAuthorModel->create($researchAuthorArray);
            }

            // Save relationship between research and condition
            $researchConditionModel = new ResearchCondition();
            $researchConditionArray = array(
                'uid' => $research['uid'],
                'condition_id' => $condition['id'],
            );

            $researchConditionModel->create($researchConditionArray);

            // Save relationship between research and articleids
            $researchArticleIDModel = new ResearchArticleID();
            foreach ($research['articleids'] as $articleID) {
                $researchConditionArray = array(
                    'uid' => $research['uid'],
                    'idtype' => $articleID['idtype'],
                    'idtypen' => $articleID['idtypen'],
                    'value' => $articleID['value']
                );

                $researchArticleIDModel->create($researchConditionArray);
            }


            // TODO: Save abstracts! and author additional information!

        }

    }

}