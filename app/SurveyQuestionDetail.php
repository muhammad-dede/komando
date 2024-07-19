<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestionDetail extends Model
{
    protected $table = 'survey_question_detail';
    protected $fillable = [
        'survey_question_id','liquids_id','liquid_peserta_id','feedback_id', 'answer','created_by','modified_by','created_at','updated_at'
    ];

    public function surveyQuestion()
    {
        return $this->belongsTo('App\SurveyQuestion');
    }

    public function feedback()
    {
        return $this->belongsTo('App\Models\Liquid\Feedback');
    }

    public function peserta()
    {
        return $this->belongsTo('App\Models\Liquid\LiquidPeserta', 'liquid_peserta_id');
    }
}
