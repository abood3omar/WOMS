<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'priority', 
        'assigned_to', 
        'created_by', 
        'due_date'
    ];

    // المنشئ
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // الموظف المسند إليه (الفني)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // سجلات هذا الطلب
    public function logs()
    {
        return $this->hasMany(WorkOrderLog::class, 'work_order_id')->latest();
    }
}