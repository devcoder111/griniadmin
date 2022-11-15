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


class ShopifyController extends Controller
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
 
  public function test_order(){
     $order_array = $this->shopify->get('orders/4340766539874.json')->json();
     echo "<pre>";
     print_r($order_array); die();

 }
 
// Get New And Updated Product  
 public function fetch_new_update_product(){
    $this->fetch_update_product();
    $this->fetch_insert_new_product();
 }

 // Get New And Updated Order  
 public function fetch_new_update_order(){
    //$this->fetch_update_order();
    $this->fetch_insert_new_order();
 }



// Start Add Product from shopify to Database
   public function fetch_insert_new_product(){
    
     $product_count = $this->shopify->getProductsCount();//  die;
     $last_product= DB::table('products')->orderBy('product_id', 'DESC')->first();
     if(!empty($last_product)){
       $last_product_id=$last_product->product_id;
     
     }else{
        $last_product_id=1;
     }
      $products = $this->shopify->getProducts(['limit' => 250,'since_id'=>$last_product_id]); 
     
     if(count($products) > 0){
         foreach ($products as $key => $value) {
                      
                $prodata = array();
                $prodata['product_id'] = $value['id'];
                $prodata['title'] = $value['title'];
                $prodata['shopify_create_at'] = $value['created_at'];
                $prodata['shopify_update_at'] = $value['updated_at'];
                if (!empty($value['variants'])) {
                   
                    foreach ($value['variants'] as $variant) {
                       $prodata['sku'] = $variant['sku'];
                       $prodata['barcode'] = $variant['barcode'];
                    }
                    
                }

                 if (!empty($value['image'])) {
                    $prodata['image'] = $value['image']['src'];
                 }

                 DB::table('products')->insert($prodata);
            
          }
        echo "<p style='color:green;text-align:center;'>Product Added Successfully</p>";
        
        
       
     }else{
        echo "<p style='color:red;text-align:center;'>No Product Found</p>";
     }
    echo "<p style='color:blue;text-align:center;'>Total Products On Shopify:".$product_count."</p>";

   }
// End Add Product from shopify to Database

// Start fetch updated Product from shopify and update into database 
   public function fetch_update_product(){
 
     $shopify = \Signifly\Shopify\Factory::fromConfig();


     $last_product= DB::table('products')->orderBy('shopify_update_at', 'DESC')->first();
     $last_updated_time=$last_product->shopify_update_at;

     $products = $this->shopify->getProducts(['limit' => 250,'updated_at_min'=>$last_updated_time]); 
    
     
         foreach ($products as $key => $value) {
                      
                $prodata = array();
                
                $prodata['title'] = $value['title'];
                $prodata['shopify_create_at'] = $value['created_at'];
                $prodata['shopify_update_at'] = $value['updated_at'];

                if (!empty($value['variants'])) {
                   
                    foreach ($value['variants'] as $variant) {
                       $prodata['sku'] = $variant['sku'];
                       $prodata['barcode'] = $variant['barcode'];
                    }
                    
                }

                 if (!empty($value['image'])) {
                    $prodata['image'] = $value['image']['src'];
                 }
                $check_record=$this->check_product_exits($value['id']);
                if($check_record > 0){
                 DB::table('products')->where('product_id', $value['id'])->update($prodata);
                }
            
          }
        echo "<p style='color:green;text-align:center;'>Product updated Successfully</p>";
   
    
    
    }
// End fetch updated Product from shopify and update into database 


