<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sobre extends Model
{
    protected $table = 'videobrasil.vbo_sobre';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title_pt', 'title_en', 'content_pt', 'content_en'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];



}
