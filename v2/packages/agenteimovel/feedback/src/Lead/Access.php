<?php

namespace Feedback\Lead;

use Carbon\Carbon;
use App\Traits\LogsActivityTrait;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use LogsActivityTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lead_accesses';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

    /**
     * The attributes that need to be logged.
     */
    protected static $logAttributes = ['*'];

    /**
     * Set to true to logged only attributes
     * that were actually changed after the update.
     */
    protected static $logOnlyDirty = true;

    /**
     * Get the user accessing the lead
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the lead that owns the access
     */
    public function lead()
    {
        return $this->belongsTo('App\Lead\Lead', 'lead_id');
    }

    /**
     * Scope a query to only return non expired accesses.
     */
    public function scopeNotExpired($query, $lead_id)
    {
        return $query->where('lead_id', $lead_id)
                     ->where('expired_at', '>=', Carbon::now());
    }

    /**
     *
     */
    public static function getAnswers()
    {
        return [
            'checking_info'               => 'Só quero checar as informações do fornecedor sobre o lead',
            'call_not_answered'           => 'Ligou para o lead - Sem resposta',
            'call_not_answered_discarted' => 'Ligou para o lead - Sem resposta - Descartado',
            'lead_interested'             => 'Conversou com o lead - Interessado e negociação em andamento',
            'future_potential'            => 'Conversou com o lead - Potencial para o futuro',
            'talked_n_discarted'          => 'Conversou com o lead - Descartado',
            'sent_email'                  => 'Enviou E-mail'
        ];
    }

    /**
     *
     */
    public static function getAnswer($key)
    {
        return self::getAnswers()[$key];
    }
}