// Fetch data from shopify store and then store order into database
   public function fetch_insert_new_order(){
  
    set_time_limit(500);
     $total_orders = $this->shopify->get('orders/count.json?status=any')->json();
     
     $last_order= DB::table('order_details')->orderBy('order_id', 'DESC')->first(); //print_r($last_order); die;
     if(!empty($last_order)){
     $last_order_id=$last_order->order_id;
     }else{
        $last_order_id="1";
     }
     $order_array = $this->shopify->get('orders.json?since_id='.$last_order_id.'&limit=200&status=any')->json();
     //$order_array = $this->shopify->get('orders.json?created_at_min=2005-07-31T15:57:11-04:00&limit=25')->json();
     
         if(!empty($order_array['orders'])){
              foreach ($order_array['orders'] as $key => $value) {
                // echo "<pre>";
                // print_r($value);
                //    die();
                    $orderdata['order_id'] = $value['id'];
                    $orderdata['order_no'] = $value['name'];
                    $orderdata['note'] = $value['note'];
                    $orderdata['customer_name'] = isset($value['customer']) ? ($value['customer']['first_name']." ".$value['customer']['last_name']) : '-';
                    
                    
                    $risk_array = $this->shopify->get('orders/'.$value['id'].'/risks.json')->json();
                    //echo '<pre>';print_r($risk_array->json()); die;
                    $risk_details = isset($risk_array['risks'][0]['score']) ? $risk_array['risks'][0]['score'] : '';
                    if($risk_details == 0){
                        $risk = 'Low';
                    }elseif($risk_details >= 0.5){
                        $risk = 'Medium';
                    }elseif($risk_details > 0.5){
                        $risk = 'High';
                    }else{
                        $risk = '-';
                    }
                    $orderdata['risk'] = $risk;
                    $orderdata['order_created'] = $value['created_at'];
                    // $orderdata['order_update_at'] = $value['updated_at'];

                    $ship_type = isset($order_array['orders'][0]['shipping_lines'][0]['title']) ? $order_array['orders'][0]['shipping_lines'][0]['title'] : '';
                    $orderdata['ship_type'] = $ship_type;

                    //$payment_status = financial_status;
                    $orderdata['payment_status'] = $value['financial_status'];

                    if(isset($value['shipping_address'])){
                    $orderdata['country'] = $value['shipping_address']['country'];
                     }
                    $item_array=$value['line_items'];

                    foreach ($item_array as $key => $items) {
                    
                    $orderdata['line_item_id'] = $items['id'];
                    $orderdata['product_id'] = $items['product_id'];
                    $orderdata['product_title'] = $items['title'];
                    $orderdata['quantity'] = $items['quantity'];
                    $orderdata['sku'] = $items['sku'];
                     $orderdata['gift_card'] = $items['gift_card'];
                    $orderdata['item_status'] = !empty($items['fulfillment_status']) ? $items['fulfillment_status'] : 'unfulfilled';
                    $orderdata['order_status'] = !empty($value['fulfillment_status']) ? $value['fulfillment_status'] : 'unfulfilled';
                    $orderdata['fulfillable_quantity'] = $items['fulfillable_quantity'];
                     $orderdata['closed_at'] = $value['closed_at'];
                     $orderdata['cancelled_at'] = $value['cancelled_at'];
                     $orderdata['pickup_Date'] = isset($value['note_attributes'][1]['value']) ?  $value['note_attributes'][1]['value']: '';
                     DB::table('order_details')->insert($orderdata);

                    }

                    
                     
              }
          
               echo "<p style='color:green;text-align:center;'>Orders Added Successfully</p>";
               echo $orderdata['product_id'];
           
         }else{
              echo "<p style='color:red;text-align:center;'>No Orders Found</p>";
         }
       echo "<p style='color:blue;text-align:center;'>Total Orders On Shopify:".$total_orders['count']."</p>";
    
    
    }
// End  add order   function


