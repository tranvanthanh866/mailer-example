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



?>
 
	<?php
		if(isset($_POST['lienhe'])){
		$sodienthoai = $_POST['sodienthoai'];	
		$giabanchitietsim = $_POST['giasim'];	
		$male = $_POST['male'];	
		$name = $_POST['name'];	
		$madl = $_POST['madl'];	
		$mang = $_POST['mang'];	
		$phone = $_POST['phone'];					
		$tinh = $_POST['tinh'];
		$quan = $_POST['quan'];
		$email = 'quanly.simsodeptoanquoc.vn@gmail.com';
		$address = $_POST['address'];
		$ship_method = $_POST['ship_method'];
		$sql = "INSERT INTO khachhang(sosim,giaban,gioitinh,hoten,dienthoai,tinh,quan,diachi,thanhtoan,madl,mang) VALUES ('$sodienthoai','$giabanchitietsim','$male','$name','$phone','$tinh','$quan','$address','$ship_method','$madl','$mang')";
		
		
	
		
		
		if (mysql_query($sql)) {
		echo "";
		} else {
		echo "Error: " ;
		}		
		

$sqlch = "SELECT * FROM cauhinh";
   $kqch = mysql_query($sqlch);
  $colch = mysql_fetch_array($kqch);
   
	  $taikhoan = $colch['taikhoan'];
	   $matkhau = $colch['matkhau'];
	   $mailnhan = $colch['mailnhan'];
	   $mailgui = $colch['mailgui'];
   
			
$sql_tinh ="select * from devvn_tinhthanhpho where matp = '$tinh'  ";
					$ketqua_tinh = mysql_query($sql_tinh);
					$mang_tinh = mysql_fetch_array($ketqua_tinh);
					$tieudetinh = $mang_tinh['name'];
					$sql_quan ="select * from devvn_quanhuyen where maqh = '$quan'  ";
					$ketqua_quan = mysql_query($sql_quan);
					$mang_quan = mysql_fetch_array($ketqua_quan);
					$tieudequan = $mang_quan['name'];
		
 $tenda = 'có khách '.$name.' đặt sim '.$sodienthoai.'';
	
	 $content = '
	 <p>Số sim đặt mua: '.$sodienthoai.'</p>
	 <p>giá sim: '.$giabanchitietsim.'</p>
	 	<p>Mã đại lý: '.$madl.'</p>
	 <p>Tên khách hàng: '.$name.'</p>
	

	<p>Giới tính :  '.$male.'</p>
	<p>Điện thoại :  '.$phone.'</p>
	
	<p>địa chỉ: '.$address.'-'.$tieudequan.'-'.$tieudetinh.'</p>
	<p>phương thức thanh toán: '.$ship_method.'</p>
		
	';
	
	
	
	if(kiemtraemail($mailgui)=='valid')
	{
		$ketquagui =  guimail($taikhoan, $matkhau, [$mailnhan,$mailgui],$tenda, $content, 'LHKH', $mailnhan);
	}

	}	 
	
		
		
	?>
	<!doctype html>
