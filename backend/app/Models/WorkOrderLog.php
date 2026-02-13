<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','work_order_id', 'action', 'performed_by','details','ip_address'];

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}