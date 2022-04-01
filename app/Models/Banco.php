<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'bancos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'descricao'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
