<?php

namespace Lambda\Puzzle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lambda\Agent\Helper\DataViewer;


class Permission extends Model
{
    use SoftDeletes;
    use DataViewer;

    protected $table = 'permissions';
    protected $fillable = ['name', 'display_name', 'description'/*, 'route'*/];
    public static $columns = ['id', 'name', 'display_name', 'description', 'created_at', 'updated_at', 'deleted_at'];

    public function setNameAttribute($value){
        $this->attributes['name'] = strtolower(str_replace(' ', '_', $value));
    }
    public function setDisplayNameAttribute($value){
        $this->attributes['display_name'] = ucfirst($value);
    }
//    public function setRouteAttribute($value){
//        $this->attributes['route'] = strtolower(str_replace(' ', '', $value));
//    }
}
