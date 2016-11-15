<?php

namespace App\Models;

use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Disease extends Model {

    /** GET LIST ILLNESSES **/
    public function getListDiseases() {

        // Get the list of diseases
        $diseases = $this->getListDiseasesFromWikipedia();


    }

    /** SCRAPE FROM WIKIPEDIA **/
    public function getListDiseasesFromWikipedia() {

        require_once(app_path() . '/Lib/simple_html_dom.php');
        $urlIndex = 'https://en.wikipedia.org/wiki/Category:Lists_of_diseases';
        $urlBase = 'https://en.wikipedia.org';

        $html = file_get_html($urlIndex);
        foreach ($html->find('.mw-category-group li a') as $link) {

            // Let's find the alphabetical pages
            $linkTitle = $link->plaintext;

            if (strpos($linkTitle, 'List of diseases (') !== false && $linkTitle !== 'List of diseases (0–9)') {
                $urlPage = $urlBase . $link->getAttribute('href');

                // Alphabetical pages
                $html = file_get_html($urlPage);

                foreach ($html->find('#mw-content-text > ul li a') as $linkDisease) {

                    $linkDiseaseUrl = $linkDisease->getAttribute('href');

                    echo $linkDiseaseUrl . '<br>';

                }

            }

            return;
        }

    }

}