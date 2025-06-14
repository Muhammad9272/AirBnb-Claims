<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{

    protected $guard = 'admin';

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role_id', 'created_at', 'updated_at', 'remember_token','shop_name', 'email_notifications',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function IsSuper(){
        if ($this->id == 1) {
           return true;
        }
        return false;
    }

    public function sectionCheck($value){
        $sections = explode(" , ", $this->role->section);
        if (in_array($value, $sections)){
            return true;
        }else{
            return false;
        }
    }
}
