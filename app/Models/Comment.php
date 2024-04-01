<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'is_approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // to check if comment is Approved
    public function isApproved()
    {
        return $this->is_approved === 1;
    }
}
