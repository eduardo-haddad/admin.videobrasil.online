<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaibaMais extends Model
{
    protected $table = 'videobrasil.vbo_saibamais';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_pt', 'content_en', 'replace_text'
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

    public function links()
    {
        return $this->hasMany('App\LinkSaibaMais', 'saibamais_id');
    }

}
