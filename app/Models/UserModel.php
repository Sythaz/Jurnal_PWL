<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_user';        // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'user_id';  // Mendefinisikan primary key dari tabel yang digunakan
  
    /** 
     * Mirip seperti comment ( // ) namun bisa digunakan untuk multi line.
     * Khusus php.
     * 
     * $fillable: Atribut yang bisa diisi (seperti insert, update).
     * @var array */
    protected $fillable = ['level_id', 'username', 'nama_lengkap', 'password'];

    protected $hidden = ['password'];

    public function level(): BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }
}
