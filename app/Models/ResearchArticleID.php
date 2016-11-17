<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchArticleID extends Model {

    protected $table = 'research_articleids';
    protected $fillable = array('uid', 'idtype', 'idtypen', 'value');

}