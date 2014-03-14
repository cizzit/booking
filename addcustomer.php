<?php
require_once('incl.php');
?>
<html>
<head>
<title><?php echo $businessname;?> - Create Customer</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>Add Customer Information</h3>
<p>Please fill in information below, then click 'Create'. <span class="comp">First Name, Last Name and Primary Number</span> are required, all other fields are optional.</p>
<p align="center"><table>
<form method="POST">
<tr><td class="right">First Name</td><td><input type="text" size="30" name="fname" class="comp" <?php if(ISSET($_POST['fname'])) echo 'value="'.stripslashes($_POST['fname']).'"'; ?>/></td></tr>
<tr><td class="right">Last Name</td><td><input type="text" size="30" name="lname" class="comp" <?php if(ISSET($_POST['lname'])) echo 'value="'.stripslashes($_POST['lname']).'"'; ?>/></td></tr>
<tr><td class="right">Company</td><td><input type="text" size="30" name="company"  <?php if(ISSET($_POST['company'])) echo 'value="'.stripslashes($_POST['company']).'"'; ?>/></td></tr>
<tr><td class="right">Primary Contact Number</td><td><input type="text" size="30" name="primnumber" class="comp" <?php if(ISSET($_POST['primnumber'])) echo 'value="'.stripslashes($_POST['primnumber']).'"'; ?>/></td></tr>
<tr><td class="right">Secondary Contact Number</td><td><input type="text" size="30" name="secnumber"  <?php if(ISSET($_POST['secnumber'])) echo 'value="'.stripslashes($_POST['secnumber']).'"'; ?>/></td></tr>
<tr><td class="right">Email Address</td><td><input type="text" size="30" name="emailaddress"  <?php if(ISSET($_POST['emailaddress'])) echo 'value="'.stripslashes($_POST['emailaddress']).'"'; ?>/></td></tr>
<tr><td class="right">Address Line 1</td><td><input type="text" size="30" name="addressline1"  <?php if(ISSET($_POST['addressline1'])) echo 'value="'.stripslashes($_POST['addressline1']).'"'; ?>/></td></tr>
<tr><td class="right">Address Line 2</td><td><input type="text" size="30" name="addressline2" <?php if(ISSET($_POST['addressline2'])) echo 'value="'.stripslashes($_POST['addressline2']).'"'; ?> /></td></tr>
<tr><td class="right">City</td><td><input type="text" size="30" name="city"  <?php if(ISSET($_POST['city'])) echo 'value="'.stripslashes($_POST['city']).'"'; ?>/></td></tr>
<tr><td class="right">State</td><td class="left">
<select name="state">
<option value="NSW" <?php if($_POST['state']=='NSW') echo 'selected="selected"'; ?>/>NSW
<option value="VIC" <?php if($_POST['state']=='VIC') echo 'selected="selected"'; ?>/>VIC
<option value="QLD" <?php if($_POST['state']=='QLD') echo 'selected="selected"'; ?>/>QLD
<option value="TAS" <?php if($_POST['state']=='TAS') echo 'selected="selected"'; ?>/>TAS
<option value="SA" <?php if($_POST['state']=='SA') echo 'selected="selected"'; ?>/>SA
<option value="NT" <?php if($_POST['state']=='NT') echo 'selected="selected"'; ?>/>NT
<option value="WA" <?php if($_POST['state']=='WA') echo 'selected="selected"'; ?>/>WA
<option value="ACT" <?php if($_POST['state']=='ACT') echo 'selected="selected"'; ?>/>ACT
</select></td></tr>
<tr><td class="right">Postcode</td><td class="left"><input type="text" size="4" name="postcode" <?php if(ISSET($_POST['postcode'])) echo 'value="'.stripslashes($_POST['postcode']).'"'; ?> /></td></tr>
<tr/>
<tr><td class="center" colspan="2"><input type="submit" name="custgo" value="Create" />&nbsp;<input type="reset" name="clear" value="Clear"/></td></tr>
<tr><td class="center" colspan="2"><a href="javascript:history.go(-1)">&lt; Back</a> | <a href="./">Back to Home</a></td></tr>
<?php
if(ISSET($_POST['custgo'])){
	// form submitted, process!
	// error output in $error, formatted into <tr><td class='comp center'>$error</td></tr>
	$err=false;
	// check expected values
	if(strlen($_POST['fname'])==0) { $err=true; $errort .= "First Name is required.<br />"; }
	if(strlen($_POST['lname'])==0) { $err=true; $errort .= "Last Name is required.<br />"; }
	if(strlen($_POST['primnumber'])==0) { $err=true; $errort .= "Primary Contact Number is required.<br />"; }

	if($err) { exit("<tr><td colspan=\"2\" class=\"comp center\">$errort</td></tr></form></table></p></div></body></html>"); }

	// no errors yet, lets assign variables
	$FNAME = $_POST['fname'];
	$LNAME = $_POST['lname'];
	$PRIMNUM = $_POST['primnumber'];
	if(strlen($_POST['company'])>0) { $COMPANY = $_POST['company']; } else { $COMPANY = ''; }
	if(strlen($_POST['secnumber'])>0) { $SECNUMBER = $_POST['secnumber']; } else { $SECNUMBER = ''; }
	if(strlen($_POST['emailaddress'])>0) { $EMAIL = $_POST['emailaddress']; } else { $EMAIL = ''; }
	if(strlen($_POST['addressline1'])>0) { $ADDRESS1 = $_POST['addressline1']; } else { $ADDRESS1 = ''; }
	if(strlen($_POST['addressline2'])>0) { $ADDRESS2 = $_POST['addressline2']; } else { $ADDRESS2 = ''; }
	if(strlen($_POST['city'])>0) { $CITY = $_POST['city']; } else { $CITY = ''; }
	if(strlen($_POST['state'])>0) { $STATE = $_POST['state']; } else { $STATE = ''; }
	if(strlen($_POST['postcode'])>0) { $POSTCODE = $_POST['postcode']; } else { $POSTCODE = ''; }

	/* no_noobs check for SQL injections, etc. Function not yet implemented.
	$fname = no_noobs($FNAME);
	$lname = no_noobs($LNAME);
	$primnum = no_noobs($PRIMNUM);
	$secnum = no_noobs($SECNUMBER);
	$company = no_noobs($COMPANY);
	$email = no_noobs($EMAIL);
	$address1 = no_noobs($ADDRESS1);
	$address2 = no_noobs($ADDRESS2);
	$city = no_noobs($CITY);
	$state = no_noobs($STATE);
	$postcode = no_noobs($POSTCODE);
	*/
	$fname = $FNAME;
	$lname = $LNAME;
	$primnum = $PRIMNUM;
	$secnum = $SECNUMBER;
	$company = $COMPANY;
	$email = $EMAIL;
	$address1 = $ADDRESS1;
	$address2 = $ADDRESS2;
	$city = $CITY;
	$state = $STATE;
	$postcode = $POSTCODE;
	// remove this block when no_noobs() is effected.

	// check database for existing customer (fname, lname, primnum)
	$custchkqry = "SELECT cid FROM customer WHERE firstname='$fname' AND lastname='$lname' AND primnum='$primnum'";
	$custchksql = $db->select($custchkqry);
	$custchkresult = $db->get_row($db->select($custchkqry));
	if($custchkresult[0]!=false) {
		// customer exists!
		$existcid = $custchkresult[0];
		exit("<tr class=\"nor\"><td colspan=\"2\" class=\"comp center\">Customer already exists with Customer ID: $existcid</td></tr>");
	} 
	// made it this far, assume it doesn't exist.
	echo "<tr class=\"nor\"><td colspan=\"2\" class=\"center \">";

	echo "Adding user to database, one moment ...<br />";

	$custdata = array(
	'lastname' => $lname,
	'firstname' => $fname,
	'company' => $company,
	'primnum' => $primnum,
	'secnum' => $secnum,
	'email' => $email,
	'address1' => $address1,
	'address2' => $address2,
	'city' => $city,
	'state' => $state,
	'postcode' => $postcode
	);
	$insertcustdata = $db->insert_array('customer', $custdata);
	if(!insertcustdata) { $db->print_last_error(false); } else {
		echo "Customer added, ID: $insertcustdata<br /><br /><a href=\"addbooking.php?gocust=$insertcustdata\">Click here to create a booking for this customer</a></td></tr>";
	}
}
?>
</form>
</table>
</p>
</div>
</body>
</html>
