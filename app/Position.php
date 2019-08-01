<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';
    protected $guarded = [];

    public function employees() {
        return $this->hasMany('App\Employee', 'position', 'id');
    }

    public static function getAll() {
        return static::get()->sortBy('name');
    }

    public static function getOne($id) {
        return static::findOrFail($id);
    }

    public static function edit($id, $params) {
        return static::find($id)->update($params);
    }


















}