<html lang="vi">
   <head>
      <meta http-equiv="content-type" content="text/html; charset=UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0' >
      <base href="<?php echo $tenweb ?>">
      <link rel="alternate" hreflang="vi-vn" href="<?php echo $tenweb ?>"/>
      <link rel="icon" href="<?php echo $logomobi ?>" sizes="16x16" type="image/png">
      <title><?php echo $tieudeweb ?></title>
	 <meta name='viewport' content='width=device-width, initial-scale=1'>
      <meta name="keywords" content="<?php echo $tukhoa ?>" />
      <meta name="description" content="<?php echo $motaweb ?>" />  
      <meta property="og:url" content="<?php echo $tenweb ?>" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="<?php echo $tieudeweb ?>" />
      <meta property="og:description" content="<?php echo $motaweb ?>" />
      <meta property="og:image" content="<?php echo $logo ?>" />
  
      <meta itemprop="author" content="<?php echo $tenweb ?>" />
      <meta itemprop="name" content="<?php echo $tieudeweb ?>" />
      <meta itemprop="url" content="<?php echo $tenweb ?>" />
      <meta itemprop="creator accountablePerson" content="<?php echo $tenweb ?>" />
      <meta itemprop="image" content="<?php echo $logo ?>" />
 
      <link rel="stylesheet" type="text/css" media="all" type="text/css" href="css/all.min.css">
      <link rel="stylesheet" type="text/css" media="all" type="text/css" href="css/style.css">
 
   </head>
   
   <body>
     <?php include("header/header.php") ?>
	 <style type="text/css">
         #main-menu > ul a img{height: 1.4em;vertical-align: middle;}
      </style>
      <main id="main-container" role="main" class="container mt-4 pt-3 pt-md-0 my-md-4">
         <div class="row">
            <?php include("slider/left.php") ?>
		   <div class="col-md-6 mb-3 col-main">
             <?php include("slider/locso.php") ?>
			 
			 <?php 
				$sodienthoai = $_POST['sodienthoai'];	
				$giabanchitietsim = $_POST['giasim'];	
				$male = $_POST['male'];	
				$name = $_POST['name'];	
				$madl = $_POST['madl'];	
				$mang = $_POST['mang'];	
				$phone = $_POST['phone'];					
				$tinh = $_POST['tinh'];
				$quan = $_POST['quan'];
				$address = $_POST['address'];
				$ship_method = $_POST['ship_method'];
				
					$sql_tinh ="select * from devvn_tinhthanhpho where matp = '$tinh'  ";
					$ketqua_tinh = mysql_query($sql_tinh);
					$mang_tinh = mysql_fetch_array($ketqua_tinh);
					$tieudetinh = $mang_tinh['name'];
					$sql_quan ="select * from devvn_quanhuyen where maqh = '$quan'  ";
					$ketqua_quan = mysql_query($sql_quan);
					$mang_quan = mysql_fetch_array($ketqua_quan);
					$tieudequan = $mang_quan['name'];
				
			 ?>
