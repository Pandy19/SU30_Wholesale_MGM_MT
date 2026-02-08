<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLoginLog extends Model
{
    protected $table = 'admin_login_logs';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    const UPDATED_AT = null; // only created_at exists in your table

    protected $fillable = [
        'user_id','role_attempt','email_attempt','ip_address',
        'user_agent','success','fail_reason','created_at'
    ];

    public function user()
    {
        return $this->belongsTo(AdminUser::class, 'user_id', 'id');
    }
}
