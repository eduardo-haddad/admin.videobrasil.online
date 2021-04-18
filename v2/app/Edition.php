<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Edition extends Model
{
    protected $table = 'videobrasil.vbo_edition';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title_pt', 'title_en', 'subtitle_pt', 'subtitle_en', 'main_preview_custom_title_pt', 'main_preview_custom_title_en', 'current', 'group_programs', 'bg_color', 'bg_img_desktop', 'bg_img_mobile', 'videos_to_show'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The roles that belong to the user.
     */
    public function seasonType()
    {
        return $this->belongsTo('App\SeasonType');
    }
    
    public function saibaMais()
    {
        return $this->hasOne('App\SaibaMais');
    }

}
