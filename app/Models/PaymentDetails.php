<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PaymentDetails extends Model
{
    use HasFactory;
    public function getCreatedAtAttribute($value)
    {
        $carbonDate = new Carbon($value);
        return $carbonDate->format('Y-m-d |  h:i:s A');
    }
}
