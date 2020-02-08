<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DonotionHistory extends Model
{
    protected $table = 'donotion_history';
    protected $fillable = [
        'user_id',
        'donotion_date'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function getOrdinal($number) {
        $suffix = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
          $ordinal = $number . 'th';
        }
        else {
          $ordinal = $number . $suffix[$number % 10];
        }
        return $ordinal;
    }
}