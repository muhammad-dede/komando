<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    protected $table = 'survey_question';
    protected $fillable = [
        'question','status','created_by','modified_by','created_at','updated_at'
    ];

    /**
     * Get the details for the survey question.
     */
    public function details()
    {
        return $this->hasMany('App\SurveyQuestionDetail');
    }
}
