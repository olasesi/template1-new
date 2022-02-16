<?php
require_once ('../incs-arahman/config.php');
require_once ('../incs-arahman/gen_serv_con.php');


$result = array();
//The parameter after verify/ is the transaction reference to be verified
$url = 'https://api.paystack.co/transaction/verify/'. $_GET['reference'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt(
$ch, CURLOPT_HTTPHEADER, [
'Authorization: Bearer sk_test_*****************************']
);
$request = curl_exec($ch);
curl_close($ch);

if ($request) {
$result = json_decode($request, true);
}

if (array_key_exists('data', $result) && array_key_exists('status', $result['data']) && ($result['data']['status'] === 'success')) {

    mysqli_query($connect, "UPDATE primary_school_students SET pri_paid='1' WHERE primary_id = '".$_SESSION['primary_id']."' AND pri_admit = '0' AND pri_active_email = '1' AND pri_paid = '0'") or die(db_conn_error);
	

    $q = mysqli_query($connect,"INSERT INTO primary_school_students (pri_class_id, pri_year, pri_firstname, pri_surname, pri_age, pri_sex, pri_email, pri_photo, pri_phone, pri_address, pri_password, pri_email_hash, pri_cookie_session) 
    VALUES ('".$radio."', '','".$firstname."','".$surname."', '', '','".$email."', '','".$phone."', '','".$encrypted."','".$hash."', '')") or die(db_conn_error);



    
    header('Location:'.GEN_WEBSITE.'/school-payment.php?status=1');
    exit();
   
//Perform necessary action
}else{
echo "Transaction was unsuccessful";
}
