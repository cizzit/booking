<?php
require_once('incl.php');
?>
<html>
<head>
<title><?php echo $businessname;?> - Create Booking</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>New Booking</h3>
<p>Create new booking. <span class="comp">Fields in red are required</span>.</p>
<table>
<form method="POST">
<tr><td class="right">Choose Customer</td><td class="left">
	<select name="selcust" class="comp">
<option value=""/>------------------
<?php
// get customer info from database
// we want: cid, firstname, lastname, primnum

$r = @$db->select("SELECT cid, firstname, lastname, primnum FROM customer ORDER BY lastname ASC");
while (@$row=$db->get_row($r, 'MYSQL_ASSOC')) {
   echo "<option value=\"".$row['cid']."\"";
	if($_GET['gocust']==$row['cid']){echo " selected=\"selected\"";}
	echo "/>[".$row['cid']."] ".$row['lastname'].", ".$row['firstname']." - ".$row['primnum']."\n";
} 

?>
	</select> 
[<a href="./addcustomer.php">Add New]</td></tr>
<tr><td class="right">Date Started</td><td class="left"><input type="text" size="30" name="bdate" value="<?php echo date("d-m-Y");?>"/></td></tr>
<tr><td class="right">Computer Description</td><td class="left"><textarea name="compdesc" cols="30" rows="3"><?php 
if(ISSET($_POST['compdesc'])){echo stripslashes($_POST['compdesc']);}?></textarea></td></tr>
<tr><td class="right">Specifications</td><td class="left"><textarea name="compspecs" cols="30" rows="3"><?php 
if(ISSET($_POST['compspecs'])){echo stripslashes($_POST['compspecs']);}?></textarea></td></tr>
<tr><td class="right">Problem Description</td><td class="left"><textarea class="comp" name="problem" cols="30" rows="5"><?php 
if(ISSET($_POST['problem'])){echo stripslashes($_POST['problem']);}?></textarea></td></tr>
<tr><td class="right">Passwords</td><td class="left"><textarea name="passwords" cols="30" rows="3"><?php 
if(ISSET($_POST['passwords'])){echo stripslashes($_POST['passwords']);}?></textarea></td></tr>
<tr><td class="right">Checklist Required?</td><td class="comp left">
	<input type="radio" name="checklist" value="y" <?php if($_POST['checklist']!=='n'){echo 'checked="checked"';}?> />Yes  
	<input type="radio" name="checklist" value="n" <?php if($_POST['checklist']=='n'){echo 'checked="checked"';}?>/>No
</td></tr>
<tr><td class="right">Labour Type</td><td class="left">
	<select name="labtype" class="comp">
		<option value="lacu" <?php if($_POST['labtype']=='lacu') echo 'selected="selected"'; ?>/>LACU
		<option value="la15" <?php if($_POST['labtype']=='la15') echo 'selected="selected"'; ?>/>LA15
		<option value="la5d" <?php if($_POST['labtype']=='la5d') echo 'selected="selected"'; ?>/>LA5D
		<option value="la3d" <?php if($_POST['labtype']=='la3d') echo 'selected="selected"'; ?>/>LA3D
		<option value="lahu" <?php if($_POST['labtype']=='lahu') echo 'selected="selected"'; ?>/>LAHU
		<option value="laon" <?php if($_POST['labtype']=='laon') echo 'selected="selected"'; ?>/>LAON
		<option value="lafl" <?php if($_POST['labtype']=='lafl') echo 'selected="selected"'; ?>/>LAFL
		<option value="laflwa" <?php if($_POST['labtype']=='laflwa') echo 'selected="selected"'; ?>/>LAFLWA
	</select>
