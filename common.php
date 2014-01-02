<?php

require_once 'libAllure/Database.php';  

use \libAllure\DatabaseFactory;      
use \libAllure\Database;

$db = new Database('mysql:host=localhost;dbname=solutionBuilder', 'root', '');

function getObjects($term) {  
	global $db;  
  
	$sql = '
SELECT 
	o.id, 
	o.title, 
	if(isnull(o.icon), "default.png", o.icon) AS icon, 
	GROUP_CONCAT(c.title) AS types, 
	GROUP_CONCAT(cp.title) AS provides   
FROM objects o 
LEFT JOIN object_types t ON 
	t.object = o.id 
LEFT JOIN classes c ON 
	t.class = c.id 
LEFT JOIN object_providers p ON
	o.id = p.object
LEFT JOIN classes cp ON
	p.class = cp.id
WHERE 
	o.title LIKE :term
	OR o.keywords LIKE :term
GROUP BY o.id 
';

	$stmt = $db->prepare($sql); 
	$stmt->bindValue(':term', '%' . $term . '%');
	$stmt->execute();
	

	return $stmt->fetchAll();
}  

?>
