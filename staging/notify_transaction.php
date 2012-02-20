<?php

$key="3A0D03C1F1F96A48ECB580E5ECEC72FF5E2670F5";

$user = "wims12";
$pass = "wims12";
$db = "wims12";

$zcrsp =  array (
    'amount'     => addslashes(trim(@$_POST['amount'])),  //original amount
    'curr'       => addslashes(trim(@$_POST['curr'])),    //original currency
    'invoice_id' => addslashes(trim(@$_POST['invoice_id'])),//original invoice id
    'ep_id'      => addslashes(trim(@$_POST['ep_id'])), //Euplatesc.ro unique id
    'merch_id'   => addslashes(trim(@$_POST['merch_id'])), //your merchant id
    'action'     => addslashes(trim(@$_POST['action'])), // if action ==0 transaction ok
    'message'    => addslashes(trim(@$_POST['message'])),// transaction responce message
    'approval'   => addslashes(trim(@$_POST['approval'])),// if action!=0 empty
    'timestamp'  => addslashes(trim(@$_POST['timestamp'])),// meesage timestamp
    'nonce'      => addslashes(trim(@$_POST['nonce'])),
);

$zcrsp['fp_hash'] = strtoupper(euplatesc_mac($zcrsp, $key));

$fp_hash=addslashes(trim(@$_POST['fp_hash']));
if($zcrsp['fp_hash']===$fp_hash) {
    $db = new mysqli("localhost", $user, $pass, $db);
    $stmt = $db->stmt_init();

    if ($stmt->prepare("UPDATE `transaction` SET status=?, ep_id=?, message=? WHERE id=?")) 
    {
	$stmt->bind_param("issi", $status, $ep_id, $message, $id);
	$id = (int)$zcrsp["invoice_id"];
	$ep_id = $zcrsp["ep_id"];
	$message = $zcrsp["message"];
	$status = ($zcrsp['action'] == "0") ? 1 : 0; 

	$stmt->execute();
	$stmt->close();
    }
} else {
    echo "Invalid signature";
}
?>
<?php
function hmacsha1($key,$data) {
    $blocksize = 64;
    $hashfunc  = 'md5';

    if(strlen($key) > $blocksize)
	$key = pack('H*', $hashfunc($key));

    $key  = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);

    $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $data))));
    return bin2hex($hmac);
}
// ===========================================================================================
function euplatesc_mac($data, $key = NULL)
{
    $str = NULL;

    foreach($data as $d)
    {
	if($d === NULL || strlen($d) == 0)
	    $str .= '-'; // valorile nule sunt inlocuite cu -
	else
	    $str .= strlen($d) . $d;
    }

    $key = pack('H*', $key); 

    return hmacsha1($key, $str);
}

?>
