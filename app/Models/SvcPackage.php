<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SvcPackage extends Model
{
    use HasFactory;
    protected $table = 't_service_pkg';
    protected $primaryKey = 'sp_code';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['sp_name,sp_info,sp_recycle'];
    
}