<div class="row px-0">
   <div class="col-12 border-xs-0 border rounded-05 p-4 mt-3 text-left order-container">
      <div class="text-center">
         <img data-original="css/images/order-success.png" alt="" class="lazyload order-img lazyload" src="css/images/order-success.png" style="">            
         <h1 class="text-uppercase font-weight-bold ml-2 fs-120 order-title" style="display: inline;">ĐẶT SIM THÀNH CÔNG</h1>
      </div>
      <p class="mb-0 mt-3 text-left">Cảm ơn <?php echo $male ?> <b><?php echo $name ?></b> đã cho <?php echo $tenweb ?> cơ hội được phục vụ.
         Trong 10 phút, nhân viên chúng tôi sẽ gửi tin nhắn hoặc gọi điện thoại xác nhận giao hàng cho anh.
      </p>
      <hr>
      <p style="font-size: 110%;"><b>THÔNG TIN ĐẶT HÀNG</b></p>
      <ul>
         <li>
            Người nhận: <span class="text-bold">anh <?php echo $name ?></span>
         </li>
         <li>
            Điện thoại: <span class="text-bold"><?php echo $phone ?></span>
         </li>
         <li>
            Địa chỉ giao sim: <span class="text-bold"><?php echo $address ?>,<?php echo $tieudequan ?>, <?php echo $tieudetinh ?></span>
         </li>
         <li>
            Tổng tiền: <span class="text-bold" style="color: red;"><?php echo $giabanchitietsim ?>₫</span>
         </li>
      </ul>
      <p class="payment-type">Bạn chọn thanh toán: <b><?php echo $ship_method ?></b></p>
      <p>Khi cần hỗ trợ vui lòng gọi&nbsp;
         <a class="text-bold" title="0911.000.888" href="tel:<?php echo $hotline ?>" style="color: #007bff;"><?php echo $hotline ?></a>
         &nbsp;(từ 8:00 - 21:00)
      </p>
     <p>
            <a class="link-intro-payment" target="_blank" href="thanh-toan-3.html" title="Hướng dẫn thanh toán">Hướng dẫn thanh toán</a>

            <a class="link-intro-registry" target="_blank" href="thanh-toan-3.html" title="Hướng dẫn đăng ký thông tin">Hướng dẫn đăng ký thông tin</a>
        </p>
      <hr>
	  
	  
	   <?php 
	  					
	  	$sql_chitietsimtt ="select * from sanpham where tensp = '$sodienthoai'  ";
		$ketqua_chitietsimtt = mysql_query($sql_chitietsimtt);
		$mang_chitietsimtt= mysql_fetch_array($ketqua_chitietsimtt);
	
		$tieudechitietsimtt = $mang_chitietsimtt['tensp'];
		$masp = $mang_chitietsimtt['masp'];
		$mangdt = $mang_chitietsimtt['mang'];
		$giabansimtt = $mang_chitietsimtt['giaban'];
			if($mangdt=='viettel'){
			$img='phoi-sim/viettel.png';
			$anh='css/images/viettel.svg';	
		}
		if($mangdt=='vinaphone'){
			$img='phoi-sim/vina.png';
			$anh='css/images/vinaphone_1.svg';
		}
		if($mangdt=='mobifone'){
			$img='phoi-sim/mobi.png';
				
			$anh='css/images/mobifone_1.svg';
		}
		if($mangdt=='vietnamobile'){
			$img='phoi-sim/vietnamobile.png';
			$anh='css/images/vietnammobile.svg';	
		}
		if($mangdt=='gmobile'){
			$img='phoi-sim/gmobile.png';
		}
		if($mangdt=='itelecom'){
			$img='phoi-sim/itelecom.png';
			$anh='css/images/itelecom.svg';	
		}
		if($mangdt=='codinh'){
			$img='phoi-sim/mco-dinh.png';
			$anh='css/images/mang-co-dinh.png';	
			$mangdt='Sim cố định';
		}
	  ?>
      <h2 style="font-size: 110%;"><b>SỐ SIM ĐẶT MUA</b></h2>
      <div class="col-12 mb-2" style="padding-left: 0px;">
         <div class="media p-1 rounded" style="min-height: 68px;">
            <a href="<?php echo $sodienthoai ?>" title="<?php echo $sodienthoai ?>" style="align-self: center; margin-right: 12px;">
            <img data-original="<?php echo $anh ?>" src="<?php echo $anh ?>" class="align-self-center mr-2 mt-1 lazyload" width="50" style="">                </a>
            <div class="media-body" style="align-self: center;">
               <span>Số sim: <b><?php echo $sodienthoai ?></b></span><br>
               
               <span>Giá tiền: <span class="text-bold" style="color: red;">
              <?php echo $giabanchitietsim ?>₫                    </span></span>
            </div>
         </div>
      </div>
   </div>
</div>
				
			</div>
			
			<?php include("slider/left1.php") ?>
				

           <?php include("slider/right.php") ?>
	   </div>
         <?php include("content/home/binhluan.php") ?>	  
	  </main>
      <div class="line-1"></div>
     <?php include("footer/footer.php") ?>
      <button class="btn material-scrolltop reveal d-none" id="scroll-button"><i class="fa fa-arrow-circle-up"></i></button>
	  
      <script async src="js/uxsearch_pc.js" defer></script>    
		<script src='https://kit.fontawesome.com/a076d05399.js'></script>
		     <script src="js/jquery-3.3.1.min.js"></script> 
      <script src="js/bootstrap.bundle.min.js" defer></script>
      <script type="text/javascript" defer src="js/scripts.min.js"></script>
   </body>
</html>