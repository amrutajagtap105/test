<?php

include_once("guid.php");
include_once("sendmail.php");

function sendsmsprovider($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid,  $companyid, $smsprovider, $templateid, $peid, $txtlocalkey,$smslanguage = 'eng', $debug = false) {
    if ($debug) {
        recorddie(__LINE__, __FILE__, $to . "\r " . $message . "\r " . $notification . "\r " . $notificationtitle . "\r " . $user . "\r " . $password . "\r " . $senderid . "\r " . $mobileid . "\r " . $smslanguage . "\r " . $companyid . "\r " . $smsprovider);
    }

    $action = 'notification';
    if ($notification == '') {
        $action = 'sms';
//recorddie(__LINE__, __FILE__, "notification message is blank");
    } else {
//        $q = "select id,messageid from clientapp where phonenumber='$to' and active = 1";
//        if (!$req = mysqli_query($link, $q)) {
//            $err = mysqli_error($link);
//            recorddie(__LINE__, __FILE__, $q . $err);
//            die('SYSTEM ERROR. Error in database');
//        }

        $q = "select id,messageid from clientapp where phonenumber=? and active = 1";
        if (!$stmt = $link->prepare($q)) {
            recorddie(__LINE__, __FILE__, $q . ' ' . $link->error);
            die('SYSTEM ERROR. Could Not Get Information');
        }
        $stmt->bind_param('s', $to);
        if (!$stmt->execute()) {
            recorddie(__LINE__, __FILE__, $stmt->error);
            die('SYSTEM ERROR. Error in database');
        }

//        $req = $stmt->get_result();
        $stmt->bind_result($id, $messageid);
        if (!$stmt->fetch()) {
            $stmt->close();
            $action = 'sms';
        }
        $stmt->close();

        if ($messageid == '') {
            $action = 'sms';
        } else {
            $clientappid = $id;
        }

//        if (!$row = mysqli_fetch_assoc($req)) {
//            $action = 'sms';
//        } else if ($row['messageid'] == '') {
//            $action = 'sms';
////            recorddie(__LINE__, __FILE__, $q);
//        } else {
//            $clientappid = $row['id'];
//        }
        $registrationIds = $messageid;
    }
    if ($action == 'notification') {

//        $q = "select * from societies.notificationpack where societyid=$companyid and qty > used";
//        if (!$req = mysqli_query($link, $q)) {
//            $err = mysqli_error($link);
//            recorddie(__LINE__, __FILE__, $q . $err);
//            die('SYSTEM ERROR. Error in database');
//        }
        $q = "select * from societies.notificationpack where societyid=? and qty > used";
        if (!$stmt = $link->prepare($q)) {
            recorddie(__LINE__, __FILE__, $q . ' ' . $link->error);
            die('SYSTEM ERROR. Could Not Get Information');
        }
        $stmt->bind_param('i', $companyid);
        if (!$stmt->execute()) {
            recorddie(__LINE__, __FILE__, $stmt->error);
            die('SYSTEM ERROR. Error in database');
        }

        $req = $stmt->get_result();
        $data = $req->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        if (checkCount($data) < 1) {
            $action = 'sms';
            $result = sendmail('support@rtac.in', 'Notification pack is exhausted', "Company id $companyid notification pack is exhausted", '', '', '', 'Please read this email in a html compliant email client', '', '', '', '', '', '');
        }
    }
    if (isset($mobileid)) {
        $midfield = "unhex('$mobileid')";
    } else {
        $midfield = 'null';
    }
    if ($action == 'notification') {
        sendNotification($notification, $notificationtitle, $companyid, $registrationIds, $clientappid, $mobileid, $link,$debug);
        return;
    }
    $buffer = '';
    if (checkstrlength($senderid) === 0 || checkstrlength($user) === 0 || checkstrlength($password) === 0 || checkstrlength($templateid) == 0 || checkstrlength($smsprovider) == 0) {
        return;
    }
    if ($debug) {
        recorddie(__LINE__, __FILE__, $smsprovider);
    }
    switch ($smsprovider) {
        case 'None':
            $buffer = '';
            break;
        case 'mVaayoo':
            $buffer = sendsms($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $companyid, $templateid,$smslanguage, $debug);
            break;
        case 'valufirst':
            $buffer = sendsmsvalufirst($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $debug);
            break;
        case 'textlocal':
            $buffer = sendsmsTextLocal($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $txtlocalkey, $debug);
            break;
        case 'DoveSoft':
            $buffer = sendsmsDoveSoft($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $peid, $debug);
            break;
        case 'valuefirsttoken':
            $buffer = sendsmsvalufirsttoken($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $debug);
            break;
        case '360marketing':
            $buffer = sendsms360Marketing($to, $message, $user, $password, $senderid, $templateid, $peid);
            break;
        default:
            $buffer = sendsms($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $companyid, $templateid, $smslanguage, $debug);
            break;
    }
    if (empty($buffer)) {
        echo " buffer is empty ";
//        recorddie(__LINE__, __FILE__, 'empty ' . $to . ' ' . $message . ' ' . $smsprovider . ' ' . $user . ' ' . $password . ' ' . $senderid . ' ' . $mobileid . ' ' . $smslanguage . ' ' . $companyid . ' ' . $templateid);
    } else {
//        $q = "insert into smslog (message,phonenumber,result,mobileid,provider) values ('$message','$to','$buffer',$midfield,'$smsprovider')";
//        if (!$req = mysqli_query($link, $q)) {
//            $err = mysqli_error($link);
//            recorddie(__LINE__, __FILE__, $q . $err);
//        }

        $q = "insert into smslog (message,phonenumber,result,mobileid,provider) values (?,?,?,?,?)";
        if (!$stmt = $link->prepare($q)) {
            recorddie(__LINE__, __FILE__, $q . ' ' . $link->error);
            die('SYSTEM ERROR. Could Not Get Information');
        }
        $stmt->bind_param('sssis', $message, $to, $buffer, $midfield, $smsprovider);
        if (!$stmt->execute()) {
            recorddie(__LINE__, __FILE__, $stmt->error);
            die('SYSTEM ERROR. Error in database');
        }

        $stmt->close();
    }
}

