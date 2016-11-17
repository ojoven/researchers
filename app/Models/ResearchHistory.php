<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchHistory extends Model {

    protected $table = 'research_history';
    protected $fillable = array('uid', 'author_name');

}