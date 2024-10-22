<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    protected $table = 'reports';

    protected $fillable = [
        'name',
        'template_use',
        'location',
        'input_fields',
        'ftp_details',
        'report_type',
        'report_date',
        'report_to_date',
        'report_id',
        'schedule',
        'schedule_cron',
        'last_run'
    ];

}
