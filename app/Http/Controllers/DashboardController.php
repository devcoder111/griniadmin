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


class DashboardController extends Controller
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

    public function index(Request $request)
    {   
        // Helpers::pp("pradeep");
        
        
        

        

        //$shopify = app('shopify');
        // Get products
        //$shopify = \Signifly\Shopify\Factory::fromConfig();
        echo '<pre>';

            // $products = $this->shopify->get('orders.json')->json();print_r($products); die;
        $r = $this->shopify->getOrder('4570745209029'); print_r($r);die;

        //https://mind-body-foods.myshopify.com/admin/api/2021-10/orders/4164206887010/risks.json
                $products = $shopify->get('orders.json?status=any');
                print_r($products);
        die;
                //$results = collect();
        print_r($products);
        //$products = $shopify->getProducts();
        //Helpers::pp($results);
        
        die;

        //Helpers::pp($products);


        die('ss');
        $data['page_name']  = "Dashboard";
        $data['header']  = "";
        $data['slot']  = "";
        return view('pages.dashboard', $data);
    }


}