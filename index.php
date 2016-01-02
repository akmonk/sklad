<?php
	require_once "database.php";
	
	function get_shares_count_by_name($dbh, $name)
	{
		$result = $dbh->query("SELECT count(*) FROM `shares` WHERE `shares_id`=0 AND `name` like '{$name}' ORDER by name");
		return $dbh->fetch_array($result);
	}
	
	function str_replace_assoc(array $replace, $subject) 
	{ 
		return str_replace(array_keys($replace), array_values($replace), $subject);    
	}
	
	$dbh=new CDataBase("skladchik", "localhost", "ypbase", "golosneba"); 

	$table = 'shares';
	
	$query = "SELECT * FROM {$table} WHERE 1 ORDER by name";
		// Выполняем запрос к БД
	//$result = $dbh->query($query);
	
	// предварительная обработка
	while ($row = $dbh->fetch_array($result)) 
	{
		$replace = array( 
			'"' => '',
			"'" => "",
			':' => '',
			'+' => '_',
			'-' => '',
			'«' => '',
			'»' => '',
			'/' => '',
			'?' => '',
			'#' => '',
			'$' => '',
			'%' => '',
			'–' => '-',
			',' => '_',
			'(' => '', 
			')' => '' 
		);
		//$new_name = str_replace_assoc($replace,$row['name']);
		$row['name'] = iconv('UTF-8','cp1251',$row['name']);
		//$new_name = preg_replace('/([.+{},®!-]+)/', '_', $row['name']); 		
		$new_name = preg_replace('/([-.+{},®!-"?:^~|@№$–=*&%;<>()—«»#\/\']+)/', '_', $row['name']);
		$new_name = preg_replace('/\s\s+/', ' ', $new_name);		
		$new_name = preg_replace('/\ /', '_', $new_name);
		$new_name = preg_replace('/__+/', '_', $new_name);
		$new_name = rtrim ($new_name,"...");
		echo "<pre>";var_dump($row);"</pre>";
		$new_name = iconv('cp1251','UTF-8',$new_name);
		$sql2 = "UPDATE `shares` SET `name` = \"{$new_name}\" WHERE `id`={$row['id']};";
		echo $sql2."<br/>";
		$result2 = $dbh->query($sql2);
		
		echo $new_name."<br/>";
	}
	
	$query3 = "SELECT * FROM {$table} WHERE `conversations_id`=0 ORDER by name";
		// Выполняем запрос к БД
	$result3 = $dbh->query($query3);
	
	// предварительная обработка
	while ($row3 = $dbh->fetch_array($result3)) 
	{
		$count = get_shares_count_by_name($dbh,$row3['name']);
		if ($count != 1) 
		{
			echo "Ошибка! ".$row3['id']." ".$row3['name']."<br/>";
		}
		echo"<pre>";var_dump($count);echo"</pre";
		
	}
	
	?>