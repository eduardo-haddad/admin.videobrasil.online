<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoProgram extends Model
{
    protected $table = 'videobrasil.vbo_video_program';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title_pt', 'title_en', 'category_pt', 'category_en', 'poster_pt', 'poster_en', 'thumb_pt', 'thumb_en', 'main_video', 'edition_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];


    public function edition()
    {
        return $this->belongsTo('App\Edition');
    }
    
    public function videos()
    {
        return $this->hasMany('App\Video');
    }

}
