<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name', 'slug', 'location_id', 'started_at', 'end_at'
    ];

    public function location(){
        return $this->hasOne(Location::class);
    }

    public function tickettypes(){
        return $this->hasMany(TicketType::class);
    }
}
