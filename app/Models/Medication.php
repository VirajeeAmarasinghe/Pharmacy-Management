<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ["name", "description", "quantity", "user_id"];

    /**
     * Get the user that owns the medication.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
