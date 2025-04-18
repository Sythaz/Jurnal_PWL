<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'm_kegiatan';        // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'kegiatan_id';  // Mendefinisikan primary key dari tabel yang digunakan

    /** 
     * Mirip seperti comment ( // ) namun bisa digunakan untuk multi line.
     * Khusus php.
     * 
     * $fillable: Atribut yang bisa diisi (seperti insert, update).
     * @var array */
    protected $fillable = ['user_id', 'nama_kegiatan', 'waktu', 'catatan'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}
