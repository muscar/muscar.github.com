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
function euplatesc_mac($data, $key)
{
  $str = NULL;

  foreach($data as $d)
  {
   	if($d === NULL || strlen($d) == 0)
  	  $str .= '-'; // valorile nule sunt inlocuite cu -
  	else
  	  $str .= strlen($d) . $d;
  }
     
  // ================================================================
  $key = pack('H*', $key); // convertim codul secret intr-un string binar
  // ================================================================

// echo " $str " ;

  return hmacsha1($key, $str);
}


  $dataAll = array(
			'amount'      => '1',                                                   //suma de plata
			'curr'        => 'RON',                                                   // moneda de plata
			'invoice_id'  => str_pad(substr(mt_rand(), 0, 7), 7, '0', STR_PAD_LEFT),  // numarul comenzii este generat aleator. inlocuiti cuu seria dumneavoastra
			'order_desc'  => 'test order',                                            //descrierea comenzii
                     // va rog sa nu modificati urmatoarele 3 randuri
			'merch_id'    => $mid,                                                    // nu modificati
			'timestamp'   => gmdate("YmdHis"),                                        // nu modificati
 			'nonce'       => md5(microtime() . mt_rand()),                            //nu modificati
); 
  
  $dataAll['fp_hash'] = strtoupper(euplatesc_mac($dataAll,$key));

//completati cu valorile dvs
$dataBill = array(
			'fname'	   => 'billing nume',      // nume
			'lname'	   => 'billing prenume',   // prenume
			'country'  => 'billing tara',      // tara
			'company'  => 'billing company',   // firma
			'city'	   => 'billing city',      // oras
			'add'	     => 'billing adresa',    // adresa
			'email'	   => 'billing email',     // email
			'phone'	   => 'billing telefon',   // telefon
			'fax'	     => 'billing fax',       // fax
);
$dataShip = array(
			'sfname'       => 'shipping nume',     // nume
			'slname'       => 'shipping prenume',  // prenume
			'scountry'     => 'shipping tara',     // tara
			'scompany'     => 'shipping company',  // firma
			'scity'	       => 'shipping city',     // oras
			'sadd'         => 'shipping add',      // adresa
			'semail'       => 'shipping email',    // email
			'sphone'       => 'shipping telefon',  // telefon
			'sfax'	       => 'shipping fax',      // fax
);

// ===========================================================================================


?>
