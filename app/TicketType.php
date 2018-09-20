<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'name', 'event_id', 'price', 'quota'
    ];

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function transactiondetail(){
        return $this->belongsTo(TransactionDetail::class);
    }
}
