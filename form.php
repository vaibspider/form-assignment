<!DOCTYPE HTML>
<html>
<head>
	<title>A simple form</title>
	<style> .error {color: #FF0000} </style>
</head>
<body>
<?php
#session_start();
$name = $mis = $branch = $phone = "";
$nameError = $misError = $branchError = $phoneError = "";

# Flags
$error = 0;  ### This flag will be set to '1' even if a single error occurs. It will remain '0' only if no errors are present in the input.
$debug = 1;  ### Set this flag to '1' to see debugging output on the browser page. If you don't want to debug, reset this flag to '0'.

function check_for_hack($info) {
	# protection from injecting malicious code
	$info = htmlspecialchars($info);
	return $info;
}

function validate_form_data() {
	global $name, $mis, $branch, $phone, $error, $debug, $nameError, $misError, $branchError, $phoneError;
	if(empty($_POST['name'])) {
		$nameError = "Name is required";
		$error = 1;
	}
	else {
		$name = check_for_hack($_POST['name']);
		if(!preg_match("/^[a-zA-Z ]*$/", $name)) {
			$nameError = "Only Alphabets are allowed";
			$error = 1;
		}
		else {
			if(strlen($name) > 50) {
				$nameError = "Limited to 50 characters";
				$error = 1;
			}
		}
	}
	
	if(empty($_POST['mis'])) {
		$misError = "MIS is required";
		$error = 1;
	}
	else {
		$mis = check_for_hack($_POST['mis']);
		if(!preg_match("/^[0-9]*$/", $mis)) {
			$misError = "Only digits [0-9] are allowed";
			$error = 1;
		}
		else {
			if(strlen($mis) != 9) {
				$misError = "MIS must be of 9 digits";
				$error = 1;
			}
		}
	}
	
	if(empty($_POST['branch'])) {
		$branchError = "Branch is required";
		$error = 1;
	}
	else {
		$branch= check_for_hack($_POST['branch']);
		if(!preg_match("/^[a-zA-Z ]*$/", $branch)) {
			$branchError = "Only Alphabets and whitespaces are allowed";
			$error = 1;
		}
		else {
			if(strlen($branch) > 60) {
				$branchError = "Limited to 60 characters";
				$error = 1;
			}
		}
	}

	
	if(empty($_POST['phone'])) {
		$phoneError = "phone is required";
		$error = 1;
	}
	else {
		$phone= check_for_hack($_POST['phone']);
		if(!preg_match("/^[0-9]*$/", $phone)) {
			$phoneError = "Only digits [0-9] are allowed";
			$error = 1;
		}
		else {
			if(strlen($phone) != 9) {
				$phoneError = "phone must be of 9 digits";
				$error = 1;
			}
		}
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	validate_form_data();
	
	if($error == 0) {
		echo "Input recorded successfully<br><br>";
                if($debug == 1) {
                        echo "Name: $name<br>";
                        echo "MIS: $mis <br>";
                        echo "Branch: $branch<br>";
			echo "Phone: $phone<br>";
                }
		
		# Inserting the input data into a table using mysql
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

		# Create a database
                if($debug == 1) {
                        echo "Creating the database:...<br>";
                }
                $database = "sample0";
                $sql = "create database $database";
                if(mysqli_query($conn, $sql)) {
                        if($debug == 1) {
                                echo "Database $database created successfully:<br>";
                        }
                }
                else {
                        if($debug == 1) {
                                echo "Error creating database: $database" . mysqli_error($conn) . "<br>";
                        }
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

		# Creating a table
                $table = "phpform0";
                if($debug == 1) {
                        echo "Creating the table '$table'.......<br>";
                }
                $sql = "create table $table(
                        name varchar(52),
                        mis varchar(16),
                        branch varchar(62),
                        primary key(mis)
                        )";
                if (mysqli_query($conn, $sql)) {
                        if($debug == 1) {
                                echo "Table $table created successfully<br>";
                        }
                } else {
                        if($debug == 1) {
                                echo "Error creating table: $table" . mysqli_error($conn) . "<br>";
                        }
                }

		# Inserting the input data in database table
                if($debug == 1) {
                        echo "Inserting the input data in $database database & $table table.......<br>";
                        echo "insert into $table values ('$name', '$mis', '$branch') <br>";
                }
	# echo "name = $name  mis = $mis branch = $branch";
                $sql = "insert into $table values ('$name', '$mis', '$branch')";

                if (mysqli_query($conn, $sql)) {
                        if($debug == 1) {
                                echo "New record created successfully<br>";
                        }
                } else {
                        if($debug == 1) {
                                echo "Error creating new record: " . mysqli_error($conn) . "<br>";
                        }
                }
		
                # Closing the mysql connection
                mysqli_close($conn);
                if($debug == 1) {
                        echo "Closed the connection with mysql<br>";
                }
		
		#$_SESSION['redirect_url'] = "form.php";
		header("Location: display.php");
	}
}
?>
			<h1>Sample Form</h1>

<p><span class="error">* Required Fields </span></p>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["SELF_PHP"]);?>">

	Name: 
	<input type="text" name="name" value="<?php echo $name; ?>">
	<span class="error">* <?php echo $nameError;?> </span>
	<br><br>

	MIS:
	<input type="text" name="mis" value="<?php echo $mis; ?>">
	<span class="error">* <?php echo $misError;?> </span>
	<br><br>

	Branch:
	<input type="text" name="branch" value="<?php echo $branch; ?>">
	<span class="error">* <?php echo $branchError;?> </span>
	<br><br>

	Phone:
	<input type="text" name="phone" value="<?php echo $phone; ?>">
	<span class="error">* <?php echo $phoneError;?> </span>
	<br><br>
	
	<input type="submit" name="submit_button" value="Submit">
	<br><br>
</form>


</body>
</html>
