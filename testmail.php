<?php 
function guimail($usermail, $passmail, $emailArray, $subject, $content, $to, $from) 
{
	require 'mailer/SendMail.php';

    $sendMail = new SendMail($usermail, $passmail);

    $sendMail->send($subject, $content, $to, $from);
}




function kiemtraemail($toemail)
{
	$fromemail = 'tranhung596@gmail.com';
	$getdetails = false;
  
    $email_arr = explode('@', $toemail);
    $domain = array_slice($email_arr, -1);
    $domain = $domain[0];

  
    $domain = ltrim($domain, '[');
    $domain = rtrim($domain, ']');

    if ('IPv6:' == substr($domain, 0, strlen('IPv6:'))) {
        $domain = substr($domain, strlen('IPv6') + 1);
    }

    $mxhosts = array();
        
    if (filter_var($domain, FILTER_VALIDATE_IP)) {
        $mx_ip = $domain;
    } else {
        
        getmxrr($domain, $mxhosts, $mxweight);
    }

    if (!empty($mxhosts)) {
        $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
    } else {
        
        if (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $record_a = dns_get_record($domain, DNS_A);
           
        } elseif (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $record_a = dns_get_record($domain, DNS_AAAA);
        }

        if (!empty($record_a)) {
            $mx_ip = $record_a[0]['ip'];
        } else {
            
            $result = 'invalid';
            $details .= 'No suitable MX records found.';

            return ((true == $getdetails) ? array($result, $details) : $result);
        }
    }

   
    $connect = @fsockopen($mx_ip, 25);

    if ($connect) {

              
        if (preg_match('/^220/i', $out = fgets($connect, 1024))) {

                     
            fputs($connect, "HELO $mx_ip\r\n");
            $out = fgets($connect, 1024);
            $details .= $out."\n";

            
            fputs($connect, "MAIL FROM: <$fromemail>\r\n");
            $from = fgets($connect, 1024);
            $details .= $from."\n";

                       
            fputs($connect, "RCPT TO: <$toemail>\r\n");
            $to = fgets($connect, 1024);
            $details .= $to."\n";

            
            fputs($connect, 'QUIT');
            fclose($connect);

            
            if (!preg_match('/^250/i', $from) || !preg_match('/^250/i', $to)) {
                $result = 'invalid';
            } else {
                $result = 'valid';
            }
        }
    } else {
        $result = 'invalid';
        $details .= 'Could not connect to server';
    }
    if ($getdetails) {
        return array($result, $details);
    } else {
        return $result;
    }
}


	function connectDB()
	{
		$dbc = mysql_connect("localhost","simso_vw","Si@12345");
		mysql_select_db("simso_vw");
        mysql_set_charset("utf8", $dbc);
	}
	
	
connectDB();



		
 $tenda = 'có khách đặt sim ';
	
	 $content = 'chi ';

$email = 'nguyenvanchinh20081998@gmail.com';

if(kiemtraemail($email)=='valid')
	{
		$ketquagui =  guimail('maitrang99630@gmail.com', '99630.maitrang', ['nguyenvanchinh20081998@gmail.com',$email],$tenda, $content, 'LHKH', 'nguyenvanchinh20081998@gmail.com');
	}

?>