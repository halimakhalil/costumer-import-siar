<?php

namespace App\Imports;

use App\Models\CustomerImport;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Shopify\Auth\OAuth;
use Shopify\Auth\Session as AuthSession;
use Shopify\Clients\HttpHeaders;
use Shopify\Clients\Rest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Events\AfterImport;

class ImportCustomers implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $client = null;
    public function  __construct($client,$session_id)
    {
        $this->client= $client;
        $this->session_id= $session_id;
    }

    public function model(array $row)
    {
       
        // exit();
        // $search_customer = $this->client->get('customers/search.json?query='.$row['email'])->getDecodedBody();
        $customer_created = $this->client->post('customers',[
            'customer' => 
                        array (
                            'first_name' => $row['first_name'],
                            'last_name' => $row['last_name'],
                            'email' => $row['email'],
                            'phone' => $row['phone'],
                            'addresses' => 
                            array (
                            0 => 
                            array (
                                'address1' => $row['address1'],
                                'city' => $row['city'],
                                'province' => $row['province'],
                                'province_code' =>$row['province_code'],
                                'zip' => $row['zip'],
                                'country' => $row['country'],
                                'country_code' => $row['country_code']
                            ),
                            ),
                            'accepts_email_marketing' => $row['accepts_email_marketing'],
                            'accepts_sms_marketing' => $row['accepts_sms_marketing'],
                            'tags' => 'customer created by app'                        ),
            ])->getDecodedBody();
            //   \Log::info($customer_created);
              if(isset($customer_created['errors'])){
                if(isset($customer_created['errors']['email']))
                \Log::info('-----------error email--------------');
                \Log::info($row['email']);
                \Log::info($customer_created['errors']);
                \Log::info('-----------error email--------------');
                // \Log::info($customer_created['errors']['0']['0']);
              }
              else{
                \Log::info('--------------no error---------');
                \Log::info($row['email']);
                \Log::info('--------------no error---------');
              }
            
    }



    public static function afterImport(AfterImport $event)
    {
        \Log::info('________________import finished_______________');
        
    }
}