function sendsms360Marketing($to, $message, $user, $password, $senderid, $templateid, $peid) {
    $userencoded = urlencode($user);
    $messageencoded = urlencode($message);

    $url = "http://164.52.205.46:6005/api/v2/SendSMS?SenderId=$senderid&Is_Unicode=false&Is_Flash=false&Message=$messageencoded&MobileNumbers=$to&PrincipleEntityId=$peid&TemplateId=$templateid&ApiKey=$userencoded&ClientId=$password";

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'accept: text/plain',
        ),
            )
    );
    $response = curl_exec($curl);
    curl_close($curl);
    if (checkstrlength($response) == 0) {
        return '';
    }

    $resp = json_decode($response);
    return $resp->Data[0]->MessageId;
}

function sendsmsTextLocal($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $txtlocalkey, $debug) {

    $apiKey = urlencode($txtlocalkey);
    $numbers = array($to);
    $sender = urlencode($senderid);
    $message = rawurlencode($message);
    $numbers = checkImplode(',', $numbers);
    $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
    $ch = curl_init('https://api.textlocal.in/send/');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $y = json_decode($response);
    if (isset($y->messages[0])) {
        if (isset($y->status)) {
            if ($y->status == 'success') {
                return $y->messages[0]->id;
            } else {
                return $y->status;
            }
        } else {
            return 'Message without status!!!';
        }
    } else {
        return $response;
    }
}

function sendsmsvalufirsttoken($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $debug) {
    $mess = rawurlencode($message);
    $deliveryurl = rawurlencode('https://apps.goultimus.in/common/deliveryupdate.php?societyid=' . $companyid . '&mobileid=' . $mobileid);
    $url = 'https://http.myvfirst.com/smpp/sendsms?to=' . $to . '&from=' . $senderid . '&text=' . $mess . '&dlr_url=' . $deliveryurl;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $password
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    if ($response == 'Sent.') {
        echo 'NoUpdate';
    } else {
        echo $response;
    }
}

