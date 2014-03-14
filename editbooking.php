<?php
require_once('incl.php');

if(!ISSET($_GET['book']) || !is_numeric($_GET['book'])){header("Location: addbooking.php");} // no booking id, or NaN? go to add

$book = $_GET['book'];

$r = $db->select("SELECT bid, cid, date, compdesc, specifications, password, problem, checklist, labtype, labcost, partsused, partscost, totalcost, comment, status FROM booking WHERE bid='$book'");
$row=$db->get_row($r, 'MYSQL_ASSOC');

?>
<html>
<head>
<title><?php echo $businessname;?> - Edit Job Details</title>
<link rel="stylesheet" href="styles.css" />
</head>
<body>
<div id="main">
<h3>New Booking</h3>
<p>Edit booking. <span class="comp">Fields in red are required</span>.</p>
<table>
<form method="POST">
<tr><td class="right">Customer</td><td class="left">
<?php
// get customer info from database
// we want: firstname, lastname

$gor = $db->select("SELECT firstname, lastname FROM customer WHERE cid=".$row['cid']);
$gorow=$db->get_row($gor, 'MYSQL_ASSOC');
echo $gorow['firstname']." ".$gorow['lastname']." [".$row['cid']."]";


?>
</td></tr>
<tr><td class="center" colspan="2"><h3>Status: <?php if($row['status']!=='c'){echo "In Progress";} else {echo "Completed";}?></h3></td></tr>
<tr><td class="right">Date Started</td><td class="left"><input type="text" size="30" name="bdate" value="<?php echo $row['date'];?>"/></td></tr>
<tr><td class="right">Computer Description</td><td class="left"><textarea name="compdesc" cols="30" rows="3"><?php echo $row['compdesc'];?></textarea></td></tr>
<tr><td class="right">Specifications</td><td class="left"><textarea name="compspecs" cols="30" rows="3"><?php echo $row['specifications'];?></textarea></td></tr>
<tr><td class="right">Problem Description</td><td class="left"><textarea class="comp" name="problem" cols="30" rows="5"><?php echo $row['problem'];?></textarea></td></tr>
<tr><td class="right">Passwords</td><td class="left"><textarea name="passwords" cols="30" rows="3"><?php echo $row['password'];?></textarea></td></tr>
<tr><td class="right">Checklist Required?</td><td class="comp left">
	<input type="radio" name="checklist" value="y" <?php if($row['checklist']=="y") echo "checked=\"checked\"";?>/>Yes  
	<input type="radio" name="checklist" value="n" <?php if($row['checklist']=="n") echo "checked=\"checked\"";?>/>No
</td></tr>
<tr><td class="right">Labour Type</td><td class="left">
	<select name="labtype" class="comp">
		<option value="lacu" <?php if($row['labtype']=="lacu") echo "selected=\"selected\"";?>/>LACU
		<option value="la15" <?php if($row['labtype']=="la15") echo "selected=\"selected\"";?>/>LA15
		<option value="la5d" <?php if($row['labtype']=="la5d") echo "selected=\"selected\"";?>/>LA5D
		<option value="la3d" <?php if($row['labtype']=="la3d") echo "selected=\"selected\"";?>/>LA3D
		<option value="lahu" <?php if($row['labtype']=="lahu") echo "selected=\"selected\"";?>/>LAHU
		<option value="laon" <?php if($row['labtype']=="laon") echo "selected=\"selected\"";?>/>LAON
		<option value="lafl" <?php if($row['labtype']=="lafl") echo "selected=\"selected\"";?>/>LAFL
		<option value="laflwa" <?php if($row['labtype']=="laflwa") echo "selected=\"selected\"";?>/>LAFLWA
	</select>
</td></tr>
<tr><td class="right">Labour Cost</td><td class="left"><input type="text" size="30" name="labcost" value="<?php echo $row['labcost'];?>" /></td></tr>
<tr><td class="right">Parts Used</td><td class="left"><textarea name="partsused" cols="30" rows="3"><?php echo $row['partsused'];?></textarea></td></tr>
<tr><td class="right">Parts Cost</td><td class="left"><input type="text" size="30" name="partscost" value="<?php echo $row['partscost'];?>"/></td></tr>
<tr><td class="right">Total Cost</td><td class="left">$<input type="text" size="30" name="totalcost" value="<?php echo $row['totalcost'];?>"/></td></tr>
<tr><td class="right">Comments</td><td class="left"><textarea name="comments" cols="30" rows="5" ><?php echo $row['comment'];?></textarea></td></tr>
<tr/>
<tr><td class="center" colspan="2"><input type="submit" name="bookgo" value="Update Booking" />&nbsp;<input type="reset" name="clear" value="Clear"/></td></tr>
<tr><td class="center" colspan="2"><a href="javascript:history.go(-1)">&lt; Back</a> | <a href="./">Back to Home</a> | <a href="workchecklist.php?book=<?php echo $book;?>">Work with this Job</a></td></tr>
<?php
if(ISSET($_POST['bookgo'])){
	// booking submitted, lets go!

	// check required values first
	$error=false;
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
	$totalcost = $_POST['totalcost'];
	$comments = $_POST['comments'];

	echo "<tr><td colspan=\"2\" class=\"center\">Updating booking, please wait ...<br />";

	
	
	$bookdata= array(
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
	'totalcost' => $totalcost,
	'comment' => $comments
	);
	$editbookdata = $db->update_array('booking', $bookdata, "bid=$book");
	if(!editbookdata) { $db->print_last_error(false); } else {
		echo "Booking Information updated.<br /><br /><meta http-equiv=\"refresh\" content=\"0\" /></td></tr>";
	}

}
?>
</form>
</table>
</div>
</body>
</html>
