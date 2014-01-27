<html>
	
	<head>
	<script src="js/jquery-2.0.3.min.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
	</head>
	
	<h1> Dev Page </h1>
	<form action="#" method="post">
	Machine Name: <input type="text" name="name"><br>
	<input type="submit">
	</form>
	
	<?php
		if (isset($_POST["name"]))
		{
			$machine = $_POST["name"];
			echo "<h1>" . $_POST["name"] . "</h1>";
			
			//Connect to the machine we want the information off of
			try {
				$objWMIService = new COM("winmgmts:\\\\" . $machine);
				
				//Get x86 or x64 status
				$getArchitecture = $objWMIService->ExecQuery("Select SystemType from Win32_ComputerSystem");
				//Modelname of the computer
				$getModel = $objWMIService->ExecQuery("Select Model from Win32_ComputerSystem");
				//Currently logged on users
				$getUsers = $objWMIService->ExecQuery("Select UserName from Win32_ComputerSystem");
				
				
				//Connect to the machine we want the information off of
				$objWMIService = new COM("winmgmts:\\\\" . $machine . "\\root\cimv2") or die("Unable to connect to machine");
				$colItems = $objWMIService->ExecQuery("Select * From Win32_NetworkAdapterConfiguration Where IPEnabled = True"); //Fetch items that will contain mac information
				$colPrinters = $objWMIService->ExecQuery("Select * From Win32_Printer"); //Query that machine for a list of all printers
				
				//User History
				$colUsers = $objWMIService->ExecQuery("Select * From Win32_NetworkLoginProfile");
				
				//Installed Programs
				define('HKEY_LOCAL_MACHINE', 0x80000002);
				$strKey = "SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall";
				$strEntry1a = "DisplayName"; 
				$strEntry1b = "QuietDisplayName"; 
				$strEntry2 = "InstallDate"; 
				$strEntry3 = "VersionMajor";
				$strEntry4 = "VersionMinor"; 
				$strEntry5 = "EstimatedSize";
				$strValue1 = $strValue2 = $strValue3 = $strValue4 = 0;
				
				$reg = new COM("winmgmts:{impersonationLevel=impersonate}!\\\\{$machine}\\root\\default:StdRegProv");

				//$key_path = 'SOFTWARE\Microsoft'; // this line will get all services
				$key_path = 'Software\Microsoft\Windows\CurrentVersion\Uninstall'; // this line will get all installed software

				$sub_keys = new VARIANT();
				$reg->EnumKey(HKEY_LOCAL_MACHINE,$key_path,$sub_keys);
				
			} catch (Exception $e) {
				exit($e->getMessage() . " The computer may be offline, disconnected, or typed incorrectly.");
			}
		} else exit();
	?>
	
	<div id="info">
		<table border = "1">
			<tr>
				<td><b>Architecture</b></td>
				<td>
				<?php
					foreach ($getArchitecture as $type)
						echo $type->SystemType;
				?>
				</td>
			</tr>
			<tr>
				<td><b>Model</b></td>
				<td>
				<?php
					foreach ($getModel as $type)
						echo $type->Model;
				?>
				</td>
			</tr>
		</table>
		
		<h2> Network Controllers </h2>
		<table border = "1">
		<tr>
			<td><b>MAC</b></td>
			<td><b>Description</b></td>
		</tr>
			<?php
				foreach($colItems as $value)
				{ //Step through them all
					echo "<tr>";
					echo "<td>" . $value->MACaddress . "</td>";
					echo "<td>" . $value->Description . "</td>";
					echo "</tr>";
				}
			?>
		</table>
		<br>
		<h2> Locally Installed Printers </h2>
		<table border = "1">
			<tr>
				<td><b>Name</b></td>
				<td><b>Driver Name</b></td>
				<td><b>IP</b></td>
			</tr>
			<?php
				foreach($colPrinters as $value)
				{ //Step through them all
					echo "<tr>";
					echo "<td>" . $value->Name . "</td>";
					echo "<td>" . $value->DriverName . "</td>";
					echo "<td>" .$value->PortName . "</td>";
					echo "</tr>";
				}
			?>
		</table>
		
		<?php
			echo "<h2> Logged on User</h2>";
			$i = 0;
			foreach ($getUsers as $user)
			{
				echo $user->UserName;
				$i++;
			}
		?>
		<br>
		
		<h2> User Profiles </h2>
		<table border = "1">
			<tr>
				<td><b>EUID</b></td>
			</tr>
			<?php
				
				foreach ($colUsers as $user)
				{
					if ($user->Name != null && substr($user->Name,0,3) == "UNT")
					{
						echo "<tr>";
						echo "<td>" . $user->Name . "</td>";
						echo "</tr>";
					}
				}
				
			?>
		</table>
		<br>
		
		<h2> Programs List (Massive) </h2>
		<table border = "1">
		<tr>
			<td><b>Name</b></td>
		</tr>
		<?php
			foreach($sub_keys as $sub_key){
				$intRet1 = $reg->GetStringValue(HKEY_LOCAL_MACHINE, $strKey . $sub_key, $strEntry1a, $strValue1);
				echo "<tr>";
				echo "<td>" . $sub_key . "</td>";
				echo "</tr>";
			}
		?>
	</div>
</html>
