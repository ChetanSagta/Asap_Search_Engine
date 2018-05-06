<html>
	<head><title></title>
		<link rel="stylesheet" type="text/css" href="effects.css"/>
	</head>
	<body>
	<?php
		include("database.php");
		$input = $_GET["text"];
		if($input ==""){
			echo "PLEASE ENTER SOMETHING TO SEARCH";
			exit(0);
		}
		$data  = array();
		$data = retrival($input);
	?>
	<a href ="https://localhost/asap/search.html"><img src = "242854115621-350-copy.png" length= "100" width="100"></a>&emsp;&emsp;&emsp;&emsp;&emsp;
		<input type="text" value="" style="width: 250px" class="search">
		<input type="submit" value="submit" class="submit"><br><hr>
		<?php
		if(empty($data)){
			echo "SORRY NO DATA FOUND BASED ON YOUR QUERY";
			exit(0); } 
		
		else
		{
			echo '<div>';
			for($i=0;$i<count($data);$i++)
			{
				echo '<a href='.$data[$i][1].'>'.$data[$i][0].'</a><br>';
				if( ($data[$i][3]) == "")
					$data[$i][3] = "No Description Available";
				echo $data[$i][3].'<br>';
				echo  $data[$i][1].'<br>';
				echo '<hr>';
			}
			echo '</div>';
		}
		
	?>
	</body>
</html>
