<?php

namespace Lambda\Puzzle\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use Dataform;
use Datagrid;
use DataSource;
use DB;
use Puzzle;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Storage;
class PuzzleController extends Controller
{
    public function index()
    {
        $dbSchema = Puzzle::getDBSchema();
        $gridList = DB::table('vb_schemas')->where('type', 'grid')->get();
        $config = Config::get('lambda');
        $title = $config["title"];
        $user_fields = $config['user_data_fields'];


        return view('puzzle::index', compact('dbSchema', 'gridList', 'user_fields', 'title'));
    }
    public function UploadDBSCHEMA()
    {

        $dbSchema = Puzzle::getDBSchema();
        $config = Config::get('lambda');
        Storage::put('db.json', json_encode($dbSchema));

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://lambda.cloud.mn/console/upload/'.$config["project_key"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('file'=> new \CurlFile(storage_path('app')."/db.json")),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if($response){
            $response = json_decode($response);
            if($response->status){
                return view('puzzle::upload_success');
            } else {
                return ["status"=>false];
            }
        }else {
            return ["status"=>false];
        }

    }

    public function ASyncFromCloud(){
        $config = Config::get('lambda');
        $url = "https://lambda.cloud.mn/console/project-data/".$config["project_key"]."?platform=laravel";


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $response = curl_exec($curl);

            curl_close($curl);


        if($response){
            $response = json_decode($response, true);
            if($response["status"]) {

                $datasoures = DB::table('vb_schemas')->where("type", "=", "datasource")->get();
                DB::table('krud')->truncate();
                DB::table('vb_schemas')->truncate();




                foreach ($response["cruds"] as $krud){
                    unset($krud["projects_id"]);
                    DB::table('krud')->insert($krud);
                }
                foreach ($response["form-schemas"] as $form){
                    unset($form["projects_id"]);
                    DB::table('vb_schemas')->insert($form);
                }
                foreach ($response["grid-schemas"] as $grid){
                    unset($grid["projects_id"]);
                    DB::table('vb_schemas')->insert($grid);
                }
                foreach ($response["menu-schemas"] as $menu){
                    unset($menu["projects_id"]);
                    DB::table('vb_schemas')->insert($menu);
                }
                foreach ($datasoures as $datasoure){
                    unset($datasoure->id);
                    DB::table('vb_schemas')->insert($datasoure);
                }


                return view("puzzle::syns_success");
            } else {
                return ["status"=>false];
            }
        }else {
            return ["status"=>false];
        }

    }

    public function embed()
    {
        $dbSchema = Puzzle::getDBSchema();
        $gridList = DB::table('vb_schemas')->where('type', 'grid')->get();

        return view('puzzle::embed', compact('dbSchema', 'gridList'));
    }

    public function dbSchema($table = false)
    {
        return $table == false ? VB::tables() : VB::tableMeta($table);
    }

    //Chart function

    //Visual builder
    public function getVB($type, $id = false, $condition = null)
    {
        $qr = DB::table('vb_schemas')->where('type', $type);

        if(strpos($id,'_')===false) {
            $data = $id === false ? $qr->orderBy('created_at', 'desc')->get() : $qr->where('id', $id)->first();
        }
        else{
            $qr = DB::table('vb_schemas_admin')->where('type', $type);
            $data = $qr->where('id', $id)->first();
        }

        $user_condition = [];
        //Filling option data
        if ($type == 'form' && $id != false) {
            $user = null;
            if ($condition) {
                if (Auth::user()) {
                    $user = Auth::user();
                    $user = $user->toArray();
                    $condition = json_decode($condition, true);
                    if ($user && $condition) {
                        foreach ($condition as $u_condition) {
                            $user_condition[$u_condition['form_field']] = $user[$u_condition['user_field']];
                        }
                        $schema = json_decode($data->schema);

                        if ($condition != 'builder') {
                            foreach ($schema->schema as &$s) {
                                foreach ($user_condition as $key => $value) {
                                    if ($s->model == $key) {
                                        $s->default = $value;
                                        $s->disabled = true;
                                    }
                                }
                            }
                        }

                        $schema->ui->schema = $this->setUserCondition($schema->ui->schema, $user_condition);
                        $data->schema = json_encode($schema);
                    }
                }
                else{
                    return redirect('auth/login');
                }
            }
        }

        if ($data) {
            return response()->json(['status' => true, 'data' => $data]);
        }
        return response()->json(['status' => false]);
    }

    public function getOptions()
    {
        $relations = request()->relations;

        $f = new Dataform();
        $data = [];
        foreach ($relations as $key => $relation) {
            $data[$key] = $f->options((object)$relation);
        }
        return $data;
    }

    public function setUserCondition($schema_ui, $use_condition)
    {
        foreach ($schema_ui as &$ui) {
            if ($ui->type == 'form') {
                foreach ($use_condition as $key => $value) {
                    if ($ui->model == $key) {
                        $ui->default = $value;
                        $ui->disabled = true;
                    }
                }
            }

            if (isset($ui->children)) {
                $ui->children = $this->setUserCondition($ui->children, $use_condition);
            }
        }

        return $schema_ui;
    }

    public function saveVB($type, $id = false)
    {
        $qr = DB::table('vb_schemas');
        $data = [
            'name' => request()->name,
            'type' => $type,
            'schema' => request()->schema,
        ];
        $action = $id ? 'update' : 'insert';

        $this->beforeAction($action, $data, $id);

        if ($id == false) {
            $r = $qr->insert($data);

            $id = DB::getPdo()->lastInsertId();
        } else {
            $r = $qr->where('id', $id)->update($data);

            $r >= 0 ? $r = true : $r = false;
        }

        if ($r) {
            $this->afterAction($action, $data, $id);

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

    public function deleteVB($table, $type, $id)
    {
        $this->beforeAction('delete', ['type' => $type], $id);
        $r = DB::table($table)->delete($id);
        if ($r) {
            $this->afterAction('delete', ['type' => $type], $id);

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

    public function formVB($action, $schemaID)
    {
        return Dataform::exec($schemaID, $action, null);
    }

    public function gridVB($action, $schemaID)
    {
        return Datagrid::exec($action, $schemaID);
    }

    public function fileUpload()
    {
        return Dataform::upload();
    }

    public function afterAction($action, $data, $id)
    {
        if ($data['type'] == 'datasource') {
            DataSource::viewHandler('after', $action, $data, $id);
        }
    }

    public function beforeAction($action, $data, $id)
    {
        if ($data['type'] == 'datasource') {
            DataSource::viewHandler('before', $action, $data, $id);
        }
    }
}
