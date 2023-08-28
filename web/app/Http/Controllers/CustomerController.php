<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\ImportCustomers;
use Illuminate\Http\Request;
use Shopify\Clients\Rest;
use App\Models\Session;

class CustomerController extends Controller
{
    public function import(Request $request){
        \Log::info('inside controller');
        $session = $request->get('shopifySession');
        $client = new Rest($session->getShop(), $session->getAccessToken());
        // $search_customer = $client->get('customers')->getDecodedBody();
        //       \Log::info($search_customer);
        // $shop = $session->getShop();
        $session_id = Session::where('shop',$shop)->value('id');
        $array = Excel::toArray(new ImportCustomers($client,$session_id), request()->file('myfile'));
        Excel::import(new ImportCustomers($client,$session_id),request()->file('myfile'));      
        
        return back();
        // return $search_customer;
    }
}
