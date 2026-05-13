<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Closure extends Model
{
    protected $fillable = [
        'year',
        'month',
        'status',
        'locked_by_user_id',
        'locked_at',
        'notes'
    ];

    /**
     * Verifica se um período específico está fechado ou bloqueado.
     */
    public static function isPeriodClosed($date)
    {
        $d = is_string($date) ? new \DateTime($date) : $date;
        $year = $d->format('Y');
        $month = $d->format('m');

        return self::where('year', $year)
            ->where('month', $month)
            ->whereIn('status', ['closed', 'locked'])
            ->exists();
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by_user_id');
    }
}
