<?php
use Aurora\Framework\QueryFilter;
$UUID = $email = null;
$WERROR="This isn't a valid code, or the code is older than 24 hours";
if(isset($_GET['code'])){
	$filter = new QueryFilter;
	$filter->andFilters['code'] = $_GET['code'];
	$filter->andFilters['info'] = 'pwreset';
	list($UUID,$email) = Globals::i()->DBLink->Query(array('UUID', 'email'), C_CODES_TBL, $filter);
	if(isset($UUID)){
		$WERROR="Thank you, we sent you a new Password to $email";
		function gen_pass(){
			$rand_pass = mt_rand(6,10);
			for($i = 0;$i < $rand_pass; $i++){
				switch (mt_rand(1,3)){
					case 1:
						$pass .= chr(mt_rand(48, 57));
					break;
					case 2:
						$pass .= chr(mt_rand(97, 122));
					break;
					case 3:
						$pass .= chr(mt_rand(65, 90));
					break;
				}
			}
			return($pass);
		}

		$pass = gen_pass();

		if (Configs::d()->ForgotPassword($UUID, $pass)){
			Globals::i()->DBLink->Delete(C_CODES_TBL, $filter);
			//-----------------------------------MAIL--------------------------------------
			 $date_arr    = getdate();
			 $date        = "$date_arr[mday].$date_arr[mon].$date_arr[year]";
			 $sendto      = $email;
			 $subject     = "Account Password from " . SYSNAME;
			 $body       .= "Your Password was changed at " . SYSNAME . ".\n";
			 $body       .= "The new Password for " . SYSNAME . " is:";
			 $body       .= "\n\n";
			 $body       .= "$pass";
			 $body       .= "\n\n";
			 $body       .= "Thank you for using " . SYSNAME . "";
			 $header      = "From: " . SYSMAIL . "\r\n";
			 $mail_status = mail($sendto, $subject, $body, $header);
			//-----------------------------MAIL END --------------------------------------
		}
	}
}
?>
<table width="100%" height="425" border="0" align="center">
	<tr>
		<td valign="top">
			<table width="50%" border="0" align="center">
				<tr>
					<td><p align="center" class="Stil1">Change Password</p></td>
				</tr>
			</table>
			<br />
			<table width="79%" height="199" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#FFFFFF">
				<tr>
					<td valign="top">
						<br />
						<br />
<?php if(isset($WERROR)){?>
						<font color="FF0000"><?php echo $WERROR; ?></font><br>
<?php } ?>
						<br />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
