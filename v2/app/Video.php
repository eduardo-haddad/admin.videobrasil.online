<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videobrasil.vbo_video';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'vimeo_id', 'title_pt', 'title_en', 'subtitle_pt', 'subtitle_en', 'main_preview_html_pt', 'main_preview_html_en', 'title_box_pt', 'title_box_en', 'poster_pt', 'poster_en', 'thumb_pt', 'thumb_en', 'category_pt', 'category_en', 'specs_pt', 'specs_en', 'caption_pt', 'caption_en', 'edition_id', 'video_program_id', 'order'
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
    
    public function videoProgram()
    {
        return $this->belongsTo('App\VideoProgram');
    }

}
