<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = [
        'transaction_id', 'ticket_type_id', 'quantity', 'subtotal'
    ];

    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function tickettype(){
        return $this->hasOne(TicketType::class);
    }
}