function sendsmsvalufirst($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $debug) {
    $mess = $message;

    $cmd = "curl -v https://http.myvfirst.com/smpp/sendsms --data-urlencode \"username=$user\"  --data-urlencode \"password=$password\"  --data-urlencode \"text=$mess\" --data-urlencode \"state=4\" --data-urlencode \"from=$senderid\" --data-urlencode \"to=$to\"   --data-urlencode \"category=bulk\"    --data-urlencode \"templateid=$templateid\"  --max-time 5  2>&1";
    exec($cmd, $output);
    if ($debug) {
        recorddie(__LINE__, __FILE__, $cmd);
    }
    return $output[checkCount($output) - 1];
}

function sendsms($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $companyid, $templateid, $smslanguage = 'eng', $debug = false) {
    $ch = curl_init();
    $user = urlencode($user . ':' . $password);
    $to = urlencode($to);
    $senderid = urlencode($senderid);
    $toraw = $to;
    $admin = urlencode('admin@rtac.in');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    if (is_null($smslanguage) || $smslanguage == 'eng') {
        $message = urlencode($message);
    } else {
        $message16 = mb_convert_encoding($message, "UTF-16", "UTF-8");
        $messagehex = strhex($message16);
        $message = "$messagehex&msgtype=4&dcs=8&ishex=1";
    }


    $url = "http://59.162.167.52/api/MessageCompose?admin=$admin&user=$user&senderID=$senderid&receipientno=$to&msgtxt=$message&state=4&template_id=$templateid";
    curl_setopt($ch, CURLOPT_URL, $url);
    $buffer = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        recorddie(__LINE__, __FILE__, 'Timeout in sms');
        return 'timeout';
    } else {
        curl_close($ch);
        return $buffer;
    }
}

function sendNotification($notification, $notificationtitle, $companyid, $registrationIds, $clientappid, $mobileid, $link, $debug) {
    if(!defined("API_ACCESS_KEY")){ 
        define('API_ACCESS_KEY', 'AAAAbBGPecw:APA91bEwCaHACvXkHPDNI6b-02_xyND42cWt6uw-vm2-QnvDEMBJ0aW3KezdG2RTqVOtCvrV6AaV5kuw3feq_iGxMbV_-EC9iaZF2bmx3qa47Uvnp2vETJCMUV5GFKua5yqDyStK6U_r');
    }    
    
//        $registrationIds = "cdmlvuVbbck:APA91bEwke-kXMrJunbeMYZEPHgYxJNirZF9TC9DOc-IAHyEe0Lx4WZVPsrYFUCJCEXyGl2j5y3AboQySlnwzM0Gac1h7ehb1USTn3m8sgRtPSLTpeC30DlMr0CGOS044XcpntuDRthU";
#prep the bundle
    /*$msg = array
        (
        'body' => $notification,
        'title' => $notificationtitle,
        'icon' => 'https://apps.goultimus.in/images/sahakarlogo.png', // Default Icon 
        'sound' => 'mySound' // Default sound 
    );
    $guid = getGUID();
    $data = array(
        "guid" => "$guid",
        "messagetext" => $notification,
        "messagetitle" => $notificationtitle,
        "companyid" => $companyid
    );
    $fields = array
        (
        'to' => $registrationIds,
        'notification' => $msg,
        'data' => $data
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
#Send Reponse To FireBase Server	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);*/
#Echo Result Of FireBase Server
//        echo $result;
    
   $guid = getGUID();
   $fields = array
        (
        'token' => $registrationIds,
        'body' => $notification,
        'title' => $notificationtitle
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://ultimusconnect.com/sendnotification.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
    curl_close($ch);
    
    $q = "update societies.notificationpack set used = used+1 where societyid=? and qty > used and last_insert_id(id) order by id limit 1";
      if (!$stmt = $link->prepare($q)) {
        recorddie(__LINE__, __FILE__, $q . ' ' . $link->error);
        die('SYSTEM ERROR. Could Not Get Information');
    }
    $stmt->bind_param('i', $companyid);
    if (!$stmt->execute()) {
        recorddie(__LINE__, __FILE__, $stmt->error);
        die('SYSTEM ERROR. Error in database');
    }
    $packid = mysqli_insert_id($link);
    $stmt->close();
    
    $q = "insert into notificationlog (
clientappid,
datetime,
mobileid,
guid,
message,
title,
packid
) values (
?,
now(),
unhex(?),
unhex(?),
?,
?,
?
)";
    if (!$stmt = $link->prepare($q)) {
        recorddie(__LINE__, __FILE__, $q . ' ' . $link->error);
        die('SYSTEM ERROR. Could Not Get Information');
    }
    $stmt->bind_param('issssi', $clientappid, $mobileid, $guid, $notification, $notificationtitle, $packid);
    if (!$stmt->execute()) {
        recorddie(__LINE__, __FILE__, $stmt->error);
        die('SYSTEM ERROR. Error in database');
    }
    $stmt->close();
}

