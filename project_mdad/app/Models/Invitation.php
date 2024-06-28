<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;
    // protected $fillable = [
    //     'project_id',
    //     'emails'
        
    // ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
