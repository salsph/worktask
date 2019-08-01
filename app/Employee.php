<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    protected $table = 'employees';
    protected $guarded = [];
    protected $dates = ['employee_date', 'created_at', 'updated_at'];

    public static $photoWidth = 300;
    public static $photoHeight = 300;

    public static function getAll() {
        $needbleFiedls = [
            'employees.*',
            'positions.name as position'
        ];

        return static::leftJoin('positions', 'employees.position', '=', 'positions.id')
            ->get($needbleFiedls);
    }

    public function getPosition() {
        return $this->belongsTo('App\Position', 'position', 'id');
    }

    public function children() {
        return $this->hasMany(self::class, 'head');
    }

    public function parent() {
        return $this->belongsTo(self::class, 'head');
    }

    public static function getByName($name) {
        return static::where('name', $name)->first();
    }

    public static function getOne($id) {
        return static::findOrFail($id);
    }

    public static function edit($id, $params) {
        return static::find($id)->update($params);
    }

    public static function getSimilarName($name) {
        return static::select('name')
            ->where('name','LIKE', "%{$name}%")
            ->get();
    }

    public static function getUnleveled() {
        return static::where('level', null)->first();
    }

    public static function getLowLeveled() {
        return static::where('level', '<=', 4)->inRandomOrder()->limit(1)->first();
    }

}





