function sendsmsDoveSoft($to, $message, $notification, $notificationtitle, $user, $password, $senderid, $link, $mobileid, $smslanguage, $companyid, $templateid, $peid) {
    $mess = $message;
    $cmd = "curl  http://redirect.ds3.in/submitsms.jsp --data-urlencode \"user=$user\"  --data-urlencode \"key=$password\"  --data-urlencode \"mobile=+91$to\" --data-urlencode \"message=$message\" --data-urlencode \"senderid=$senderid\" --data-urlencode \"accusage=1\"   --data-urlencode \"entityid=$peid\"    --data-urlencode \"tempid=$templateid\" 2>&1";
    exec($cmd, $output);
    foreach ($output as $currentoutput) {
        $smsoutput = checkExplode(',', $currentoutput);
        if (checkCount($smsoutput) > 1) {
            return $smsoutput[2];
        }
    }
    return '';
}

function sendConnectNotification($notification, $notificationtitle, $companyid, $registrationIds, $clientappid, $link) {
    if (!defined('API_ACCESS_KEY')) {
        define('API_ACCESS_KEY', 'AAAAbBGPecw:APA91bEwCaHACvXkHPDNI6b-02_xyND42cWt6uw-vm2-QnvDEMBJ0aW3KezdG2RTqVOtCvrV6AaV5kuw3feq_iGxMbV_-EC9iaZF2bmx3qa47Uvnp2vETJCMUV5GFKua5yqDyStK6U_r');
    }
    if(!isset($clientappid)){
        return;
    }
//        $registrationIds = "cdmlvuVbbck:APA91bEwke-kXMrJunbeMYZEPHgYxJNirZF9TC9DOc-IAHyEe0Lx4WZVPsrYFUCJCEXyGl2j5y3AboQySlnwzM0Gac1h7ehb1USTn3m8sgRtPSLTpeC30DlMr0CGOS044XcpntuDRthU";
#prep the bundle
    /*$msg = array
        (
        'body' => $notification,
        'title' => $notificationtitle,
        'icon' => 'https://apps.goultimus.in/images/sahakarlogo.png', // Default Icon 
        'sound' => 'mySound' // Default sound 
    );
    $guid = getGUID();
    $data = array(
        "guid" => "$guid",
        "messagetext" => $notification,
        "messagetitle" => $notificationtitle,
        "companyid" => $companyid
    );
    $fields = array
        (
        'to' => $registrationIds,
        'notification' => $msg,
        'data' => $data
    );

    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
#Send Reponse To FireBase Server	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);*/
#Echo Result Of FireBase Server
//        echo $result;
    
   if(checkstrlength($clientappid) == 0 || $clientappid == null){
       return;
   } 
    
   $guid = getGUID();
   $fields = array
        (
        'token' => $registrationIds,
        'body' => $notification,
        'title' => $notificationtitle
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://ultimusconnect.com/sendnotification.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
    curl_close($ch);
    
    $q = "insert into connectnotificationlog (
        appid,
        datetime,
        message,
        title
        ) values (
        ?,
        now(),
        ?,
        ?
        )";
    if (!$stmt = $link->prepare($q)) {
        recorddie(__LINE__, __FILE__, $q . ' ' . $link->error);
        die('SYSTEM ERROR. Could Not Get Information');
    }
    $stmt->bind_param('iss', $clientappid, $notification, $notificationtitle);
    if (!$stmt->execute()) {
        recorddie(__LINE__, __FILE__, $stmt->error);
        die('SYSTEM ERROR. Error in database');
    }
    $stmt->close();
}

function strhex($string) {
    $hexstr = unpack('H*', $string);
    return array_shift($hexstr);
}
