<?php
	require_once "database.php";
	
	function str_replace_assoc(array $replace, $subject) 
	{ 
		return str_replace(array_keys($replace), array_values($replace), $subject);    
	}
	
	$dbh=new CDataBase("skladchik", "localhost", "ypbase", "golosneba"); 

	$table = 'prepare_csv';
	
	$query = "SELECT * FROM {$table} WHERE 1";
		// ¬ыполн€ем запрос к Ѕƒ
	$result = $dbh->query($query);
	
	// предварительна€ обработка
	while ($row = $dbh->fetch_array($result)) 
	{
		$replace = array( 
			'"' => '',
			"'" => "",
			':' => '',
			'+' => '_',
			'-' => '',
			'Ђ' => '',
			'ї' => '',
			'/' => '',
			'?' => '',
			'#' => '',
			'$' => '',
			'%' => '',
			'Ц' => '-',
			',' => '_',
			'(' => '', 
			')' => '' 
		);
		//$new_name = str_replace_assoc($replace,$row['name']);
		$row['name'] = iconv('UTF-8','cp1251',$row['name']); 		
		$new_name = preg_replace('/(["?:^~|@є$Ц=+*&%,;<>()ЧЂї#\/]+)/', '', $row['name']);
		$new_name = preg_replace('/\s\s+/', '_', $new_name);
		$new_name = preg_replace('/\_\_+/', '_', $new_name);
		$new_name = rtrim ($new_name,"...");
		//$new_name = iconv('cp1251','UTF-8',$new_name);
		echo $new_name."<br/>";
	}
	
	
	?>