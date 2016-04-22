<!DOCTYPE HTML>
<html>
	<head>
	<title>Display Form Info</title>
	</head>
<body>
<?php
	# Set this debug to 1 if you want to see debugging output on the browser screen. Else reset it to 0.
	$debug = 0;
	# Inserting the input data into a table using mysql
		$database = "sample0";
                if($debug == 1) {
                        echo "Now entering the mysql to enter the data ...........<br>";
                }
                $servername = "localhost";
                $username = "root";
                $password = "root";

                 # Create a connection
                $conn = mysqli_connect($servername, $username, $password);
                if($debug == 1) {
                        echo "Authenticating mysql.....<br>";
                }

                # Check connection
                if(!$conn) {
                        if($debug == 1) {
                                die("mysql connection failed: " . mysqli_connect_error() . "<br");
                        }
                }
                if($debug == 1) {
                        echo "<br>connected with mysql successfully<br>";
                }
		
		 # Selecting the database
                if($debug == 1) {
                        echo "Selecting the database $database......";
                }
                $sql = "use $database";
                if (mysqli_query($conn, $sql)) {
                        if($debug == 1) {
                                echo "<br>Database changed to $database<br>";
                        }
                }
                else {
                        if($debug == 1) {
                                echo "Error using database: $database" . mysqli_error($conn . "<br>");
                        }
                }


		# Displaying the data		

		$sql = "select * from phpform0"; 
		$result = mysqli_query($conn, $sql);
			
		echo "<table border='1'>
		<tr>
		<th>Name</th>
		<th>MIS</th>
		<th>Branch</th>
		</tr>";

		while($row = mysqli_fetch_array($result))
		{
			echo "<tr>";
			echo "<td>" . $row['name'] . "</td>";
			echo "<td>" . $row['mis'] . "</td>";
			echo "<td>" . $row['branch'] . "</td>";
			echo "</tr>";
		}
		echo "</table>";

		 # Closing the mysql connection
                mysqli_close($conn);
                if($debug == 1) {
                        echo "Closed the connection with mysql<br>";
                }

		

?> 
<form method="post" action="form.php">
	<input type="submit" name="redirect_button" value="Redirect to Form.php">
</form>
</body>
</html>
