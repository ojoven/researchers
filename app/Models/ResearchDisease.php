<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchDisease extends Model {

    protected $table = 'research_diseases';
    protected $fillable = array('uid', 'disease_id');

}