<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustPackage extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'trel_cust_pkg';
    protected $primaryKey = '_nomor';

    public $incrementing = false;
    public $timestamps = false;

    public function getCupkgSvcBeginAttribute($value)
    {
       return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY');
    }
    
    public function getInvEndAttribute($value)
    {
       return (new Carbon($value))->isoFormat('dddd, D MMMM YYYY');
    }
}
