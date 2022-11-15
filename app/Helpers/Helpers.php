<?php
namespace App\Helpers;

use Response;
use Redirect;
use Session;
use Carbon\Carbon;
use DB;
use Signifly\Shopify\Shopify;
class Helpers {

    public function __construct()
    {
       
    
    }

    public static function DeleteRecored($data)
    {   
        try {
            if ($data['delete_type'] == 'hard') {
                DB::table($data['table_name'])
                    ->where($data['column_name'], $data['recored_id'])
                    ->delete();
                    return true;
                
            } else {
                    DB::table($data['table_name'])
                    ->where($data['column_name'], $data['recored_id'])
                    ->update(
                            array(
                                $data['soft_column_name'] => 1,
                            )
                    );
                return true;
            }
        } catch (\Throwable $th) {
            return "this is catch please check DeleteRecored function in helper";
        }
    }


    public static function AddRecored($data)
    {   
        try {
            // $columns = \Config::get('project_config.'.$data['table_name']);    
            // Self::pp($column); die;
            $columns = array(
                //form_input_name       => "database_column_name"
                "id"            => "id",
                "title"         => "title",
                "description"   => "description",
                "image"         => "image",
            );

            $insertArray = [];
            foreach ($columns as $key => $col) {
                if (isset($data['input'][$key])) {
                    $insertArray[$col] = $data['input'][$key];
                }
            }
            // echo"<pre>";print_r($insertArray); die;
            DB::table($data['table_name'])->insert($insertArray);
            return true;
            
        } catch (\Throwable $th) {
            return "this is catch please check AddRecored function in helper";
        }
    }

    public static function UpdateRecored($data)
    {   

        try {
            $columns = \Config::get('databaseTableConfig.'.$data['table_name']); 
            // self::pp($columns);
            $columns = array(
                //form_input_name       => "database_column_name"
                "id"            => "id",
                "title"         => "title",
                "description"   => "description",
                "image"         => "image",
            );  
            $updateArray = [];
            
            foreach ($columns as $key => $col) {
                if (isset($data['input'][$key])) {
                    $updateArray[$col] = $data['input'][$key];
                }
            }

            DB::table($data['table_name'])
                    ->where($data['where_col'],$data['input'][$data['where_col']])
                    ->update($updateArray);
            return true;
        } catch (\Throwable $th) {
            return "this is catch please check UpdateRecored function in helper";
        }
    }

    public static function AddRecored_old($data)
    {   
        try {
            DB::table($data['table_name'])->insert($data['input']);
            return true;
        } catch (\Throwable $th) {
            return "this is catch please check DeleteRecored function in helper";
        }
    }
    
    public static function pp($data,$die=0)
    {
        echo "<pre>";
            print_r($data);
        echo "</pre>";
        if($die ==0){
            die(" pp ");    
        }
        
    }

     public static function is_item_refund($order_id,$line_item_id)
    {   
        $shopify = new Shopify(
            env('SHOPIFY_API_KEY'),
            env('SHOPIFY_API_PASSWORD'),
            env('SHOPIFY_DOMAIN'),
            env('SHOPIFY_API_VERSION')
        );
        
        $order_result = $shopify->get('orders/'.$order_id.'.json')->json(); 

        if(count($order_result['order']['refunds']) > 0){
           foreach ($order_result['order']['refunds'] as $key => $refunds_item) {
                $line_item_array[]=$refunds_item['refund_line_items'];
                
                foreach ($refunds_item['refund_line_items'] as $key2 => $line_item_obj) {
                    if($line_item_id == $line_item_obj['line_item_id']){

                        return true;
                        exit;
                    }
                }
                
           }
            

        }else{
            return false;
        }
      
        
    }

}