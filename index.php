<html>
	<?php if (isset($_POST["name"])) $machine = $_POST["name"];//"ABN-ABN-5B5J8V1"; ?>
	
	<form action="#" method="post">
	Machine Name: <input type="text" name="name"><br>
	<input type="submit">
	</form>
	
	<?php if (isset($_POST["name"])) echo "<h1>" . $_POST["name"] . "</h1>";?>
	
	<table border = "1">
		<tr>
			<td><b>Architecture</b></td>
			<td>
			<?php
				if (isset($_POST["name"]))
				{
					$objWMIService = new COM("winmgmts:\\\\" . $machine) or Die ("Unable to connect to machine, rights issue?"); //Connect to the machine we want the information off of
					$getArchitecture = $objWMIService->ExecQuery("Select SystemType from Win32_ComputerSystem");
					foreach ($getArchitecture as $type)
						echo $type->SystemType;
					
				}
				
			?>
			</td>
		</tr>
		<tr>
			<td><b>Model</b></td>
			<td>
			<?php
				if (isset($_POST["name"]))
				{
					$objWMIService = new COM("winmgmts:\\\\" . $machine) or Die ("Unable to connect to machine, rights issue?"); //Connect to the machine we want the information off of
					$getModel = $objWMIService->ExecQuery("Select Model from Win32_ComputerSystem");
					foreach ($getModel as $type)
						echo $type->Model;
					
				}
				
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
			if (isset($_POST["name"]))
			{
				$objWMIService = new COM("winmgmts:\\\\" . $machine . "\\root\cimv2") or Die ("Unable to connect to machine, rights issue?"); //Connect to the machine we want the information off of
				$colItems = $objWMIService->ExecQuery("Select * From Win32_NetworkAdapterConfiguration Where IPEnabled = True"); //Fetch items that will contain mac information
				$colPrinters = $objWMIService->ExecQuery("Select * From Win32_Printer"); //Query that machine for a list of all printers
				
				foreach($colItems as $value)
				{ //Step through them all
					echo "<tr>";
					echo "<td>" . $value->MACaddress . "</td>";
					echo "<td>" . $value->Description . "</td>";
					echo "</tr>";
				}
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
			if (isset($_POST["name"]))
			{
				foreach($colPrinters as $value)
				{ //Step through them all
					echo "<tr>";
					echo "<td>" . $value->Name . "</td>";
					echo "<td>" . $value->DriverName . "</td>";
					echo "<td>" .$value->PortName . "</td>";
					echo "</tr>";
				}
			}
		?>
	</table>
	
	<?php
		if (isset($_POST["name"]))
		{
			echo "<h2> Logged on Users (Locally and not locked)</h2>";
			$objWMIService = new COM("winmgmts:\\\\" . $machine) or Die ("Unable to connect to machine, rights issue?"); //Connect to the machine we want the information off of
			$getUsers = $objWMIService->ExecQuery("Select UserName from Win32_ComputerSystem");
			foreach ($getUsers as $user)
				echo $user->UserName;
			
		}
		
	?>
</html>
