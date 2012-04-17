<?php

// Detalii euplatesc.ro
$merchant_id = "44840978562";
$key = "3A0D03C1F1F96A48ECB580E5ECEC72FF5E2670F5";

// Detalii baza de date
$user = "wims12";
$pass = "wims12";
$db = "wims12";

function hmacsha1($key, $data) {
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
  
  return hmacsha1($key, $str);
}

$db = new mysqli("localhost", $user, $pass, $db);
$stmt = $db->stmt_init();

if ($stmt->prepare("INSERT INTO `transaction` (`title`, `first_name`, `last_name`, `affiliation`, `address`, `city`, 
`zip_code`, `country`, `telephone`, `email`, `amount`, `currency`, `details`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
?, ?, ?)")) 
{
  $stmt->bind_param("ssssssssssdss", $title, $first_name, $last_name, $affiliation, $address, $city, $zip_code, 
$country, $telephone, $email, $amount, $currency, $details);

  $title = $_POST["title"];
  $first_name = $_POST["first_name"];
  $last_name = $_POST["last_name"];
  $affiliation = $_POST["affiliation"];
  $address = $_POST["address"];
  $city = $_POST["city"];
  $zip_code = $_POST["zip_code"];
  $country = $_POST["country"];
  $telephone = $_POST["telephone"];
  $email = $_POST["email"];
  $amount = 0.0 + $_POST["amount"];
  $currency = $_POST["curr"];
  $details = $_POST["order_desc"];

  $stmt->execute();

  $invoice_id = $db->insert_id;

  $stmt->close();
}

$dataAll = array(
    'amount'      => addslashes(trim(@$_POST['amount'])),                                                     //suma de plata
    'curr'        => addslashes(trim(@$_POST['curr'])),                                                   // moneda de plata
    'invoice_id'  => $invoice_id,
    'order_desc'  => addslashes(trim(@$_POST['order_desc'])),                                            //descrierea comenzii
                                                                              // va rog sa nu modificati urmatoarele 3 randuri
    'merch_id'    => $merchant_id,                                                    // nu modificati
    'timestamp'   => gmdate("YmdHis"),                                        // nu modificati
    'nonce'       => md5(microtime() . mt_rand()),                            //nu modificati
); 
  
$dataAll['fp_hash'] = strtoupper(euplatesc_mac($dataAll,$key));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>WIMS'12</title>
<!-- add your meta tags here -->

<link href="css/my_layout.css" rel="stylesheet" type="text/css" />
<!--[if lte IE 7]>
<link href="css/patch_my_layout.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" charset="utf-8"></script>

<style type="text/css">
    label {
        display: block;
        float: left;
        width: 110px;
    }

    input {
        width: 300px;
    }
</style>

<script type="text/javascript" charset="utf-8">
    $(function () {
        $("#title").val("<?php echo $_POST['title']; ?>");
        $("#country").val("<?php echo $_POST['country']; ?>");
        $("#gateway").submit(function () {
            $(this).children().each(function () {
                var child = $(this);
                alert(child.attr("name"));
            });
            return false;
        });
    });
</script>

</head>
<body>
  <div class="page_margins">
    <div class="page">
      <div id="header">
        <div class="subcolumns">
          <div class="c25l">
            <div class="subcl">
              <img src="images/wims12.png" alt="WIMS'12"/>
              <div id="tagline">
                International Conference on Web Intelligence, Mining and Semantics
              </div>
              <div id="dateline">
                June 13-15, 2012<br/>
                <span style="font-size: 80%">Craiova, Romania</span>
              </div>
            </div>
          </div>
          <div class="c25l">
            <div class="subc">
              <img src="images/craivoa1.png" alt="Muscial fountain from town center"/>
            </div>
          </div>
          <div class="c25l">
            <div class="subc">
              <img src="images/craivoa2.png" alt="English park"/>
            </div>
          </div>
          <div class="c25r">
            <div class="subcr">
              <img src="images/craivoa3.png" alt="Craiova's Romanescu natural park"/>
            </div>
          </div>
        </div>
      </div>
      <div id="nav">
        <!-- skiplink anchor: navigation -->
        <a id="navigation" name="navigation"></a>
        <div class="hlist">
          <!-- main navigation: horizontal list -->
          <ul>
            <li class="active"><strong>Home</strong></li>
            <li><a href="organization.html">Organisation</a></li>
            <li><a href="workshops.html">Workshops</a></li>
            <li><a href="keynote.html">Keynotes & Tutorials</a></li>
            <li><a href="cfp.html">Call for Papers</a></li>
            <li><a href="submission.html">Submission</a></li>
            <li><a href="registration.html">Registration</a></li>
            <li><a href="not_available.html">Program</a></li>
            <li><a href="important_dates.html">Important Dates</a></li>
            <li><a href="venue.html">Venue & Accommodation</a></li>
          </ul>
        </div>
      </div>
      <div id="main">
        <div id="col1">
          <div id="col1_content" class="clearfix">
            <div class="organizer">
              ORGANISED BY<br/>
              <a href="http://www.ucv.ro/">
                University of Craiova,<br/>
                Romania
              </a>
            </div>
            <div class="organizer_logo">
              <a href="http://www.ucv.ro/">
                <img src="images/ucv_logo.png" border="0" alt="University of Craiova"/>
              </a>
            </div>
            <div class="organizer">
              Proceedings will be part of the International Conference Proceedings Series
            </div>
            <div class="organizer_logo">
              <a href="http://portal.acm.org/citation.cfm?id=1988688&coll=DL&dl=GUIDE&CFID=36670581&CFTOKEN=90504349">
                <img src="images/acm_logo.jpg" border="0" width="175" height="115" alt="University of Craiova"/>
              </a>
            </div>
            <div class="organizer">
              Contact info:
              <p style="font-size: 80%">
                Software Engineering Department<br/>
                University of Craiova<br/>
                Bvd.Decebal 107, 200440, Craiova, Romania<br/>
                Tel/Fax: +40251438198<br/>
                E-mail: <a href="mailto:wims12@software.ucv.ro">wims12@software.ucv.ro</a><br/>
              </p>
            </div>
          </div>
        </div>
        <div id="col2">
          <div id="col2_content" class="clearfix">
            <h1>About</h1>
            <p>
              The 2nd International Conference on Web Intelligence, Mining and Semantics (WIMS'12) is organised under the auspices of <a href="http://www.ucv.ro/">University of Craiova</a>. The site for the previous edition, <a href="http://wims.vestforsk.no/index.html"><strong>WIMS'11</strong></a>, can be found <a href="http://wims.vestforsk.no/index.html"><strong>here</strong></a>.
            </p>
            <p>
              This is the second in a new series of conferences concerned with intelligent approaches to transform the World Wide Web into a global reasoning and semantics-driven computing machine. Then next conference in this series, WIMS'13 will take place in Madrid (Spain).
            </p>
            <h1>Supported by</h1>
            <div>
                <a href="http://www.vestforsk.no/">
                    <img src="images/vf_nor-eng-web.png" alt="vestforsk" border="0" width="100%"/>
                </a>
            </div>
            <div>
                <a href="http://www.planet-data.eu/">
                    <img src="images/PD_logo.gif" alt="vestforsk" border="0" width="100%"/>
                </a>
            </div>
            <h1>Scientific Patronage of</h1>
            <div>
                <a href="http://www.aria-romania.org/">
                    <img src="images/aria-logo.png" alt="Asociatia Romana de Inteligenta Artificiala" border="0" width="100%"/>
                </a>
            </div>
                        <h1>News</h1><h3>Extended deadlines for industrial track</h3>
            <p>
              <strong>05.01.2012</strong>: Check the <a href="cfp-i.html">the industrial track</a> section for details.
            </p>
            <h3>Added accommodation information</h3>
            <p>
              <strong>23.02.2012</strong>: Check the <a href="venue.html">venue & accommodation section</a> for details.
            </p>
            <h3>Added tutorials</h3>
            <p>
              <strong>20.02.2012</strong>: Check the <a href="keynote.html">keynotes & tutorials section</a> for details.
            </p>

            <h3>Updated registration details</h3>
            <p>
              <strong>15.02.2012</strong>: Check the <a href="registration.html">registration section</a> for details.
            </p>
            <h3>Added keynote speaker Elena Simperl</h3>
            <p>
              <strong>15.02.2012</strong>: Announced keynote session <a href="keynote.html">Crowdsourcing Semantic Data Management: Challenges and Opportunities</a> by Elena Simperl.
            </p>
            <h3>Updated registration details</h3>
            <p>
              <strong>15.02.2012</strong>: Check the <a href="registration.html">registration section</a> for details.
            </p>
            <h3>Added keynote speaker Elena Simperl</h3>
            <p>
              <strong>15.02.2012</strong>: Announced keynote session <a href="keynote.html">Crowdsourcing semantic data management: challenges and opportunities</a> by Elena Simperl.
            </p>
            <h3>Added programme committee for the iCompute workshop</h3>
            <p>
              <strong>07.01.2012</strong>: The programme committee for the iCompute workshop can be found <a href="workshops.html">here</a>.
            </p>
            <h3>Call for Workshop Papers</h3>
            <p>
              <strong>03.01.2012</strong>: Details about the workshop papers can be found on <a href="workshops.html">here</a>.
            </p>
            <h3>Added keynote speaker Jeff Z. Pan</h3>
            <p>
              <strong>03.01.2012</strong>: Announced keynote session <a href="keynote.html">&#8220;&laquo;Closing&raquo; Some Doors for the Open Semantic Web&#8221;</a> by Jeff Z. Pan.
            </p>
            <h3>Industrial track PC members announced</h3>
            <p>
              <strong>20.01.2012</strong>: Announced the list of members of the PC for the <a href="cfp-i.html">Industrial track</a>.
            </p>
            <h3>Deadlines extended</h3>
            <p>
              <strong>11.12.2011</strong>: Deadlines for paper submissions extended.
            </p>
            <h3>WIMS'12 site updated</h3>
            <p>
              <strong>26.09.2011</strong>: Added call for the Industrial Track; updated PC member list.
            </p>
            <hr/>
            <h3>WIMS'12 site updated</h3>
            <p>
              <strong>05.08.2011</strong>: The list of PC and AC members has been updated.
            </p>
            <hr/>
            <h3>WIMS'12 site launched</h3>
            <p>
              <strong>20.06.2011</strong>: The WIMS'12 site has been launched. This site will be constantly updated with news and information about the WIMS'12 conference.
            </p>
            <hr/>
          </div>
        </div>
        <div id="col3">
          <div id="col3_content" class="clearfix">
              <h1>Confirm registration</h1>
              <p>Please check that the information below if accurate before you submit your data. If you want to change anything <a href="javascript:history.go(-1)">go back</a> and make the necessary changes.</p>
              <form ACTION="https://secure.euplatesc.ro/tdsprocess/tranzactd.php?lang=en" METHOD="POST" name="gateway" id="gateway" target="_self">
                  <!-- <p class="tx_red_mic">Transferring to EuPlatesc.ro gateway</p>
                  <p><img src="https://www.euplatesc.ro/plati-online/tdsprocess/images/progress.gif" alt="" title="" onload="javascript:document.gateway.submit()"></p> -->
                  <h4>Personal Information</h4>
                  <p>
                      <label for="title">Title</label>
                      <select name="title" id="title" >
                          <option value="mr">Mr.</option>
                          <option value="mrs">Mrs.</option>
                          <option value="dr">Dr.</option>
                          <option value="prof">Prof.</option>
                      </select>
                  </p>
                  <p>
                      <label for="first_name">First Name</label>
                      <input type="text" name="fname" value="<?php echo $_POST['first_name']; ?>" id="fname" />
                  </p>
                  <p>
                      <label for="last_name">Last Name</label>
                      <input type="text" name="lname" value="<?php echo $_POST['last_name']; ?>" id="lname" />
                  </p>
                  <p>
                      <label for="affiliation">Affiliation</label>
                      <input type="text" name="company" value="<?php echo $_POST['affiliation']; ?>" id="company" />
                  </p>
                  <p>
                      <label for="address">Address</label>
                      <input type="text" name="add" value="<?php echo $_POST['address']; ?>" id="add" />
                  </p>
                  <p>
                      <label for="city">City</label>
                      <input type="text" name="city" value="<?php echo $_POST['city']; ?>" id="city" />
                  </p>
                  <p>
                      <label for="zip_code">Zip Code</label>
                      <input type="text" name="zip" value="<?php echo $_POST['zip_code']; ?>" id="zip" />
                  </p>
                  <p>
                      <label for="country">Country</label>
                      <select class="" id="country" name="country" >
                          <option value="">Select One</option><option value="af">Afghanistan</option><option value="ax">Aland Islands</option><option value="al">Albania</option><option value="dz">Algeria</option><option value="as">American Samoa</option><option value="ad">Andorra</option><option value="ao">Angola</option><option value="ai">Anguilla</option><option value="aq">Antarctica</option><option value="ag">Antigua and Barbuda</option><option value="ar">Argentina</option><option value="am">Armenia</option><option value="aw">Aruba</option><option value="au">Australia</option><option value="at">Austria</option><option value="az">Azerbaijan</option><option value="bs">Bahamas</option><option value="bh">Bahrain</option><option value="bd">Bangladesh</option><option value="bb">Barbados</option><option value="by">Belarus</option><option value="be">Belgium</option><option value="bz">Belize</option><option value="bj">Benin</option><option value="bm">Bermuda</option><option value="bt">Bhutan</option><option value="bo">Bolivia</option><option value="ba">Bosnia and Herzegovina</option><option value="bw">Botswana</option><option value="bv">Bouvet Island</option><option value="br">Brazil</option><option value="io">British Indian Ocean Territory</option><option value="vg">British Virgin Islands</option><option value="bn">Brunei</option><option value="bg">Bulgaria</option><option value="bf">Burkina Faso</option><option value="bi">Burundi</option><option value="kh">Cambodia</option><option value="cm">Cameroon</option><option value="ca">Canada</option><option value="cv">Cape Verde</option><option value="ky">Cayman Islands</option><option value="cf">Central African Republic</option><option value="td">Chad</option><option value="cl">Chile</option><option value="cn">China</option><option value="cx">Christmas Island</option><option value="cc">Cocos (Keeling) Islands</option><option value="co">Colombia</option><option value="km">Comoros</option><option value="cg">Congo</option><option value="ck">Cook Islands</option><option value="cr">Costa Rica</option><option value="hr">Croatia</option><option value="cu">Cuba</option><option value="cy">Cyprus</option><option value="cz">Czech Republic</option><option value="cd">Democratic Republic of Congo</option><option value="dk">Denmark</option><option value="xx">Disputed Territory</option><option value="dj">Djibouti</option><option value="dm">Dominica</option><option value="do">Dominican Republic</option><option value="tl">East Timor</option><option value="ec">Ecuador</option><option value="eg">Egypt</option><option value="sv">El Salvador</option><option value="gq">Equatorial Guinea</option><option value="er">Eritrea</option><option value="ee">Estonia</option><option value="et">Ethiopia</option><option value="fk">Falkland Islands</option><option value="fo">Faroe Islands</option><option value="fm">Federated States of Micronesia</option><option value="fj">Fiji</option><option value="fi">Finland</option><option value="fr">France</option><option value="gf">French Guyana</option><option value="pf">French Polynesia</option><option value="tf">French Southern Territories</option><option value="ga">Gabon</option><option value="gm">Gambia</option><option value="ge">Georgia</option><option value="de">Germany</option><option value="gh">Ghana</option><option value="gi">Gibraltar</option><option value="gr">Greece</option><option value="gl">Greenland</option><option value="gd">Grenada</option><option value="gp">Guadeloupe</option><option value="gu">Guam</option><option value="gt">Guatemala</option><option value="gn">Guinea</option><option value="gw">Guinea-Bissau</option><option value="gy">Guyana</option><option value="ht">Haiti</option><option value="hm">Heard Island and Mcdonald Islands</option><option value="hn">Honduras</option><option value="hk">Hong Kong</option><option value="hu">Hungary</option><option value="is">Iceland</option><option value="in">India</option><option value="id">Indonesia</option><option value="ir">Iran</option><option value="iq">Iraq</option><option value="xe">Iraq-Saudi Arabia Neutral Zone</option><option value="ie">Ireland</option><option value="il">Israel</option><option value="it">Italy</option><option value="ci">Ivory Coast</option><option value="jm">Jamaica</option><option value="jp">Japan</option><option value="jo">Jordan</option><option value="kz">Kazakhstan</option><option value="ke">Kenya</option><option value="ki">Kiribati</option><option value="kw">Kuwait</option><option value="kg">Kyrgyzstan</option><option value="la">Laos</option><option value="lv">Latvia</option><option value="lb">Lebanon</option><option value="ls">Lesotho</option><option value="lr">Liberia</option><option value="ly">Libya</option><option value="li">Liechtenstein</option><option value="lt">Lithuania</option><option value="lu">Luxembourg</option><option value="mo">Macau</option><option value="mk">Macedonia</option><option value="mg">Madagascar</option><option value="mw">Malawi</option><option value="my">Malaysia</option><option value="mv">Maldives</option><option value="ml">Mali</option><option value="mt">Malta</option><option value="mh">Marshall Islands</option><option value="mq">Martinique</option><option value="mr">Mauritania</option><option value="mu">Mauritius</option><option value="yt">Mayotte</option><option value="mx">Mexico</option><option value="md">Moldova</option><option value="mc">Monaco</option><option value="mn">Mongolia</option><option value="me">Montenegro</option><option value="ms">Montserrat</option><option value="ma">Morocco</option><option value="mz">Mozambique</option><option value="mm">Myanmar</option><option value="na">Namibia</option><option value="nr">Nauru</option><option value="np">Nepal</option><option value="an">Netherlands Antilles</option><option value="nl">Netherlands</option><option value="nc">New Caledonia</option><option value="nz">New Zealand</option><option value="ni">Nicaragua</option><option value="ne">Niger</option><option value="ng">Nigeria</option><option value="nu">Niue</option><option value="nf">Norfolk Island</option><option value="kp">North Korea</option><option value="mp">Northern Mariana Islands</option><option value="no">Norway</option><option value="om">Oman</option><option value="pk">Pakistan</option><option value="pw">Palau</option><option value="ps">Palestinian Territories</option><option value="pa">Panama</option><option value="pg">Papua New Guinea</option><option value="py">Paraguay</option><option value="pe">Peru</option><option value="ph">Philippines</option><option value="pn">Pitcairn Islands</option><option value="pl">Poland</option><option value="pt">Portugal</option><option value="pr">Puerto Rico</option><option value="qa">Qatar</option><option value="re">Reunion</option><option value="ro">Romania</option><option value="ru">Russia</option><option value="rw">Rwanda</option><option value="sh">Saint Helena and Dependencies</option><option value="kn">Saint Kitts and Nevis</option><option value="lc">Saint Lucia</option><option value="pm">Saint Pierre and Miquelon</option><option value="vc">Saint Vincent and the Grenadines</option><option value="ws">Samoa</option><option value="sm">San Marino</option><option value="st">Sao Tome and Principe</option><option value="sa">Saudi Arabia</option><option value="sn">Senegal</option><option value="rs">Serbia</option><option value="sc">Seychelles</option><option value="sl">Sierra Leone</option><option value="sg">Singapore</option><option value="sk">Slovakia</option><option value="si">Slovenia</option><option value="sb">Solomon Islands</option><option value="so">Somalia</option><option value="za">South Africa</option><option value="gs">South Georgia and South Sandwich Islands</option><option value="kr">South Korea</option><option value="es">Spain</option><option value="pi">Spratly Islands</option><option value="lk">Sri Lanka</option><option value="sd">Sudan</option><option value="sr">Suriname</option><option value="sj">Svalbard and Jan Mayen</option><option value="sz">Swaziland</option><option value="se">Sweden</option><option value="ch">Switzerland</option><option value="sy">Syria</option><option value="tw">Taiwan</option><option value="tj">Tajikistan</option><option value="tz">Tanzania</option><option value="th">Thailand</option><option value="tg">Togo</option><option value="tk">Tokelau</option><option value="to">Tonga</option><option value="tt">Trinidad and Tobago</option><option value="tn">Tunisia</option><option value="tr">Turkey</option><option value="tm">Turkmenistan</option><option value="tc">Turks And Caicos Islands</option><option value="tv">Tuvalu</option><option value="vi">US Virgin Islands</option><option value="ug">Uganda</option><option value="ua">Ukraine</option><option value="ae">United Arab Emirates</option><option value="uk">United Kingdom</option><option value="um">United States Minor Outlying Islands</option><option value="us">United States</option><option value="uy">Uruguay</option><option value="uz">Uzbekistan</option><option value="vu">Vanuatu</option><option value="va">Vatican City</option><option value="ve">Venezuela</option><option value="vn">Vietnam</option><option value="wf">Wallis and Futuna</option><option value="eh">Western Sahara</option><option value="ye">Yemen</option><option value="zm">Zambia</option><option value="zw">Zimbabwe</option>
                      </select>
                  </p>
                  <p>
                      <label for="telephone">Telephone/Fax</label>
                      <input type="text" name="phone" value="<?php echo $_POST['telephone']; ?>" id="phone" />
                  </p>
                  <p>
                      <label for="email">Email</label>
                      <input type="text" name="email" value="<?php echo $_POST['email']; ?>" id="email" />
                  </p>
                  <h4>Paper Information</h4>
                  <p>
                      <label for="paper_1_id">Paper 1 ID</label>
                      <input type="text" value="<?php echo $_POST['paper_1_id']; ?>" id="paper_1_id" />
                  </p>
                  <p>
                      <label for="paper_1_title">Paper 1 Title</label>
                      <input type="text" value="<?php echo $_POST['paper_1_title']; ?>" id="paper_1_title" />
                  </p>
                  <p>
                      <label for="paper_2_id">Paper 2 ID</label>
                      <input type="text" value="<?php echo $_POST['paper_2_id']; ?>" id="paper_2_id" />
                  </p>
                  <p>
                      <label for="paper_2_title">Paper 2 Title</label>
                      <input type="text" value="<?php echo $_POST['paper_2_title']; ?>" id="paper_2_title" />
                  </p>
                  <p>
                      <label for="paper_3_id">Paper 3 ID</label>
                      <input type="text" value="<?php echo $_POST['paper_3_id']; ?>" id="paper_3_id" />
                  </p>
                  <p>
                      <label for="paper_3_title">Paper 3 Title</label>
                      <input type="text" value="<?php echo $_POST['paper_3_title']; ?>" id="paper_3_title" />
                  </p>
                  <p><strong style="font-size: 120%;">Total amount: <?php echo $dataAll["amount"] . " " . $dataAll["curr"] ?></strong></p>
                  <input type="hidden" name="amount" VALUE="<?php echo  $dataAll['amount'] ?>" SIZE="12" MAXLENGTH="12" />
                  <input type="hidden" name="curr" VALUE="<?php echo  $dataAll['curr'] ?>" SIZE="5" MAXLENGTH="3" />
                  <input type="hidden" name="invoice_id" VALUE="<?php echo  $dataAll['invoice_id']; ?>" SIZE="32" MAXLENGTH="32" />
                  <input type="hidden" name="order_desc" VALUE="<?php echo  $dataAll['order_desc'] ?>" SIZE="32" MAXLENGTH="50" />
                  <input type="hidden" name="merch_id" SIZE="15" VALUE="<?php echo  $dataAll['merch_id'] ?>" />
                  <input type="hidden" name="timestamp" SIZE="15" VALUE="<?php echo  $dataAll['timestamp'] ?>" />
                  <input type="hidden" name="nonce" SIZE="35" VALUE="<?php echo  $dataAll['nonce'] ?>" />
                  <input type="hidden" name="fp_hash" SIZE="40" VALUE="<?php echo  $dataAll['fp_hash'] ?>" />
                  <!--p><a href="javascript:gateway.submit();" class="txtCheckout">Confirm and proceed to payment</a></p-->
                  <p><input type="submit" value="Confirm and proceed to payment"/></p>
              </form>
          </div>
          <!-- IE Column Clearing -->
          <div id="ie_clearing"> &#160; </div>
        </div>
      </div>
      <!-- begin: #footer -->
      <div id="footer">Layout based on <a href="http://www.yaml.de/">YAML</a>
      </div>
    </div>
  </div>
<script type="text/javascript">

 var _gaq = _gaq || [];
 _gaq.push(['_setAccount', 'UA-25287199-1']);
 _gaq.push(['_trackPageview']);

 (function() {
  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
 })();

</script>
</body>
</html>
