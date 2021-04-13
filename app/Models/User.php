<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class User extends Authenticatable{

    protected $fillable = [
        'email',
        'newemail',
        'password',
        'name',
        'surname',
        'photo',
        'city',
        'status',
        'phone',
        'code',
    ];

    protected $table = 'users';

    static function byCode($code){
        return DB::table('users')
                    ->where('code', $code)
                    ->select('id', 'email')
                    ->first();
    }

    static function NewEmailbyCode($code){
        return DB::table('users')
                    ->where('code', $code)
                    ->select('id', 'newemail')
                    ->first();
    }

    static function byEmail($email){
        return DB::table('users')
                    ->where('email', $email)
                    ->select('id')
                    ->first();
    }

    static function activate($id){
        return DB::update("update `users` set `status` = 'on', `code` = '' where `id` = ?", [$id]) > 0;
    }

    static function checkEmail($email, $column = 'email', $id = 0){
        $query = DB::table('users')
                    ->where($column, $email);

        if($id > 0){
            $query->where('id', '!=', $id);
        }

        return $query->count() > 0;
    }

    static function setCode($email, $code){
        return DB::update("update `users` set `code` = ? where `email` = ?", [$code, $email]) > 0;
    }

    static function setPassword($id, $hash){
        return DB::update("update `users` set `code` = '', `password` = ? where `id` = ?", [$hash, $id]) > 0;
    }

    static function byUID($uid, $column){
        return DB::table('users')
                    ->where($column, $uid)
                    ->select('id')
                    ->first();
    }
}
