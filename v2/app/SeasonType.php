<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeasonType extends Model
{
    protected $table = 'videobrasil.vbo_season_type';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title_pt', 'title_en'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function editions()
    {
        $this->hasMany('App\Edition');
    }



}
