<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','date_column','punchIn','punchOut','breakStart','breakEnd','breakDuration','workDuration'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($attendance){
            if ($attendance->breakStart && $attendance->breakEnd) {
                $breakStart = Carbon::parse($attendance->breakStart);
                $breakEnd = Carbon::parse($attendance->breakEnd);
                $attendance->brealDuration = $breakStart->diffInMinutes($breakEnd);
            }
            if ($attendance->punchIn && $attendance->punchOut){
                $punchIn = Carbon::parse($attendance->punchIn);
                $punchOut = Carbon::parse($attendance->punchOut);
                $attendance->workDuration = $punchIn->diffInMinutes($punchOut) - ($attendance->breakDuration ?? 0);
            }
        });
    }
}
