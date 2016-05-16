<?php
function sendSms($number, $message)
{

    include app_path().'/libraries/DialogAPI/'.'IdeaBizAPIHandler.php';

    $auth = new IdeaBizAPIHandler();


    $mobile  = ltrim($number, '0');

    $senderName = "";
    $sender  = "94767676999";

    if (in_array(substr($mobile, 0, 2), ['77', '76']))
        $senderName  = "ThePapare";


    $message = $message;
    $msisdn  = "tel:+94{$mobile}";

    $args['outboundSMSMessageRequest'] = array(
        "address" =>
            array($msisdn),
        "senderAddress" =>
            "tel:{$sender}",
        "clientCorrelator" => "",
        "outboundSMSTextMessage" =>
            array("message" => $message),
        "receiptRequest" =>
            array('notifyURL' => ' ', "callbackData" => ' '),
        "senderName" => "{$senderName}"
    );

    $data_string = json_encode($args);
    $url = "https://ideabiz.lk/apicall/smsmessaging/v2/outbound/87702/requests";
    $a = $auth->sendAPICall($url, RequestMethod::POST, $data_string);
    return true;
}

function sendPush($title,$message)
{
    ob_start();
    $url = 'https://api.parse.com/1/push';
    $appId = 'yjTqvA88MhdwjMJnxoSUWzpnPlneIAD1Jb0u7fbo';
    $restKey = '2S2RMxabvv0PPHun6n1Jnu8Ie20yyYj7Aow4kwB6';

    $push_payload = json_encode(array("where" => '{}',
        "data" => array("alert" => array('type'=>'messsage',
            'data'=>array('title'=>$title,
                'message'=>$message
            )))));

    $rest = curl_init();
    curl_setopt($rest, CURLOPT_URL, $url);
    curl_setopt($rest, CURLOPT_PORT, 443);
    curl_setopt($rest, CURLOPT_POST, 1);
    curl_setopt($rest, CURLOPT_POSTFIELDS, $push_payload);
    curl_setopt($rest, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($rest, CURLOPT_HTTPHEADER, array(
        "X-Parse-Application-Id: " . $appId,
        "X-Parse-REST-API-Key: " . $restKey,
        "Content-Type: application/json"));
    curl_setopt($rest, CURLOPT_RETURNTRANSFER, 1);

    return json_encode(curl_exec($rest));

    if (curl_errno($rest)) {

        $this->response($this->json(array('success' => 'false', 'error' => array('code' =>
            curl_errno($rest), 'message' => curl_error($rest)))), 500);
    } else {

        return json_encode(curl_errno($rest));
    }
    curl_close($rest);
}