//Start fetch updated order from shopify and update into database 
   public function fetch_update_order(){
    
     $shopify = \Signifly\Shopify\Factory::fromConfig();
     $last_order= DB::table('order_details')->orderBy('order_update_at', 'DESC')->first();
     $last_update_at=$last_order->order_update_at;
    
     $order_array = $this->shopify->get('orders.json?updated_at_min='.$last_update_at.'&limit=225')->json();
      foreach ($order_array['orders'] as $key => $value) {
                    
                    $orderdata = array();
                 
                    $orderdata['order_id'] = $value['id'];
                    $orderdata['order_no'] = $value['order_number'];
                    $orderdata['note'] = $value['note'];
                    $orderdata['customer_name'] = isset($value['customer']) ? ($value['customer']['first_name']." ".$value['customer']['last_name']) : ' ';
                    
                    $risk_array = $this->shopify->get('orders/'.$value['id'].'/risks.json');
                    
                    $risk_details = isset($risk_array['risks'][0]['score']) ? $risk_array['risks'][0]['score'] : '';
                    if($risk_details == 0){
                        $risk = 'Low';
                    }elseif($risk_details >= 0.5){
                        $risk = 'Medium';
                    }elseif($risk_details > 0.5){
                        $risk = 'High';
                    }else{
                        $risk = '-';
                    }

                    $orderdata['risk'] = $risk;
                    $orderdata['order_created'] = $value['created_at'];
                    $orderdata['order_update_at'] = $value['updated_at'];

                    $ship_type = isset($order_array['orders'][0]['shipping_lines'][0]['title']) ? $order_array['orders'][0]['shipping_lines'][0]['title'] : '';
                    $orderdata['ship_type'] = $ship_type;

                    //$payment_status = financial_status;
                    $orderdata['payment_status'] = $value['financial_status'];

                    if(isset($value['shipping_address'])){
                    $orderdata['country'] = $value['shipping_address']['country'];
                     }
                    $item_array=$value['line_items'];
                    
                    $check_record=$this->check_order_exits($value['id']);
                    if($check_record > 0){

                    DB::table('order_details')->where('order_id', $value['id'])->delete();
                   
                    foreach ($item_array as $key => $items) {
                    $orderdata['line_item_id'] = $items['id'];
                    $orderdata['product_id'] = $items['product_id'];
                    $orderdata['product_title'] = $items['title'];
                    $orderdata['quantity'] = $items['quantity'];
                    $orderdata['sku'] = $items['sku'];
                    $orderdata['gift_card'] = $items['gift_card'];
                    $orderdata['item_status'] = !empty($items['fulfillment_status']) ? $items['fulfillment_status'] : 'unfulfilled';
                    $orderdata['order_status'] = !empty($value['fulfillment_status']) ? $value['fulfillment_status'] : 'unfulfilled';
                    DB::table('order_details')->insert($orderdata);

                     }
                    }
 
                    
                     
              }
               echo "<p style='color:green;text-align:center;'>Orders Updated Successfully</p>";
    }
// End fetch updated order from shopify and update into database





//check order
   public function check_order_exits($order_id){
        $count_record= DB::table('order_details')->where('order_id', $order_id)->count();
        return $count_record;
   }
// End check id

//check Product
   public function check_product_exits($product_id){
        $count_record= DB::table('products')->where('product_id', $product_id)->count();
        return $count_record;
   }
