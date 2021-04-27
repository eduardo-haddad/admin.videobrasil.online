<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogoSaibaMais extends Model
{
    protected $table = 'videobrasil.vbo_logos_saibamais';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
        'img', 'url', 'partner_roles_id'
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
    
    public function partner_roles()
    {
        return $this->belongsTo('App\PartnerRole');
    }

}
