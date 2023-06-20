<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 't_invoice';
    protected $primaryKey = 'inv_number';

    public $incrementing = false;
    public $timestamps = false;

    public function getInvStatusAttribute($value)
    {
        $status = $value;

        if ($value == 0) {
            $status = 'Belum Lunas';
        } else if ($value == 1) {
            $status = 'Lunas';
        } elseif ($value == 2) {
            $status = 'Expired';
        }

        return $status;
    }
    public function getInvPaidAttribute($value)
    {
        return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY HH:mm');
    }
    public function getInvStartAttribute($value)
    {
        return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY');
    }
    public function getInvEndAttribute($value)
    {
        return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY');
    }
    public function getAmountAttribute($value)
    {
        return currency_format($value);
    }
}
