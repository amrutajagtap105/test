<?php

if (!function_exists('sendmail')) {

    function sendmail($to, $subject, $message, $cc = "", $bcc = "", $attachment = "", $messagetext = "", $server = "", $port = "", $ssl = "", $email = "", $user = "", $password = "", $embedattach = "") {
        $url = "https://email.rtac.in/sendmail.php";
        if (empty($server)) {
            $server = 'smtp.yandex.com';
            $port = '587';
            $email = 'noreply@rtac.in';
            $user = 'noreply@rtac.in';
            $password = '3e5DcE60XF2t';
            $ssl = 'tls';
        }
        $params = array('domain' => $server,
            'subject' => $subject,
            'message' => $message,
            'to' => $to,
            'replyto' => $email,
            'emailhost' => $server,
            'emailport' => $port,
            'emailid' => $email,
            'emailuser' => $user,
            'emailpassword' => $password,
            'emailssl' => $ssl,
            'attachment' => $attachment
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $buffer = curl_exec($ch);
        
        if (curl_errno($ch)) {
        $error_msg = 'cURL error: ' . curl_error($ch);
        curl_close($ch);
        return $error_msg;
        }

        curl_close($ch);    
        
        
        if (empty($buffer)) {
            return " buffer is empty ";
        } else {
            if ($buffer == 'OK') {
                return 'ok';
            } else {
                //echo $buffer;
                return $buffer;
            }
        }
        curl_close($ch);
    }

}