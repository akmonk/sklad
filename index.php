<?php
	require_once "database.php";
	
	$dbh=new CDataBase("skladchik", "localhost", "ypbase", "golosneba"); 

	$table = 'prepare_csv';
	
	$query = "SELECT * FROM {$table} WHERE 1";
		// Выполняем запрос к БД
	$result = $dbh->query($query);
	
	// предварительная обработка
	while ($row = $dbh->fetch_array($result)) 
	{
		$replace = array( 
			'"' => '', 
			'(' => '', 
			')' => '' 
		);
		$new_name = str_replace_assoc($replace,$row['name']); 
		$new_name = rtrim ($new_name,"...");
		echo $new_name."<br/>";
	}
	
	
	?>