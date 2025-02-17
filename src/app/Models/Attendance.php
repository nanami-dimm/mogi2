<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','punchIn','punchOut','workDuration','created_at','updated_at','note'];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function breaktimes(){
        return $this->belongsToMany(Breaktime::class,'attendance_breaktime','attendance_id','breaktime_id');
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
