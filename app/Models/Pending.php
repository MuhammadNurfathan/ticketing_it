<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pending extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pending';

    // Field yang bisa diisi massal
    protected $fillable = [
        'id_project_header',
        'pending_start',
        'pending_end',
        'reason',
        'duration_minutes',
    ];

    // Timestamp default Laravel aktif (created_at & updated_at)
    public $timestamps = true;

    // Opsional: kalau mau cast field tanggal otomatis
    protected $dates = [
        'pending_start',
        'pending_end',
    ];

     public function projectHeader()
    {
        return $this->belongsTo(ProjectHeader::class, 'id_project_header')->withTrashed();
    }


}
