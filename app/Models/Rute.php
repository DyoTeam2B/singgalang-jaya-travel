<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rute';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'asal',
        'tujuan',
        'tarif',
    ];

    /**
     * Get the schedules associated with this route.
     */
    public function jadwal(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'rute_id');
    }
}