</td></tr>
<tr><td class="right">Labour Cost</td><td class="left"><input type="text" size="30" name="labcost" <?php if(ISSET($_POST['labcost'])){echo 'value="'.stripslashes($_POST['labcost']).'"';} ?>/></td></tr>
<tr><td class="right">Parts Used</td><td class="left"><textarea name="partsused" cols="30" rows="3"><?php 
if(ISSET($_POST['partsused'])){echo stripslashes($_POST['partsused']);}?></textarea></td></tr>
<tr><td class="right">Parts Cost</td><td class="left"><input type="text" size="30" name="partscost" <?php if(ISSET($_POST['partscost'])){echo 'value="'.stripslashes($_POST['partscost']).'"';} ?>/></td></tr>
<tr><td class="right">Comments</td><td class="left"><textarea name="comments" cols="30" rows="5" ><?php 
if(ISSET($_POST['comments'])){echo stripslashes($_POST['comments']);}?></textarea></td></tr>
<tr/>
<tr><td class="center" colspan="2"><input type="submit" name="bookgo" value="Create Booking" />&nbsp;<input type="reset" name="clear" value="Clear"/></td></tr>
<tr><td class="center" colspan="2"><a href="javascript:history.go(-1)">&lt; Back</a> | <a href="./">Back to Home</a></td></tr>
<?php
if(ISSET($_POST['bookgo'])){
	// booking submitted, lets go!

	// check required values first
	$error=false;
	if(strlen($_POST['selcust'])==0){$error=true; $errort .= "Customer must be chosen.<br />";}
	if(strlen($_POST['problem'])==0){$error=true; $errort .= "Problem Description must be entered.<br />";}
	if(strlen($_POST['checklist'])==0){$error=true; $errort .= "Checklist Option must be selected (either (Y)es or (N)o.<br />";}
	if(strlen($_POST['labtype'])==0){$error=true; $errort .= "Labour Type must be selected.<br />";}

	if($error){echo "<tr><td colspan=\"2\" class=\"comp center\">$errort</td></tr></form></table></div></body></html>";exit();}

	// assign values
	$cust = $_POST['selcust'];
	$startdate = $_POST['bdate'];
	$compdesc = $_POST['compdesc'];
	$compspec = $_POST['compspecs'];
	$problem = $_POST['problem'];
	$passwords = $_POST['passwords'];
	$checklist = $_POST['checklist'];
	$labtype = $_POST['labtype'];
	$labcost = $_POST['labcost'];
	$partsused = $_POST['partsused'];
	$partscost = $_POST['partscost'];
	$comments = $_POST['comments'];

	echo "<tr><td colspan=\"2\" class=\"center\">Creating booking, please wait ...<br />";

	switch ($labtype) {
		case "lacu":
		case "lafl":
		case "laflwa":
			$checklist = "n";
			break;
		default:
			$checklist = "y";
	}


	$bookdata= array(
	'cid' => $cust,
	'date' => $startdate,
	'compdesc' => $compdesc,
	'specifications' => $compspec,
	'password' => $passwords,
	'problem' => $problem,
	'checklist' => $checklist,
	'labtype' => $labtype,
	'labcost' => $labcost,
	'partsused' => $partsused,
	'partscost' => $partscost,
	'comment' => $comments
	);
	$insertbooking = @$db->insert_array('booking', $bookdata);
	if(!$insertbooking) { @$db->print_last_error($false); } else {
		echo "Booking created, Booking ID: $insertbooking<br /><br /><a href=\"workchecklist.php?book=$insertbooking\">Click here to begin the checking procedure.</a></td></tr>";
	}
	$updatecheck = @$db->select("INSERT INTO checklist(bid) VALUES ($insertbooking)");
/*	if($checklist=='n') {
		// have to 'mark as done' all the ones in checklist database that aren't applicable.
		$qrydata = array(
	'step4' => 'y',
	'step5' => 'y',
	'step6' => 'y',
	'step7' => 'y',
	'step8' => 'y',
	'step9' => 'y',
	'step10' => 'y',
	'step11' => 'y',
	'step12' => 'y',
	'step13' => 'y',
	'step14' => 'y',
	'step15' => 'y',
	'step16' => 'y',
	'step17' => 'y',
	'cmnt4' => 'Skipped.',
	'cmnt5' => 'Skipped.',
	'cmnt6' => 'Skipped.',
	'cmnt7' => 'Skipped.',
	'cmnt8' => 'Skipped.',
	'cmnt9' => 'Skipped.',
	'cmnt10' => 'Skipped.',
	'cmnt11' => 'Skipped.',
	'cmnt12' => 'Skipped.',
	'cmnt13' => 'Skipped.',
	'cmnt14' => 'Skipped.',
	'cmnt15' => 'Skipped.',
	'cmnt16' => 'Skipped.',
	'cmnt17' => 'Skipped.'
	);
$rows = $db->update_array('checklist', $qrydata, "bid=$insertbooking");
	}*/


}
?>
</form>
</table>
</div>
</body>
</html>
