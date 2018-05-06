<?php
	
	function insert_into_database($title,$description,$keyword,$url)
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "SEARCH_ENGINE";
		
		$conn = new mysqli($servername,$username,$password,$database);
		
		$query = "INSERT INTO INFORMATION VALUES('$title','$description','$keyword','$url')";

		if ($conn->query($query) === TRUE )
			echo "data inserted";
		else
			echo "Error:". $conn->error;


		$conn->close();
	}
	
	function retrival($text)
	{
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "SEARCH_ENGINE";

		$data = array();
		$splitted_text = explode(" ",$text);

		$conn = new mysqli($servername,$username,$password,$database);

		if($conn -> connect_errno)
		{
			echo "Connect Failed: ".$mysqli->connect_error;
			exit();
		}

		$query ="";
		$x = 0;
		foreach ($splitted_text as $term) {
			$x++;
			if($x = 1)
			{
				$query = "SELECT * FROM INFORMATION WHERE TITLE LIKE '%$term%' OR KEYWORD LIKE '%$term%' OR DESCRIPTION LIKE '%$term%'";
			}
			else
			{
				$query = $query . " OR SELECT * FROM INFORMATION WHERE TITLE LIKE '%$term%' OR KEYWORD LIKE '%$term%' OR DESCRIPTION LIKE '%$term%'";
			}
			
		}
		
		#$query = "select * from INFORMATION";
		$result = $conn ->query($query);

		if($result -> num_rows >0)
		{
			/* fetch associative array */
			while ($row = $result ->fetch_assoc()){
				$title = $row["TITLE"];
				$url = $row["URL"];
				$keyword = $row["KEYWORD"];
				$description = $row["DESCRIPTION"];
				$data[] = array($title,$url,$keyword,$description);
			}
			

			$result->free();
		}
		$conn->close();
		return $data;
	}
?>