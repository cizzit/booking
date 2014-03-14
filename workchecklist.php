<?php
require_once('incl.php');

if(ISSET($_GET['autocom'])){
	$self = $_SERVER["PHP_SELF"]."?book=".$_GET['book'];
	if(!is_numeric($_GET['autocom'])){header("Location: ".$self);}
	$current = $_GET['autocom'];
	if($current>=19){header("Location: ".$self);}
	for($i=$current;$i<19;$i++){
		$dodat = array('step'.$i => 'y', 'cmnt'.$i => 'autocompleted');
		$godat = $db->update_array('checklist', $dodat, "bid=".$_GET['book']);
	}
}

if(!ISSET($_GET['book']) || !is_numeric($_GET['book'])){header("Location: addbooking.php");} // no job id, or NaN? go to add

$book = $_GET['book'];

$r = $db->select("SELECT booking.bid, booking.cid, booking.problem, booking.password, booking.checklist, booking.labtype, booking.comment, booking.totalcost, customer.firstname,customer.lastname,customer.primnum FROM booking JOIN customer ON booking.cid = customer.cid WHERE booking.bid='$book'");

$row=$db->get_row($r, 'MYSQL_ASSOC');

if(ISSET($_POST['update'])){
	// update has been posted, lets process it now before the database calls
	// expected values will be: stepx and cmntx
	// hidden value of curstep will be x
	$current = $_POST['curstep'];

	// if comment is set but no step, just update comment
	// if step is set with no comment, just update comment
	// if both are set, update both
	$comment = $_POST['cmnt'.$current];
	$step = $_POST['step'.$current];	


	if($current == "20") {
		// current step is SMS, let's send that shit!
		// we are going to ignore everything thats in the comment field, since we will populate it with our results
		// need to get the following info to pass: mobile number, total cost, customer number, job number

		$mob = $row['primnum'];
		$ttl = $row['totalcost'];
		$cst = $row['cid'];
		$jbn = $row['bid'];
		
		if(!$ttl) $ttl = "$??.??";

		$rawr = send_SMS($mob, $ttl, $cst, $jbn);

		// $rawr will look like this: 0|04xxxxxxxx|c11j2222||Invalid Username or Password
		// or like this: 1|04xxxxxxxx|c11j2222|x8df94|Sent
		// in the format statuscode|receivernumber|ourreference|exetelreference|statusdescription

		$response = explode("|",$rawr);
		switch($response[0]){
			  case "2":
			   $stscode="Failed";
			   break;
			  case "1":
			   $stscode = "Sent";
			   break;
			  default:
			   // must be zero
			   $stscode="Rejected";
		 }
		$comment = "$stscode - ".$response[4]." (<a title=\"Exetel Reference Number\">".$response[3]."</a>|<a title=\"Our Reference Number\">".$response[2]."</a>)";
		if(!$step) $step="step20";
	}

	if(!$step) {
		// not progressing, just update comment
		$data = array('cmnt'.$current => $comment);
		$godo = $db->update_array('checklist', $data, "bid=$book");
	} else {
		// progressing and commenting if applicable
		$data = array('step'.$current => 'y', 'cmnt'.$current => $comment);
		$godo = $db->update_array('checklist', $data, "bid=$book");
	}

}
if(ISSET($_POST['complete'])){
	// complete has been posted
	$comjob = $_POST['done'];
	if(!is_numeric($comjob)){ header("Location:index.php"); }
	// mark the last step as completed like above, then mark the booking job as completed
	$current = $_POST['curstep'];
	
	// if comment is set but no step, just update comment
	// if step is set with no comment, just update comment
	// if both are set, update both
	$comment = $_POST['cmnt'.$current];
	$step = $_POST['step'.$current];

	if(!$step) {
		// not progressing, just update comment
		$data = array('cmnt'.$current => $comment);
		$godo = $db->update_array('checklist', $data, "bid=$book");
	} else {
		// progressing and commenting if applicable
		$data = array('step'.$current => 'y', 'cmnt'.$current => $comment);
		$godo = $db->update_array('checklist', $data, "bid=$book");
	}

	// now to mark the booking job as completed
	$qrydata = array('status' => 'c');
	$qrydo = $db->update_array('booking', $qrydata, "bid=$book");
	$grandold = "<tr><td colspan=\"5\" class=\"center\"><h2>Job Complete!</h2></td></tr>\n";


}

