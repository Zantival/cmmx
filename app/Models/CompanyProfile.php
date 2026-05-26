<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'phone',
        'rut_path',
        'logo',
        'banner',
        'corporate_info',
        'is_kyc_approved',
    ];

    protected $casts = [
        'is_kyc_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
