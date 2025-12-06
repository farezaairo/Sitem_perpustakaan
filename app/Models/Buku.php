<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    public $incrementing = false; // karena id_buku varchar
    protected $keyType = 'string';

    protected $fillable = [
        'id_buku',
        'judul',
        'penulis',
        'kategori',
        'isbn',
        'stok',
        'tersedia',
        'tahun_terbit',
        'gambar_sampul'
    ];
}
