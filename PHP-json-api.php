<?php
//BulkSMS send SMS function
function bulksms_jsonapi($data_array)
{
    $api_key = $data_array['username'];
    $password = $data_array['password'];
    //Build json
    $data_string = '{
    "from": "' . $data_array['from'] . '",
    "to": "' . $data_array['to'] . '",
    "body": "' . $data_array['message'] . '"
	}';
    //connecting to json API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.bulksms.com/v1/messages");
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $api_key . ':' . $password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
        'Content-Type: application/json')
    );
    $result = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($http == 201) {
        if ($result != '') {
            $res = json_decode($result);
            if (isset($res[0]) && $res[0]->type == 'SENT') {
                //sent success
                return 'Message sent successfully';
            } else {
                //sent error
                return 'error : ' . $res->status;
            }
        } else {
            //error on connection
            return 'Error on connection to bulkSMS';
        }
    } else {
        //user or pass wrong
        return 'Username or password is wrong';
    }
}
$data_array = array('to' => 'Mobile_number', 'from' => 'Your_Sender_ID', 'message' => 'Hello!', 'username' => 'Your_Username', 'password' => 'Your_Password');
$result = bulksms_jsonapi($data_array);
echo $result;