<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $fillable = ['user_id','id_siswa','nis','nama','kelas','email','no_hp','username','password','created_at','updated_at'];
    public $timestamps = true;
    protected $hidden = ['password'];
}
