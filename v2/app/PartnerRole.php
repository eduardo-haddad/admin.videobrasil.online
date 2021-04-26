<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerRole extends Model
{
    protected $table = 'videobrasil.vbo_partner_roles';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_pt', 'role_en'
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function logos()
    {
        return $this->hasMany('App\LogoSaibaMais', 'saibamais_id');
    }

}
