<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helpers;
use Illuminate\Routing\UrlGenerator;
use Response;
use Headers;
use DB;
use File;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\CrudModel;

class CrudController extends Controller
{
    public function __construct()
    {
        
    }

    public function list(Request $request)
    {   
        
        $data['listData'] = CrudModel::
                where("is_deleted","0")
                ->paginate(10)
                ->appends(request()->except('page'));
        // Helpers::pp($all);
        $data['page_name']  = "Crud-List";
        return view('pages.crud.list', $data);
    }

    public function getList(Request $request)
    {   
        $data['listData'] = CrudModel::
                where("is_deleted","0")
                ->paginate(2)
                ->appends(request()->except('page'));
        $data['page_name']  = "Crud-List";
        return view('pages.crud.crud-table', $data);
    }

    public function add(Request $request) 
    {   
        $data['table_name'] = "crud";
        $data['input'] = $request->all();

        $responce = Helpers::AddRecored($data);
        
        if ($responce == true) {
            $a = json_encode(['message' => 'Recored Insert successfully', 'status' => 200]);
            return response($a, 200)->header('Content-Type', 'application/json');
        } else {
            $a = json_encode(['Error' => 'Something went wrong please check', 'status' => 500]);
            return response($a, 500)
                    ->header('Content-Type', 'application/json');
        }
    }

    public function getRecoredByID(Request $request)
    {
        $data['data'] = CrudModel::
            where("is_deleted","0")
            ->where("id",$request->id)
            ->first();
            return $a = view('pages/crud/popup/edit-form', $data);
    }

    public function update(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        }

        $data['table_name'] = "crud";
        $data['where_col']  = "id";
        $data['input']      = $request->all();
        
        $responce = Helpers::UpdateRecored($data);
        // Helpers::pp($responce); 

        if ($responce == true) {
            $a = json_encode(['message' => 'Recored Updated successfully', 'status' => 200]);
            return response($a, 200)->header('Content-Type', 'application/json');
        } else {
            $a = json_encode(['Error' => 'Something went wrong please check', 'status' => 500]);
            return response($a, 500)
                    ->header('Content-Type', 'application/json');
        }

    }

    public function delete(Request $request)
    {
        $data['table_name']         = "crud";
        $data['recored_id']         = $request->id;
        $data['column_name']        = 'id';
        // $data['delete_type']     = "hard";

        $data['delete_type']        = "soft";
        $data['soft_column_name']   = "is_deleted";
        
        $responce = Helpers::DeleteRecored($data);
        
        if ($responce == true) {

            $a = json_encode(['message' => 'Recored delete successfully', 'status' => 200]);
            return response($a, 200)
                    ->header('Content-Type', 'application/json');
        } else {
            $a = json_encode(['Error' => 'Something went wrong please check', 'status' => 500]);
            return response($a, 500)
                    ->header('Content-Type', 'application/json');
        }
     }
}
