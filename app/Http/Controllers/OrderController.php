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
use Signifly\Shopify\Shopify;
use App\Models\OrderModel;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->shopify = new Shopify(
            env('SHOPIFY_API_KEY'),
            env('SHOPIFY_API_PASSWORD'),
            env('SHOPIFY_DOMAIN'),
            env('SHOPIFY_API_VERSION')
        );
    
    }

    /*
    Order View/Table with filter
    */
    public function index(Request $request)
    {   
        die('0');
        $data['page_name']  = "Dashboard";
        $data['header']  = "";
        $data['slot']  = "";
        return view('pages.dashboard', $data);
    }

    /*
    To update order status on shopify via shopify API calling & fetch the updated orders from shopify
    */
    public function update_order_status(Request $request){
        echo 'To update the order status, call the shopify api ';

    }

    /*
    To update order notes on shopify via shopify API calling & fetch the updated orders from shopify
    */
    public function update_order_note(Request $request){
        echo 'To update the order notes by calling the shopify api ';

    }

    public function plan(Request $request)
    {   
        
        $data['listData'] = OrderModel:: paginate(100) ->appends(request()->except('page'));
        // $data['ship_type'] = OrderModel:: select('ship_type')->groupBy('ship_type')->get();
       
        return view('pages.order.plan', $data);
    }

    public function list(Request $request)
    {   
        
        $data['listData'] = OrderModel:: paginate(100) ->appends(request()->except('page'));
        $data['ship_type'] = OrderModel:: select('ship_type')->groupBy('ship_type')->get();
       
        return view('pages.order.list', $data);
    }

    public function getList(Request $request)
    {   
        
          $searchParameters =array();
          $data['order_data']="";
          $sort_by="DESC";
          $colum_name="order_created";
         if (isset($request->column)) {
            $sort_by=$request->column_order;
            $colum_name=$request->column;
           
            if($sort_by == "ASC"){
                $data['order_data'] =array('column_sort_by_new'=>"DESC",'column_name'=>$request->column);
            }else{
                $data['order_data']=array('column_sort_by_new'=>"ASC",'column_name'=>$request->column);
            }
         }
        if (isset($request->payment_status)) {
            $searchParameters['payment_status'] = $request->payment_status;
        }
        if (isset($request->order_status)) {
            $searchParameters['order_status'] = $request->order_status;
        }
        if (isset($request->keyword)) {
            $searchParameters['keyword'] = htmlspecialchars($request->keyword);
        }
         if (isset($request->ship_type)) {
            $searchParameters['ship_type'] = $request->ship_type;
        }

        if (isset($request->order_date_filter)) {
             $date=date_create($request->order_date_filter);
             $order_date= date_format($date,"Y-m-d");
          $searchParameters['order_date'] = $order_date;
        }
      
        
        $data['listData'] = OrderModel::where(function($query) use ($searchParameters)
            {
            
                if( isset($searchParameters['payment_status']) && ($searchParameters['payment_status'] != '' )) {
                    $query->where('order_details.payment_status', $searchParameters['payment_status']);
                }
                if( isset($searchParameters['order_status']) && ($searchParameters['order_status'] != '' )) {
                    $query->where('order_details.order_status', $searchParameters['order_status']);
                }
                if( isset($searchParameters['ship_type']) && ($searchParameters['ship_type'] != '' )) {
                            if($searchParameters['ship_type'] == "onlyInternational"){
                               $query->where('order_details.country', '!=','Australia');
                            }else{
                                $query->where('order_details.ship_type', $searchParameters['ship_type']);            
                            }
                    
                }
                 if( isset($searchParameters['order_date']) && ($searchParameters['order_date'] != '' )) {
                    $query->whereDate('order_details.order_created','=', $searchParameters['order_date']);
                }
               
            })
            ->leftJoin('products', 'order_details.product_id', '=', 'products.product_id')
            ->leftJoin('order_note', 'order_details.line_item_id', '=', 'order_note.line_item_id')
            ->select('order_details.*','products.image','products.barcode','order_note.note','order_note.fulfillment_id','order_note.note_color','order_note.order_row_color')
            ->orderBy('order_details.'.$colum_name,$sort_by)
            ->where(function($query) use ($searchParameters)
            {                                                                              
             if( isset($searchParameters['keyword']) && ($searchParameters['keyword'] != '' )) {
                    $query->orWhere('order_details.order_no', 'LIKE',"%" .$searchParameters['keyword'] ."%");
                    // $query->orwhere('products.barcode', 'LIKE','%'.$searchParameters['keyword'].'%');
                    // $query->orwhere('order_details.country', 'LIKE','%'.$searchParameters['keyword'].'%');
                    // $query->orwhere('order_details.product_title', 'LIKE','%'.$searchParameters['keyword'].'%');
                    // $query->orwhere('order_details.sku', 'LIKE','%'.$searchParameters['keyword'].'%');
                    
                }
              
            })
            ->whereNotIn('product_title', ['Shipping Protection '])
            ->whereNotIn('payment_status', ['refunded'])
            ->where('gift_card','!=',true)
            ->where(function($query) use ($searchParameters){
                $query->where('fulfillable_quantity','!=',0);
                $query->orwhere('item_status','fulfilled');
            })
          
            // ->orwhere('item_status','fulfilled')
            ->whereNotNull('order_details.product_id')
            // ->whereNull('closed_at')
            ->whereNull('cancelled_at')
            ->paginate(100)
            ->appends(request()->except('page'));
        
        return view('pages.order.crud-table', $data);
    }
    
    public function getPlanList(Request $request)
    {   
        
          $searchParameters =array();
          $data['order_data']="";
          $sort_by="DESC";
          $colum_name="order_created";
         if (isset($request->column)) {
            $sort_by=$request->column_order;
            $colum_name=$request->column;
           
            if($sort_by == "ASC"){
                $data['order_data'] =array('column_sort_by_new'=>"DESC",'column_name'=>$request->column);
            }else{
                $data['order_data']=array('column_sort_by_new'=>"ASC",'column_name'=>$request->column);
            }
         }
        if (isset($request->payment_status)) {
            $searchParameters['payment_status'] = $request->payment_status;
        }
        if (isset($request->order_status)) {
            $searchParameters['order_status'] = $request->order_status;
        }
        if (isset($request->keyword)) {
            $searchParameters['keyword'] = htmlspecialchars($request->keyword);
        }
         if (isset($request->ship_type)) {
            $searchParameters['ship_type'] = $request->ship_type;
        }

        if (isset($request->order_date_filter)) {
             $date=date_create($request->order_date_filter);
             $order_date= date_format($date,"Y-m-d");
          $searchParameters['order_date'] = $order_date;
        }
        $today = date("Y/m/d");
        $tomorrow = date("Y/m/d", strtotime('tomorrow'));
        $inweek = date("Y/m/d", strtotime("+7 day"));
        $data['listData'] = \DB::select("
        SELECT origin.id, origin.title, today.todayCounts, tomorrowCounts, inTwoDayCounts, inweek.inweekCounts FROM 
(SELECT products.id , products.title FROM products LEFT JOIN order_details ON order_details.product_id = products.product_id GROUP BY products.id, products.title ) AS origin LEFT JOIN
(SELECT products.title, SUM(order_details.quantity) AS todayCounts FROM products LEFT JOIN order_details ON order_details.product_id = products.product_id 
WHERE order_details.pickup_date = '".$today."' GROUP BY products.id, products.title ) AS today ON origin.title =  today.title
LEFT JOIN (SELECT products.title, SUM(order_details.quantity) AS tomorrowCounts FROM products LEFT JOIN order_details ON order_details.product_id = products.product_id 
WHERE order_details.pickup_date = '".$tomorrow."' GROUP BY products.id, products.title) AS tomorrow ON origin.title = tomorrow.title

LEFT JOIN (SELECT products.title, SUM(order_details.quantity) AS inTwoDayCounts FROM products LEFT JOIN order_details ON order_details.product_id = products.product_id 
WHERE ( order_details.pickup_date BETWEEN  '".$today."' AND '".$tomorrow."' ) GROUP BY products.id,  products.title) AS intwo ON origin.title = intwo.title

LEFT JOIN (SELECT products.title, SUM(order_details.quantity) AS inweekCounts FROM products LEFT JOIN order_details ON order_details.product_id = products.product_id 
WHERE ( order_details.pickup_date BETWEEN  '".$today."' AND '".$inweek."' ) GROUP BY products.id,  products.title) AS inweek ON origin.title = inweek.title
        ");
// $data['listData'] = \DB::select("SELECT products.id , products.title FROM products LEFT JOIN order_details ON order_details.product_id = products.product_id GROUP BY products.id, products.title");
        // $data['listData'] = OrderModel::where(function($query) use ($searchParameters)
        //     {
            
        //         if( isset($searchParameters['payment_status']) && ($searchParameters['payment_status'] != '' )) {
        //             $query->where('order_details.payment_status', $searchParameters['payment_status']);
        //         }
        //         if( isset($searchParameters['order_status']) && ($searchParameters['order_status'] != '' )) {
        //             $query->where('order_details.order_status', $searchParameters['order_status']);
        //         }
        //         if( isset($searchParameters['ship_type']) && ($searchParameters['ship_type'] != '' )) {
        //                     if($searchParameters['ship_type'] == "onlyInternational"){
        //                        $query->where('order_details.country', '!=','Australia');
        //                     }else{
        //                         $query->where('order_details.ship_type', $searchParameters['ship_type']);            
        //                     }
                    
        //         }
        //          if( isset($searchParameters['order_date']) && ($searchParameters['order_date'] != '' )) {
        //             $query->whereDate('order_details.order_created','=', $searchParameters['order_date']);
        //         }
               
        //     })
        //     ->leftJoin('products', 'order_details.product_id', '=', 'products.product_id')
        //     ->leftJoin('order_note', 'order_details.line_item_id', '=', 'order_note.line_item_id')
        //     ->select('order_details.*','products.image','products.barcode','order_note.note','order_note.fulfillment_id','order_note.note_color','order_note.order_row_color')
        //     ->orderBy('order_details.'.$colum_name,$sort_by)
        //     ->where(function($query) use ($searchParameters)
        //     {                                                                              
        //      if( isset($searchParameters['keyword']) && ($searchParameters['keyword'] != '' )) {
        //             $query->orWhere('order_details.order_no', 'LIKE',"%" .$searchParameters['keyword'] ."%");
        //             // $query->orwhere('products.barcode', 'LIKE','%'.$searchParameters['keyword'].'%');
        //             // $query->orwhere('order_details.country', 'LIKE','%'.$searchParameters['keyword'].'%');
        //             // $query->orwhere('order_details.product_title', 'LIKE','%'.$searchParameters['keyword'].'%');
        //             // $query->orwhere('order_details.sku', 'LIKE','%'.$searchParameters['keyword'].'%');
                    
        //         }
              
        //     })
        //     ->whereNotIn('product_title', ['Shipping Protection '])
        //     ->whereNotIn('payment_status', ['refunded'])
        //     ->where('gift_card','!=',true)
        //     ->where(function($query) use ($searchParameters){  
        //         $query->where('fulfillable_quantity','!=',0);
        //         $query->orwhere('item_status','fulfilled');
        //     })
          
        //     // ->orwhere('item_status','fulfilled')
        //     ->whereNotNull('order_details.product_id')
        //     // ->whereNull('closed_at')
        //     ->whereNull('cancelled_at')
        //     ->paginate(100)
        //     ->appends(request()->except('page'));
        // echo json_encode($data);
        return view('pages.order.plan-table', $data);
    }

    
    public function get_order_record_by_id(Request $request)
    {   
         $id= $request->id;
         $data['data'] = OrderModel::leftJoin('order_note', 'order_details.line_item_id', '=', 'order_note.line_item_id')-> where("order_details.id",$id)->select('order_details.*', 'order_note.note','order_note.note_color')->first();

        return $a = view('pages/crud/popup/edit-order-form', $data);
    }

     public function edit_order_action(Request $request)
    {   
        $order=$request->all();
         $id=$request->id;
        unset($order['id']);
        unset($order['_token']);
        
        $order_record= OrderModel::where("id",$id)->first();
       // print_r($order_record);
         $order_id=$order_record->order_id;
        $line_item_id=$order_record->line_item_id;
        $result=$this->UpdateOrderOnShopify($order,$order_id,$line_item_id);
        if($result){
            echo 1;
           $request->session()->flash('message.level', 'success');
            $request->session()->flash('message.content', 'Order saved Succesfully!');
        }else{
            echo 0;
            $request->session()->flash('message.level', 'danger');
            $request->session()->flash('message.content', 'Order Not saved !');
        }
   }

    public function UpdateOrderOnShopify($order,$order_id,$line_item_id){
        $data['order']=$order;

        $status=false;
     
        if(isset($data['order']['note']) || isset($data['order']['note_color'])){
            // $note['order']=array('note'=>$data['order']['note']);
            //  $res = $this->shopify->put('orders/'.$order_id.'.json',$note)->json();
            // if(!empty($res['order'])){
              $status=true;
            // }
           
        }


        if(isset($data['order']['item_status'])){
               
               $order_record= OrderModel::where("order_id",$order_id)->where("line_item_id",$line_item_id)->first();
               $old_order_status=$order_record->item_status;

               $get_fulfillment_data= DB::table('order_note')->where("order_id",$order_id)->where("line_item_id",$line_item_id)->first();
               
               $location_id="";
               if(isset($get_fulfillment_data)){
               $location_id=$get_fulfillment_data->location_id;
               $fulfillment_id=$get_fulfillment_data->fulfillment_id;
               }
              

                  if($data['order']['item_status'] == "fulfilled" && $old_order_status =="unfulfilled"){
                     
                      if(empty($location_id)){
                        $location_data = $this->shopify->get("locations.json")->json();
                        $location_id=$location_data['locations'][0]['id'];
                      }
                     
                      $item_id_array=array("id"=>$line_item_id);
                     
                      $fulfillment_data["fulfillment"]=array(
                           "location_id" =>$location_id,
                           "tracking_number" => null,
                           "line_items" => [$item_id_array]
                      );
                     //print_r($fulfillment_data);
                     $response = $this->shopify->post("orders/".$order_id."/fulfillments.json",$fulfillment_data)->json();
                    
                     $order['location_id']=$response['fulfillment']['location_id'];
                     $order['fulfillment_id']=$response['fulfillment']['id'];
                    
                     if($response['fulfillment']['status'] == "success"){
                        $orderinfo=$this->GetShopifyOrderInfo($order_id);
                        $new_order_status=$orderinfo['order']['fulfillment_status'];
                        $new_order_status_check= !empty($new_order_status) ? $new_order_status : 'unfulfilled';
                        OrderModel:: where("order_id",$order_id)->update(["order_status"=>$new_order_status_check]);

                        $count_record= DB::table('order_note')->where('order_id', $order_id)->where("line_item_id",$line_item_id)->count();
                        
                        $fulfillment_data_update['location_id']=$response['fulfillment']['location_id'];
                        $fulfillment_data_update['fulfillment_id']=$response['fulfillment']['id'];
                        if($count_record > 0){
                            DB::table('order_note')->where("order_id",$order_id)->where("line_item_id",$line_item_id)->update($fulfillment_data_update);
                        }else{
                            $fulfillment_data_update['order_id']=$order_id;
                            $fulfillment_data_update['line_item_id']=$line_item_id;
                            $result=DB::table('order_note')->insert($fulfillment_data_update);
                        }

                        $status=true;
                     }else{
                        $status=false;
                     }
                 

                  }

                  if($data['order']['item_status'] == "unfulfilled" && $old_order_status =="fulfilled"){
                      $response = $this->shopify->post("orders/".$order_id."/fulfillments/".$fulfillment_id."/cancel.json", [] )->json();
                      if($response['fulfillment']['status'] == "cancelled"){

                        $orderinfo=$this->GetShopifyOrderInfo($order_id);
                        $new_order_status=$orderinfo['order']['fulfillment_status'];
                        $new_order_status_check= !empty($new_order_status) ? $new_order_status : 'unfulfilled';
                        OrderModel:: where("order_id",$order_id)->update(["order_status"=>$new_order_status_check]);

                        $status=true;
                     }else{
                        $status=false;
                     }
                  }
            
        }

          if($status){
                     if(isset($data['order']['note']) || isset($data['order']['note_color'])){

                        
                        $count_record= DB::table('order_note')->where('order_id', $order_id)->where("line_item_id",$line_item_id)->count();
                        if($count_record > 0){
                            $result=DB::table('order_note')->where("order_id",$order_id)->where("line_item_id",$line_item_id)->update($order);
                        }else{
                            $order['order_id']=$order_id;
                            $order['line_item_id']=$line_item_id;
                            $result=DB::table('order_note')->insert($order);
                        }

                     }else{
                        $result= OrderModel:: where("order_id",$order_id)->where("line_item_id",$line_item_id)->update($order);
                     }
                     
                   
                       if($result){
                           return true;
                          
                        }else{
                           return false;
                           
                        }
          }else{
                return false;
          }
       



    }
   
   
   
   public function order_highlight_popup(Request $request)
    {   
         $ids= $request->id;
         $data['data']['ids']=implode(',', $ids);
         //$data['data']['ids'] =$ids;
         // print_r($id);
         // $data['data'] = OrderModel::leftJoin('order_note', 'order_details.line_item_id', '=', 'order_note.line_item_id')-> where("order_details.id",$id)->select('order_details.*', 'order_note.note','order_note.note_color','order_note.order_row_color')->first();
         
         return $a = view('pages/crud/popup/order-highlight-form', $data);
    }
   
   public function order_highlight_action(Request $request)
    {   
         $order=$request->all();
         $id=$request->order_id;
         $order_ids=explode(",",$id);
          
          foreach ($order_ids as $key => $value) {
            
             $order_record= OrderModel::where("id",$value)->first();
             $order_id=$order_record->order_id;
             $line_item_id=$order_record->line_item_id;
             unset($order['_token']);
             unset($order['order_id']);
        
             $count_record= DB::table('order_note')->where('order_id', $order_id)->where("line_item_id",$line_item_id)->count();
                        if($count_record > 0){
                            $result=DB::table('order_note')->where("order_id",$order_id)->where("line_item_id",$line_item_id)->update($order);
                        }else{
                            $order['order_id']=$order_id;
                            $order['line_item_id']=$line_item_id;
                            $result=DB::table('order_note')->insert($order);
                        }
          }

           echo 1;
           $request->session()->flash('message.level', 'success');
           $request->session()->flash('message.content', 'Order saved Succesfully!');
        

        // if($result){
        //     echo 1;
        //    $request->session()->flash('message.level', 'success');
        //     $request->session()->flash('message.content', 'Order saved Succesfully!');
        // }else{
        //     echo 0;
        //     $request->session()->flash('message.level', 'danger');
        //     $request->session()->flash('message.content', 'Order Not saved !');
        // }
   }

   public function GetShopifyOrderInfo($order_id){
       $order_result = $this->shopify->get('orders/'.$order_id.'.json')->json(); 
       return $order_result;
   }



}
/*
//Sample

1. To load & call another controller function 
    //app('App\Http\Controllers\ShopifyController')->index();

2. To handle the request (Get Parameter / Post Parameters)
    print_r($request->all());
    echo $name = $request->input('s');
    echo $name = $request->s;
        



*/ 