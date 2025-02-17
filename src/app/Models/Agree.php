<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agree extends Model
{
    use HasFactory;
    protected $fillable = [ 'user_id','date','punchIn','punchOut','reststart','restend','note','created_at','updated_at'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    }


