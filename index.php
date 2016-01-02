<?php
	require_once "database.php";
	
	$dbh=new CDataBase("skladchik", "localhost", "ypbase", "golosneba"); 

	$table = 'prepare_csv';
	
	$query = "SELECT * FROM {$table} WHERE 1";
		// Выполняем запрос к БД
	$result = $dbh->query($query);
	
	while ($row = $dbh->fetch_array($result)) 
	{
		echo $row['name']. "<br/>";
	}
	?>