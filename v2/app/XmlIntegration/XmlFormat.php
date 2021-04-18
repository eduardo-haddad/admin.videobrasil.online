<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;

class XmlFormat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agenteimovel.xmls_formats';

    /**
     * The primary key column.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function integrationClients()
    {
        return $this->hasMany('App\XmlIntegration\IntegrationClient', 'format_id');
    }



}