if(ISSET($_POST['gocmnt']) && is_numeric($_POST['book'])){
	// comment has been updated
	// expected vars: POST['book'], POST['comments']
	$docomment = $_POST['comments'];
	
	$data = array('comment' => $docomment);
	$rows = $db->update_array('booking', $data, "bid=$book");
	if($rows>0) $domememe="OMGWTFBBQ"; //force a screen update to show new comments
}

// generate checklist
$checklistfull = array(
'1' => "Place at Yellow Bay",
'2' => "Air Clean",
'3' => "Place at SW/HW Bay",
'4' => "Run AV CD",
'5' => "RogueFix",  
'6' => "ComboFix",   
'7' => "Malware",  
'8' => "Eset",
'9' => "HijackThis",
'10' => "CClean",
'11' => "Check Drivers",
'12' => "Windows Update",
'13' => "Check Sound",
'14' => "Install AV",
'15' => "Run CHKDSK",
'16' => "Uninstall Test Programs",
'17' => "Fix Restart Issues",
'18' => "Check Problems",
'19' => "Check and Remove CDs",
'20' => "Clean Case",
'21' => "SMS Client",
'22' => "Green Bay",
'23' => "Customer Pickup"
);
$checklisthalf = array(
'1' => "Place at Yellow Bay",
'2' => "Air Clean",
'3' => "Place at SW/HW Bay",
'18' => "Check Problems",
'19' => "Check and Remove CDs",
'20' => "Clean Case",
'21' => "SMS Client",
'22' => "Green Bay",
'23' => "Customer Pickup");

$checklistonsite = array(
'1' => "Start Job",
'23' => "Finish Job"
);


?>
<html>
<head>
<title><?php echo $row['firstname']." ".$row['lastname'];?> - Work with Job</title>
<link rel="stylesheet" href="styles.css" />
<meta http-equiv="refresh" content="60" />

