<?php

namespace App\XmlIntegration;

use Illuminate\Database\Eloquent\Model;

class IntegrationClient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agenteimovel.xmls_integration';

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


    public function xmlFormat()
    {
        return $this->hasOne('App\XmlIntegration\XmlFormat', 'id', 'format_id');
    }

    public function integrationType()
    {
        return $this->hasOne('App\XmlIntegration\IntegrationType', 'id', 'integration_type_id');
    }



}