// End Product id


    public function set_webhook(){
        /*  Order UPDATED  */
        echo '<br><br>Order UPDATED<br>';
        $payload["webhook"] = [ 
                                "topic" => "orders/updated",
                                "address" => "https://shopify-order.ibrcloud.com/webhook-order-update",
                                "format" => "json",
                                "fields" => []
                                ];
        $response = $this->shopify->post('webhooks.json', $payload);
        print_r($response->json());
        
    }
        
   

    public function show_list_webhook(){
        echo '<pre>';
        echo '<br><br>List of all webhooks<br>';
        $response = $this->shopify->get('webhooks.json');
        print_r($response->json());
       
    }

    public function update_webhook(){
    //"topics" => ["orders/partially_fulfilled","fulfillments/update"],
       $payload["webhook"] = 
          [
              "topic" => "orders/updated",
              //"address" => "https://shopify-order.ibrcloud.com/webhook-order-update",
              "address" => "https://shopify-order.houseofknives.com.au/webhook-order-update",
              "format" => "json",
              "fields" => []
            
          ];
       $response = $this->shopify->put('webhooks/1030495076450.json',$payload);
        echo '<pre>';
        print_r($response->json());
    }

    public function delete_webhook(Request $request){
        $webhook_id = $request->id;
        $response = $this->shopify->delete('webhooks/'.$webhook_id.'.json');
        echo '<pre>';
        print_r($response->json());   
    }



    //handle webhook notification of order cancel
    public function webhook_handle_order_notification(){
    
         $order_data = file_get_contents('php://input');
		
         if(!empty($order_data)){
            
		    //date_default_timezone_set('Asia/Kolkata');
            //$currentTime = date( 'd-m-Y h:i:s A', time ()); 
            //$myfile = file_put_contents('webhook_log.txt', '*------*'.$currentTime.' : '.$order_data.'----END----'.PHP_EOL , FILE_APPEND | LOCK_EX);
            
            $value = (array)json_decode($order_data);
         
          // echo '<pre>'; print_r($value); die('die here');
            
            $check_record=$this->check_order_exits($value['id']);
            if($check_record > 0){ //die('order id present');
                        $orderdata = array();
                        $orderdata['order_id'] = $value['id'];
                        $orderdata['order_no'] = $value['name'];
                        $orderdata['note'] = $value['note'];
                        $orderdata['customer_name'] = isset($value['customer']) ? ($value['customer']->first_name." ".$value['customer']->last_name) : '';
                        
                        $risk_array = $this->shopify->get('orders/'.$value['id'].'/risks.json');
                        
                        $risk_details = isset($risk_array['risks'][0]['score']) ? $risk_array['risks'][0]['score'] : '';
                        if($risk_details == 0){
                            $risk = 'Low';
                        }elseif($risk_details >= 0.5){
                            $risk = 'Medium';
                        }elseif($risk_details > 0.5){
                            $risk = 'High';
                        }else{
                            $risk = '-';
                        }

                        $orderdata['risk'] = $risk;
                        $orderdata['order_created'] = $value['created_at'];
                        $orderdata['order_update_at'] = $value['updated_at'];

                        $ship_type = (count($value['shipping_lines']) > 0) ? $value['shipping_lines'][0]->title : '';
                        $orderdata['ship_type'] = $ship_type;

                        //$payment_status = financial_status;
                        $orderdata['payment_status'] = $value['financial_status'];

                        if(isset($value['shipping_address'])){
                            $value['shipping_address'] = (array)$value['shipping_address'];
                        $orderdata['country'] = $value['shipping_address']['country'];
                         }
                        $item_array=(array)$value['line_items'];

                         DB::table('order_details')->where('order_id', $value['id'])->delete();

                        foreach ($item_array as $key => $items) {
                            
                            $items = (array)$items;
                            $orderdata['line_item_id'] = $items['id'];
                            $orderdata['product_id'] = $items['product_id'];
                            $orderdata['product_title'] = $items['title'];
                            $orderdata['quantity'] = $items['quantity'];
                            $orderdata['sku'] = $items['sku'];
                            $orderdata['gift_card'] = $items['gift_card'];
                            $orderdata['item_status'] = !empty($items['fulfillment_status']) ? $items['fulfillment_status'] : 'unfulfilled';
                            $orderdata['order_status'] = !empty($value['fulfillment_status']) ? $value['fulfillment_status'] : 'unfulfilled';
                            $orderdata['fulfillable_quantity'] = $items['fulfillable_quantity'];
                             $orderdata['closed_at'] = $value['closed_at'];
                             $orderdata['cancelled_at'] = $value['cancelled_at'];

                             DB::table('order_details')->insert($orderdata);
                        // DB::table('order_details')->where("line_item_id",$orderdata['line_item_id'])->update($orderdata);
                        }
     
            }
        }
    }

}