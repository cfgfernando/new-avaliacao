<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'nickname',
        'document',
        'document_type',
        'email',
        'phone',
        'contacts',
        'address',
        'bank_account',
        'status',
        'notes',
    ];

    protected $casts = [
        'address' => 'json',
        'contacts' => 'json',
        'bank_account' => 'json',
    ];
}