</head>
<body>
<div id="main">
<h3>Checklist</h3>
<p>Click Update next to the currently active line to update the status. Do this after entering a comment or ticking the 'Completed' box.</p>
<table>
<tr><th width="100" class="right">Customer</th><th class="left"><?php echo $row['firstname']." ".$row['lastname']." [".$row['cid']."]";?></th></tr>
<tr><th width="100" class="right">Contact No.</th><th class="left"><?php echo $row['primnum'];?></th></tr>
<tr><th width="100" class="right">Booking ID</th><th class="left"><?php echo $row['bid'];?></th></tr>
<tr><th width="100" class="right">Problem</th><th class="left"><?php
echo nl2br($row['problem']);
?></th></tr>
<tr><th width="100" class="right">Labour Type</th><th class="left">[<?php echo strtoupper($row['labtype'])."]";?></th></tr>
<?php if($row['password']) {
?>
<tr><th width="100" class="right">Passwords<br /><span class="small">(highlight to reveal)</span></th><th class="left"><span class="password"><?php echo nl2br($row['password']);?></span></th></tr>
<?php
}
?>
<tr><th colspan="2" class="center"><a href="editbooking.php?book=<?php echo $book;?>">Change Job Description</a></th></tr>
<tr><th colspan="2" class="center">

<script language="JavaScript">
//configure refresh interval (in seconds)
var countDownInterval=60;
//configure width of displayed text, in px (applicable only in NS4)
var c_reloadwidth=200
</script>
<ilayer id="c_reload" width=&{c_reloadwidth}; ><layer id="c_reload2" width=&{c_reloadwidth}; left=0 top=0></layer></ilayer>
<script>
var countDownTime=countDownInterval+1;
function countDown(){
countDownTime--;
if (countDownTime <=0){
countDownTime=countDownInterval;
clearTimeout(counter)
//window.location.reload()
return
}
if (document.all) //if IE 4+
document.all.countDownText.innerText = countDownTime+" ";
else if (document.getElementById) //else if NS6+
document.getElementById("countDownText").innerHTML=countDownTime+" "
else if (document.layers){ //CHANGE TEXT BELOW TO YOUR OWN
document.c_reload.document.c_reload2.document.write('Next refresh in <strong id="countDownText">'+countDownTime+' </strong> seconds.')
document.c_reload.document.c_reload2.document.close()
}
counter=setTimeout("countDown()", 1000);
}

function startit(){
if (document.all||document.getElementById) //CHANGE TEXT BELOW TO YOUR OWN
document.write('Next refresh in <strong id="countDownText">'+countDownTime+' </strong> seconds.')
countDown()
}

if (document.all||document.getElementById)
startit()
else
window.onload=startit
</script>

</th></tr>
</table>
<table><form method="POST">
<?php
// now for the serious stuff.
// need to get the current step of the job from booking.checkliststatus as to where it's last step was up to (defaults to '1')
// maybe not, see how this works out
// get the booking id and all the columns from checklist, then in a for() loop, check to see if checklist.stepx has been set to yes. If so, disable the udpate, etc. Either way, show the comment if application in cohecklist.commentx

if($row['checklist']=="n") {
	$query = "SELECT bid, step0, cmnt0, step1, cmnt1, step2, cmnt2, step17, cmnt17, step18, cmnt18, step19, cmnt19, step20, cmnt20, step21, cmnt21, step22, cmnt22 FROM checklist WHERE bid='$book'";
	$ray = $checklisthalf;
} else {
	$query = "SELECT * FROM checklist WHERE bid='$book'";
	$ray = $checklistfull;
}
if($row['labtype']=="laon") {
	// onsite
	$query = "SELECT bid, step0, cmnt0, step22, cmnt22 FROM checklist WHERE bid='$book'";
	$ray = $checklistonsite;
	
}
$chkrow = $db->select($query);
$chkrow = $db->get_row($chkrow);
// now, echo the checklist table
echo '<tr class="nor"><th class="right">Step</th><th>Description</th><th>Completed</th><th>Comments (max. 60 characters)</th><th>Action</th>';

for($i=0;$i<23;$i++){


	if($row['labtype']=="laon") {
		if($i>0&&$i<22){$i=22;}
	}
	else if($row['checklist']=="n") {
		// need to seperate the fields alot.
		// bring this logic up to the correct row
		if($i>2&&$i<17) { $i=17; }
	}

		$u=$i+1;	
		if($chkrow['step'.$i]=="n" && !$currentstep) {
			// assume this is the current step
			$currentstep=true;
			$socurrent = $i;
			echo "<tr class=\"comp\">";
			echo "<td class=\"right\">$u</td>";
			echo "<td>".$ray[$u]."</td>";
			if($i==20){
				echo "<td class=\"center\">&nbsp;</td>";
				echo "<td class=\"small\">Make sure the 'Total' field is correct, then press 'Update' to send SMS.</td>";
			} else {
				echo "<td class=\"center\"><input type=\"checkbox\" name=\"step$i\" /></td>";
				echo "<td><input type=\"text\" name=\"cmnt$i\" size=\"50\" maxlength=\"60\" value=\"".$chkrow['cmnt'.$i]."\"/></td>";
			}
			if($i<22){
				echo "<td><input type=\"hidden\" name=\"curstep\" value=\"$i\"/><input type=\"submit\" class=\"btn\" name=\"update\" value=\"Update\" />";
			} else {
				echo "<td><input type=\"hidden\" name=\"curstep\" value=\"$i\"/><input type=\"hidden\" name=\"done\" value=\"$book\"/><input type=\"submit\" class=\"btn\" name=\"complete\" value=\"Complete Job\" />";
			}
			echo "</tr>";
		
		} else {
			// not the current step, need to disable checkboxes, not show input boxes but values only if they exist. Also, no submit button.
			$numcas = $i%2;
			switch($numcas){
				case true:
					echo "<tr class=\"nor\">";
					break;
				case false:
					echo "<tr class=\"alt\">";
					break;
			}
			echo "<td class=\"right\">$u</td>";
			echo "<td>".$ray[$u]."</td>";
			echo "<td class=\"center\">";
				if($chkrow['step'.$i]=='y'){echo "X";}
			echo "</td>";
			echo "<td class=\"left smaller\"> ".$chkrow['cmnt'.$i]."&nbsp;</td>";
			echo "<td>&nbsp;</td>";
			echo "</tr>";
		}
	}
if($grandold){echo $grandold;}
?>
<tr><td colspan="5"><a href="viewbookings.php">&lt; Back</a> | <a href="./">Back to Home</a>
<?php
if($socurrent && $socurrent<19){

	echo " | <a href=\"".$_SERVER['REQUEST_URI']."&autocom=$socurrent\">AUTOCOMPLETE</a> (up to step 19)";
}
if($domememe=="OMGWTFBBQ") echo "<meta http-equiv=\"refresh\" content=\"0\" />"; // force an update of the screen
?>
</td></tr>
</form>
</table>
<table>
<form method="POST">
<tr>
<td width="100">Comments</td>
<td><textarea rows="5" cols="30" name="comments"><?php echo $row['comment']; ?></textarea></td>
<input type="hidden" name="book" value="<?php echo $book;?>"/>
<tr><th colspan="2"><input type="submit" name="gocmnt" value="Update Comment" /></th></tr>
</tr>
</form>
</table>
</div>
</body>
</html>
