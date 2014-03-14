<?php
require_once('incl.php');

if(!ISSET($_GET['cust']) || !is_numeric($_GET['cust'])){header("Location: addcustomer.php");} // no customer, or NaN? go to add

$cust = $_GET['cust'];

$r = $db->select("SELECT cid, firstname, lastname, primnum, secnum, company, email, address1, address2, city, state, postcode FROM customer WHERE cid='$cust'");
$row=$db->get_row($r, 'MYSQL_ASSOC');

?>
<html>
<head>
<title><?php echo $businessname; ?> - Edit Customer Information</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>Edit Customer Information</h3>
<p>Please adjust the information below as required, then click 'Update'. <span class="comp">First Name, Last Name and Primary Number</span> are required, all other fields are optional.</p>
<p align="center"><table>
<form method="POST">
<tr><td class="right">Customer Number</td><td class="left"><?php echo $row['cid']; ?></td></tr>
<tr><td class="right">First Name</td><td><input type="text" size="30" name="fname" class="comp" value="<?php echo $row['firstname'];?>"/></td></tr>
<tr><td class="right">Last Name</td><td><input type="text" size="30" name="lname" class="comp" value="<?php echo $row['lastname'];?>"/></td></tr>
<tr><td class="right">Company</td><td><input type="text" size="30" name="company" value="<?php echo $row['company'];?>"/></td></tr>
<tr><td class="right">Primary Contact Number</td><td><input type="text" size="30" name="primnumber" class="comp" value="<?php echo $row['primnum'];?>"/></td></tr>
<tr><td class="right">Secondary Contact Number</td><td><input type="text" size="30" name="secnumber" value="<?php echo $row['secnum'];?>"/></td></tr>
<tr><td class="right">Email Address</td><td><input type="text" size="30" name="emailaddress" value="<?php echo $row['email'];?>"/></td></tr>
<tr><td class="right">Address Line 1</td><td><input type="text" size="30" name="addressline1" value="<?php echo $row['address1'];?>"/></td></tr>
<tr><td class="right">Address Line 2</td><td><input type="text" size="30" name="addressline2" value="<?php echo $row['address2'];?>"/></td></tr>
<tr><td class="right">City</td><td><input type="text" size="30" name="city"  value="<?php echo $row['city'];?>"/></td></tr>
<tr><td class="right">State</td><td class="left">
	<select name="state">
		<option value="NSW" <?php if($row['state']=="NSW") echo "selected=\"selected\""; ?>/>NSW
		<option value="VIC" <?php if($row['state']=="VIC") echo "selected=\"selected\""; ?>/>VIC
		<option value="QLD" <?php if($row['state']=="QLD") echo "selected=\"selected\""; ?>/>QLD
		<option value="TAS" <?php if($row['state']=="TAS") echo "selected=\"selected\""; ?>/>TAS
		<option value="SA" <?php if($row['state']=="SA") echo "selected=\"selected\""; ?>/>SA
		<option value="NT" <?php if($row['state']=="NT") echo "selected=\"selected\""; ?>/>NT
		<option value="WA" <?php if($row['state']=="WA") echo "selected=\"selected\""; ?>/>WA
		<option value="ACT" <?php if($row['state']=="ACT") echo "selected=\"selected\""; ?>/>ACT
	</select>
</td></tr>
<tr><td class="right">Postcode</td><td class="left"><input type="text" size="4" name="postcode" value="<?php echo $row['postcode'];?>"/></td></tr>
<tr/>
<tr><td class="center" colspan="2"><input type="submit" name="custgo" value="Update" />&nbsp;<input type="reset" name="clear" value="Clear"/></td></tr>
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

	echo "<tr class=\"nor\"><td colspan=\"2\" class=\"center \">";

	echo "Updating database with new information, one moment ...<br />";

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
	$editcustdata = $db->update_array('customer', $custdata, "cid=$cust");
	if(!editcustdata) { $db->print_last_error(false); } else {
		echo "Customer updated.<br /><br /><a href=\"addbooking.php?gocust=".$row['cid']."\">Click here to create a booking for this customer</a><meta http-equiv=\"refresh\" content=\"0\" /></td></tr>";
	}
}

?>
</form>
</table>
<table>
<tr><td colspan="4" class="center"><h3><?php echo $row['firstname']." ".$row['lastname']."'s Jobs";?></h3></td></tr>
<tr><td colspan="4" class="center">[<a href="addbooking.php?gocust=<?php echo $cust;?>">create new job</a>]</td></tr>
<tr><th>Date</th><th>Problem</th><th>Status</th><th>View</th></tr>
<?php
$ohyea = "SELECT booking.bid, booking.problem, booking.date, booking.status FROM booking JOIN customer ON booking.cid = customer.cid WHERE booking.cid='$cust'  ORDER BY booking.date ASC";
$goyea = $db->select($ohyea);
$i=0;
while($t = $db->get_row($goyea)){
	echo "<tr class=\"";
	if($i%2){echo "nor";} else {echo"alt";}
	echo "\"><td>".$t['date']."</td>";
	if(strlen($t['problem'])<50) {
		echo "<td class=\"left\">".$t['problem']."</td>";
	} else {
		$cut = substr($t['problem'],0,50);
		echo "<td class=\"left\">$cut</td>";
	}
	echo "<td>";
	switch($t['status']){
		case 'i':
			echo "In Progress";
			break;
		case "c":
			echo "Completed";
			break;
	}
	echo "</td>";
	echo "<td>[<a href=\"workchecklist.php?book=".$t['bid']."\">work</a>]</td>";
	echo "</tr>";
	$i++;
}
?>
</table>
</p>
</div>
</body>
</html>
