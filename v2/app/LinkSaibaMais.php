<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkSaibaMais extends Model
{
    protected $table = 'videobrasil.vbo_links_saibamais';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'title_pt', 'title_en', 'url_pt', 'url_en', 'blank', 'download', 'text_replacement', 'saibamais_id'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function saibamais()
    {
        return $this->belongsTo('App\SaibaMais');
    }

}
