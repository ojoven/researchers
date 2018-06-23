<?php

namespace App\Models;

use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Condition extends Model {

    protected $fillable = array('name', 'wikipedia_url');

    /** GET LIST CONDITIONS **/
    public function getListConditions() {

        // Get the list of conditions
        $conditions = self::get()->toArray();
        return $conditions;

    }

    public function getActiveConditions() {

        // Get the list of conditions
        $conditions = self::where('active', true)->get()->toArray();
        return $conditions;

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

                foreach ($html->find('#mw-content-text > ul li a') as $linkCondition) {

                    $linkConditionUrl = $linkCondition->getAttribute('href');

                    $hjey = strpos($linkConditionUrl, '#');

                    if (strpos($linkConditionUrl, 'redlink') === false // Conditions without page in Wikipedia
                        && strpos($linkConditionUrl, '#') === false // Indexes
                        && strpos($linkConditionUrl, 'List_of_diseases') === false) { // Other category pages
                        echo $urlBase . $linkConditionUrl . '<br>';

                        self::create(array(
                            'name' => $linkCondition->plaintext,
                            'wikipedia_url' => $urlBase . $linkConditionUrl
                        ));
                    }

                }

            }

        }

        // Return the conditions list from DB
        return self::get()->toArray();

    }

}