<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Porfoma extends Model
{
    use HasFactory;
    protected $table = 't_invoice_porfoma';
    protected $primaryKey = 'inv_number';

    public $incrementing = false;
    public $timestamps = false;

    public function getInvStartAttribute($value)
    {
       return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY');
    }
    public function getInvEndAttribute($value)
    {
       return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY');
    }
}
