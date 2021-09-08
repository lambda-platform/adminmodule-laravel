<?php

namespace Lambda\Puzzle\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Lambda\Puzzle\Models\Role;
use Lambda\Puzzle\Requests\RoleRequest;
use Lambda\Puzzle\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\Config;

class RolesController extends Controller
{
    public function index()
    {
        return view('agent::index');
    }

    public function getRoles()
    {
        $roles = Role::SearchPaginateAndOrder();
        $getPermissions = Permission::all();

        foreach ($roles as $role) {
            $permissions = explode(',', $role->permissions);
            $role->permissions = DB::table('permissions')->whereIn('id', $permissions)->get();
        }
        if ($roles) {
            return response()->json(['status' => true, 'roles' => $roles, 'permissions' => $getPermissions]);
        }
        return response()->json(['status' => false, 'message' => 'Үүргийн жагсаалтад алдаа гарлаа']);
    }

    public function getKrudFields($id)
    {
        $config = Config::get('lambda');
        $user_fields = $config['user_data_fields'];
        $krud = DB::table('krud')->where('id', $id)->first();

        if ($krud) {
            $form = DB::table('vb_schemas')->where('id', $krud->form)->first();
            $grid = DB::table('vb_schemas')->where('id', $krud->grid)->first();

            $form_fields = [];
            if ($form) {
                $schema = json_decode($form->schema);

                foreach ($schema->schema as $field) {
                    $form_fields[] = $field->model;
                }
            }

            $grid_fields = [];

            if ($grid) {
                $schema = json_decode($grid->schema);

                foreach ($schema->schema as $field) {
                    $grid_fields[] = $field->model;
                }
            }
            return response()->json(['status' => true, 'user_fields' => $user_fields, 'form_fields' => $form_fields, 'grid_fields' => $grid_fields]);
        } else {
            $form_fields = [];
            $grid_fields = [];
            return response()->json(['status' => true, 'user_fields' => $user_fields, 'form_fields' => $form_fields, 'grid_fields' => $grid_fields]);
        }

    }

    public function getRolesMenus()
    {
        $roles = DB::table('roles')
            ->where('name', '!=', 'super-admin')
            ->where('deleted_at', '=', NULL)
            ->orderBy('name')->get();
        $menus = DB::table('vb_schemas')
            ->where('type', 'menu')
            ->orderBy('name')->get();
        $cruds = DB::table('krud')
            ->orderBy('title')->get();
        if ($roles) {
            return response()->json(['status' => true, 'roles' => $roles, 'menus' => $menus, 'cruds' => $cruds]);
        }
        return response()->json(['status' => false, 'message' => 'Үүргийн жагсаалтад алдаа гарлаа']);
    }

    public function saveRole(RoleRequest $request)
    {
        $role_id = $request->get('id');
        $permissions = $request->get('permissions');

        DB::table('roles')->where('id', $role_id)->update([
            'permissions' => json_encode($permissions)
        ]);

        return response()->json(['status' => true]);

    }

    public function getDeletedRoles()
    {
        $deletedRoles = Role::onlyTrashed()->SearchPaginateAndOrder();
        foreach ($deletedRoles as $role) {
            $permissions = explode(',', $role->permissions);
            $role->permissions = DB::table('permissions')->whereIn('id', $permissions)->get();
        }
        if ($deletedRoles) {
            return response()->json(['status' => true, 'deletedRoles' => $deletedRoles]);
        } else {
            return response()->json(['status' => false, 'message' => 'Устгасан үүргийн жагсаалтад алдаа гарлаа']);
        }
    }

    public function store(RoleRequest $request)
    {
//        if (Auth::user()->can('users_create')) {
//        $permissions = implode(",", $request->get('permissions'));
        $role = Role::create([
            'name' => $request['name'],
            'display_name' => $request['display_name'],
            'description' => $request['description'],
//                'permissions' => $permissions,
        ]);

        if ($role) {
            return response()->json(['status' => true, 'role' => $role, 'message' => 'Үүрэг амжилттай нэмэгдлээ']);
        } else {
            return response()->json(['status' => false, 'message' => 'Үүрэг нэмэхэд алдаа гарлаа']);
        }
    }


    public function update(RoleRequest $request, $id)
    {
//        $permissionsArray = $request->get('permissions');
//        $permissions = implode(",", $permissionsArray);

        $role = Role::find($id);

        $role->name = $request->get('name');
        $role->display_name = $request->get('display_name');
        $role->description = $request->get('description');
//        $role->permissions = $permissions;
        if ($role->save()) {
            return response()->json(['status' => true, 'message' => 'Үүрэг амжилттай шинэчлэгдлээ']);
        } else {
            return response()->json(['status' => false, 'message' => 'Үүрэг шинэчлэхэд алдаа гарлаа']);
        }
    }

    public function destroy($id)
    {
//        if (Auth::user()->can('users_delete')){
        if (Role::find($id)->delete()) {
            $role = Role::onlyTrashed()->where('id', $id)->get();
            return response()->json(['status' => true, 'role' => $role, 'message' => 'Үүрэг амжилттай устлаа']);
        } else {
            return response()->json(['status' => false, 'message' => 'Үүрэг устгахад алдаа гарлаа']);
        }
    }

    public function restore($id)
    {
//        if (Auth::user()->can('users_restore')){
        if (Role::onlyTrashed()->where('id', $id)->restore()) {
            return response()->json(['status' => true, 'message' => 'Үүргийг амжилттай сэргээлээ']);
        } else {
            return response()->json(['status' => false, 'message' => 'Үүрэг сэргээхэд алдаа гарлаа']);
        }
    }

    public function forceDelete($id)
    {
        if (Role::onlyTrashed()->where('id', $id)->forceDelete()) {
            return response()->json(['status' => true, 'message' => 'Үүрэг бүр мөсөн амжилттай устлаа']);
        } else {
            return response()->json(['status' => false, 'message' => 'Үүрэг бүр мөсөн устахад алдаа гарлаа']);
        }
    }
}
