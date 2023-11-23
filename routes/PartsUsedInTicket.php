<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PartsUsedInTicket extends Model
{
    use Notifiable;
    
    protected $fillable = [
        'part_id',
        'ticket_id',
        'quantity_used'
    ];
}
