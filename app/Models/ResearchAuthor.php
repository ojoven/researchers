<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchAuthor extends Model {

    protected $table = 'research_authors';
    protected $fillable = array('uid', 'author_name');

}