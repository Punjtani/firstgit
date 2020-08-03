<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingModel extends Model
{
    //
	 public $table = 'bookings';
    public $primaryKey = 'id';
    protected $fillable = [
        'category_id',
        'name',
        'email',
        'phone',
        'age',
        'gender',
        'country',
        'booking_date',
        'booking_time',
      
    ];
}
