<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchCondition extends Model {

    protected $table = 'research_conditions';
    protected $fillable = array('uid', 'condition_id');

}