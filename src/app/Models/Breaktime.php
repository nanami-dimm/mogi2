<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaktime extends Model
{
    use HasFactory;

    protected $fillable = [ 'breakStart','breakEnd','breakDuration','created_at','updated_at'];

    public function attendance()
    {
        return $this->belongsToMany(Attendance::class,'attendance_breaktime','break_id','attendance_id');
    }

    public function getBreakDurationAttribute()
    {

        $start = strtotime($this->breakStart);
        $end = strtotime($this->breakEnd);
        $duration = $end - $start;

        return gmdate("H:i:s", $duration); 
    }

    public function getDurationAttribute()
    {
        if ($this->breakStart && $this->breakEnd){
            return Carbon::parse($this->breakEnd)->diffInMinutes(Carbon::parse($this->breakStart));
        }
        return null;
        }
    }


