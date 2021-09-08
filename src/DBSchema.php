<?php

namespace Lambda\Puzzle;

use DB;

trait DBSchema
{
    public static function tables()
    {
        $ignore_tables = [];

        $tables_ = [];
        $views_ = [];

        if (env('DB_CONNECTION') == 'sqlsrv') {
            $tables = DB::select(DB::raw('SELECT TABLE_NAME, TABLE_TYPE FROM INFORMATION_SCHEMA.TABLES ORDER BY TABLE_NAME'));
            foreach ($tables as $t) {
                $key = 'TABLE_NAME';
                $tableName = $t->$key;
                if (array_search($tableName, $ignore_tables)) {
                } else {
                    if ($t->TABLE_TYPE == 'VIEW') {
                        $views_[] = $tableName;
                    } else {
                        $tables_[] = $tableName;
                    }
                }
            }
        } else {
            $tables = DB::select('SHOW FULL TABLES');
            $databaseName = env('DB_DATABASE', 'lambda_db');

            foreach ($tables as $t) {
                $key = "Tables_in_$databaseName";
                $tableName = $t->$key;
                if (array_search($tableName, $ignore_tables)) {
                } else {
                    if ($t->Table_type == 'VIEW') {
                        $views_[] = $tableName;
                    } else {
                        $tables_[] = $tableName;
                    }
                }
            }
        }

        return [
            'tables' => $tables_,
            'views' => $views_,
        ];
    }

    /*
     * get table Meta by table name
     * */
    public static function tableMeta($table)
    {
        $data = null;
        $data = [];
        try {
            $dataname = env('DB_DATABASE');
            if (env('DB_CONNECTION') == 'sqlsrv') {

                $data = DB::select(DB::raw("SELECT * FROM   $dataname.INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table'"));

                if ($data) {
                    $newData = [];
                    foreach ($data as $dcolumn) {
                        $type = '';
                        if ($dcolumn->DATA_TYPE == 'nvarchar') {
                            $type = 'varchar(255)';
                        } elseif ($dcolumn->DATA_TYPE == 'ntext') {
                            $type = 'text';
                        }
                        $newData[] = [
                            'model' => $dcolumn->COLUMN_NAME,
                            'title' => $dcolumn->COLUMN_NAME,
                            'dbType' => $type,
                            'table' => $table,
                            'key' => $dcolumn->ORDINAL_POSITION == 1 ? 'PRI' : '',
                            'extra' => $dcolumn->ORDINAL_POSITION == 1 ? 'auto_increment' : '',
                        ];
                    }

                    return $newData;
                } else {
                    return $data;
                }
            }

            $data = DB::select("SELECT COLUMN_NAME, COLUMN_KEY, DATA_TYPE, IS_NULLABLE, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table' AND table_schema = '$dataname'");
        } catch (\Exception $e) {
            dd($e);
        }
        if ($data) {
            $newData = [];

            foreach ($data as $dcolumn) {

                $newData[] = [
                    'model' => $dcolumn->COLUMN_NAME,
                    'title' =>  $dcolumn->COLUMN_NAME,
                    'dbType' => $dcolumn->DATA_TYPE,
                    'table' => $table,
                    'key' => $dcolumn->COLUMN_KEY,
                    'extra' => $dcolumn->EXTRA,
                    'nullable' => $dcolumn->IS_NULLABLE,
                ];
            }

            return $newData;
        }
        if ($data) {
            return $data;
        }
    }

    public static function getDBSchema()
    {
        $tables = Puzzle::tables();
        $dbSchema = [
            'tableList' => $tables['tables'],
            'viewList' => $tables['views'],
            'tableMeta' => [],
        ];

        foreach ($tables['tables'] as $t) {
            $dbSchema['tableMeta'][$t] = Puzzle::tableMeta($t);
        }

        foreach ($tables['views'] as $t) {
            $dbSchema['tableMeta'][$t] = Puzzle::tableMeta($t);
        }

        return $dbSchema;
    }
}
