<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeknisiProfile extends Model
{
    use HasFactory;

    protected $table = 'teknisi_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'id_teknisi',
    ];

    /**
     * Get the user that owns the TeknisiProfile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
