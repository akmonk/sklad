<?php
	require_once "database.php";
	
	function get_shares_count_by_name($dbh, $name)
	{
		$result = $dbh->query("SELECT `id` FROM `shares` WHERE `shares_id`=0 AND `name` like '{$name}' ORDER by name");
		$count = $dbh->num_rows($result);
		if ($count==1)
		{
			//$id_arr = $dbh->fetch_array($result);
			//return $id_arr['id'];
			return 1;
		}
		elseif($count>1)
		{
			echo "Больше 1, {$name} <br/>";
			return -1;
		}
		else
		{
			return -1;
		}
	}
	
	function get_conversations_by_name($dbh, $name)
	{
		$result = $dbh->query("SELECT `id` FROM `shares` WHERE `conversations_id`=0 AND `name` like '{$name}' ORDER by name");
		$id_arr = $dbh->fetch_array($result);
		return $id_arr['shares_id'];
	}
	
	function str_replace_assoc(array $replace, $subject) 
	{ 
		return str_replace(array_keys($replace), array_values($replace), $subject);    
	}
	
	$dbh=new CDataBase("skladchik", "localhost", "ypbase", "golosneba"); 

	$table = 'shares';
	
	$query = "SELECT * FROM {$table} WHERE 1 ORDER by name";
		// Выполняем запрос к БД
	$result = $dbh->query($query);
	
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
	$i = 0;
	// предварительная обработка
	while ($row3 = $dbh->fetch_array($result3)) 
	{
		$count = get_shares_count_by_name($dbh,$row3['name']);
		if ($count == "-1") 
		{
			echo "Ошибка! ".$row3['id']." ".$row3['name'];var_dump($count);echo"<br/>" ;
			$i++;
		}
		else
		{
			$shares_id = get_conversations_by_name($dbh,$row3['name']);
			$sql = "UPDATE `shares` SET `shares_id` = \"{$shares_id}\" WHERE `id`={$row3['id']};";
			echo $sql."<br/>";
			$result = $dbh->query($sql);
		}
		//echo"<pre>";var_dump($count);echo"</pre";
		
	}
	echo "all: ".$i;
	?>