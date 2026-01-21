<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSlo extends Model
{
    use HasFactory;

    protected $table = 'master_slo';
    protected $guarded = ['id'];
}
