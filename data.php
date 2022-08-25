<?php
    function Get_IP($url,$api_key,$protocol){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = <<<DATA
        {
            "api_key": "$api_key",
            "sign": "string",
            "id_location": 0
        }
        DATA;

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }
    
    $protocol= $_GET['protocol'];
    $data=Get_IP("https://tmproxy.com/api/proxy/get-current-proxy",$_GET['api-key'],$protocol);
    $data = json_decode($data, true);
    $timeout= $data['data']['timeout'];
    $next_request= $data['data']['next_request'];   
     
     if ($timeout<=0 or $next_request ==0 ){
        $data_new=Get_IP("https://tmproxy.com/api/proxy/get-new-proxy",$_GET['api-key'],$protocol);
	sleep(1);
        $data_new = json_decode($data_new, true);
        if ($protocol=="socks5"){
            echo $data_new['data']['socks5'];
            }
            elseif ($protocol=="https"){
                echo $data_new['data']['https'];
            }
     }
     elseif ($timeout>0){
        if ($protocol=="socks5"){
        echo $data['data']['socks5'];
        }
        elseif ($protocol=="https"){
            echo $data['data']['https'];
        }
     }
?>