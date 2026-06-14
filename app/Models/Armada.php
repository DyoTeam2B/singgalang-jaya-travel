<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Armada extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'armada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_mobil',
        'nomor_plat',
        'kapasitas',
        'status_armada',
    ];

    /**
     * Get the driver associated with the armada.
     */
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'armada_id');
    }

    /**
     * Get the trips for the armada.
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'armada_id');
    }
}
