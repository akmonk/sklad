<?php
	require_once "database.php";
	
	function str_replace_assoc(array $replace, $subject) 
	{ 
		return str_replace(array_keys($replace), array_values($replace), $subject);    
	}
	
	$dbh=new CDataBase("skladchik", "localhost", "ypbase", "golosneba"); 

	$table = 'prepare_csv';
	
	$query = "SELECT * FROM {$table} WHERE 1 ORDER by name";
		// ��������� ������ � ��
	$result = $dbh->query($query);
	
	// ��������������� ���������
	while ($row = $dbh->fetch_array($result)) 
	{
		$replace = array( 
			'"' => '',
			"'" => "",
			':' => '',
			'+' => '_',
			'-' => '',
			'�' => '',
			'�' => '',
			'/' => '',
			'?' => '',
			'#' => '',
			'$' => '',
			'%' => '',
			'�' => '-',
			',' => '_',
			'(' => '', 
			')' => '' 
		);
		//$new_name = str_replace_assoc($replace,$row['name']);
		$row['name'] = iconv('UTF-8','cp1251',$row['name']);
		//$new_name = preg_replace('/([.+{},�!-]+)/', '_', $row['name']); 		
		$new_name = preg_replace('/([-.+{},�!-"?:^~|@�$�=*&%;<>()���#\/\']+)/', '_', $row['name']);
		$new_name = preg_replace('/\s\s+/', ' ', $new_name);		
		$new_name = preg_replace('/\ /', '_', $new_name);
		$new_name = preg_replace('/__+/', '_', $new_name);
		$new_name = rtrim ($new_name,"...");
		$sql2 = "UPDATE `shares` SET `name` = \"{$new_name}\" WHERE `id`={$row['id']};";
		echo $sql2."<br/>";
		$result2 = $dbh->query($sql2);
		//$new_name = iconv('cp1251','UTF-8',$new_name);
		echo $new_name."<br/>";
	}
	
	
	?>