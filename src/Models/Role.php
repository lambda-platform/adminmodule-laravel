<?php

namespace Lambda\Puzzle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Lambda\Agent\Helper\DataViewer;

class Role extends Model
{
    use SoftDeletes;
    protected $table = 'roles';
    protected $fillable = ['name', 'display_name', 'description', 'permissions'];
    public static $columns = ['id', 'name', 'display_name', 'description', 'permissions', 'created_at', 'updated_at', 'deleted_at'];

    public function setNameAttribute($value){
        $this->attributes['name'] = strtolower(str_replace(' ', '_', $value));
    }
    public function setDisplayNameAttribute($value){
        $this->attributes['display_name'] = ucfirst($value);
    }

    public function users()
    {
        return $this->hasMany('Lambda\Agent\Models\User')->withTrashed();
    }
}
