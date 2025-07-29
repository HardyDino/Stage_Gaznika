<?php

   namespace App\Models;

   use Illuminate\Foundation\Auth\User as Authenticatable;
   use Illuminate\Notifications\Notifiable;
   use Illuminate\Support\Facades\DB;

   class User extends Authenticatable
   {
       use Notifiable;

       protected $fillable = [
           'name',
           'email',
           'phone',
           'password',
           'role_id',
           'is_active',
           'remember_token',
       ];

       protected $hidden = [
           'password',
           'remember_token',
       ];

       public function role()
       {
           return $this->belongsTo(Role::class);
       }

       public function getStatusAttribute()
       {
           return $this->is_active ? 'Actif' : 'Inactif';
       }

       public function checkPassword($password)
       {
           $result = DB::selectOne(
               'SELECT password = crypt(?, password) AS password_match FROM users WHERE id = ?',
               [$password, $this->id]
           );
           return $result->password_match;
       }
   }