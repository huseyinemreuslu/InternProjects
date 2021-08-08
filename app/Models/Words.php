<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Words extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['verb','lang_id','parent_id','type'];

    public function children(){
        return $this->hasMany(Words::class, 'parent_id' , 'id');
    }
    public function parent(){
        return $this->hasOne(Words::class, 'id' , 'parent_id')->with('children');
    }
}
