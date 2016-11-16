<?php

namespace App\Models;

use App\Models\Disease;
use App\Lib\Functions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ResearchAuthor extends Model {

    protected $table = 'research_authors';

    protected $fillable = array('uid', 'author_name');

}