<?php
ini_set("max_execution_time", "100000");

require '../configPDO.php';
$projectName = $_GET['projectName'];
$sentiment = $_GET['sentiment'];
$graph_type = "commit";
$graph_data = "graph_data_".$projectName.".json";

if (file_exists($graph_data)) {
    echo file_get_contents($graph_data);
} 
else { 
	if($graph_type=="commit"){
		$sql = "SELECT @n := @n + 1 AS idx, user_id, name from 
					(SELECT distinct c.author_id as user_id, p.full_name as name FROM ".$projectName.".inline_comments c, ".$projectName.".people p 
						where p.gerrit_id = c.author_id) tab, 
					(SELECT @n:=0) r order by user_id";

		$users = $db->query($sql)->fetchAll();
		
		$data = $db->query("SELECT rd.author comitter_id, ic.author_id commenter_id, ic.sentiment_newdict sentiment, count(*) num_interaction
							FROM ".$projectName.".inline_comments ic, ".$projectName.".request_detail rd
							WHERE ic.request_id = rd.request_id 
								AND rd.author != ic.author_id  
								#AND rd.author in (1000210,1000110) 
								#AND ic.author_id in (1000210,1000110)
							#GROUP BY ic.sentiment_newdict
						    GROUP BY rd.author, ic.author_id, ic.sentiment_newdict
							ORDER BY num_interaction DESC, rd.author ASC, ic.author_id ASC")->fetchAll();

		$json_nodes=array();
		$json_links=array();

		for($i=0; $i<count($users); $i++){
		//for($i=0; $i<10; $i++){
			$user_a = $users[$i]['idx']-1;
			$user_a_name = $users[$i]['name'];
			$data_links = array();
			for($j=($i+1); $j<count($users); $j++){
			//for($j=($i+1); $j<10; $j++){
				$user_b = $users[$j]['idx']-1;
				$user_b_name = $users[$i]['name'];
				if($user_a!=$user_b){

					if($sentiment=='false'){
						$sentiment='true';					
					}

					if($sentiment=='true'){
						$positive = $db->query("	SELECT * FROM ".$projectName.".sentiment_interaction
													WHERE comitter_id in (".$users[$i]['user_id'].",".$users[$j]['user_id'].")
														AND commenter_id in (".$users[$i]['user_id'].",".$users[$j]['user_id'].")
														AND sentiment='positive'
													")->fetchAll();

	 					$negative = $db->query("	SELECT * FROM ".$projectName.".sentiment_interaction
													WHERE comitter_id in (".$users[$i]['user_id'].",".$users[$j]['user_id'].")
														AND commenter_id in (".$users[$i]['user_id'].",".$users[$j]['user_id'].")
														AND sentiment='negative'
													")->fetchAll();

						$weightPos = 0;
						if(count($positive) > 0)
							$weightPos += $positive[0]['num_interaction'];
						if(count($positive) > 1)
							$weightPos += $positive[1]['num_interaction'];
	 
						$weightNeg = 0;
						if(count($negative) > 0)
							$weightNeg += $negative[0]['num_interaction'];
						if(count($negative) > 1)
							$weightNeg += $negative[1]['num_interaction'];

						$weight = $weightPos-$weightNeg ;
						//print_r($positive);
						//echo count($positive).'-'.count($negative).'-'.$weightPos.'-'.$weightNeg.'-'.$users[$i]['user_id'].'-'.$users[$j]['user_id'].'-'.'<br>';

						if($weightPos!=0 || $weightNeg!=0 ){
							array_push($data_links, (int)$user_b);
							array_push($json_links, array("source" => (int)$user_a, "target" => (int)$user_b, "weight"=>(int)$weight, "left"=> false, "right"=>true));
						}
					}
				}
			}

			array_push($json_nodes,
				array(	"index" => (int)$user_a,
						"links" => $data_links,
						"score" => 8,
						"level" => 1,
						"title" => $user_a_name,
						"label" => $user_a_name,
						"id" => (int)$user_a
					 )
			);
		}

		echo json_encode(array("nodes"=>$json_nodes, "links"=>$json_links));
	}
}

function searchArray($comitter_id, $commenter_id, $sentiment, $array) {
	foreach ($array as $key => $val) {
		//echo $val['comitter_id'].' '.$val['commenter_id'].'<br>';
		if ( $val['comitter_id'] == $comitter_id && 
				$val['commenter_id'] == $commenter_id && 
				$val['sentiment'] == $sentiment) {
		   	return $key;
			break;
		}
	}
   return null;
}

	/*
	$json_nodes=array();
	foreach ($query as $data) {
		$data_links = array();
		$query_links = $db->query("SELECT * FROM edges_isern where source='".$data['id_citation']."'")->fetchAll();
		foreach ($query_links as $links)
			array_push($data_links, (int)$links['destination']-1);

		array_push($json_nodes,
					array(	"index" => (int)$data['id_citation']-1,
							"links" => $data_links,
							"score" => 8,
							"level" => 1,
							"title" => $data['title'],
							"year" => $data['year'],
							"label" => $data['title']."(".$data['year'].")",
							"author" => $data['author'],
							"ieee_id" => (int)$data['ieee_id'],
							"id" => (int)$data['ieee_id']
						 )
					);
	}

	$query = $db->query("SELECT * FROM edges_isern")->fetchAll();
	$json_links=array();
	foreach ($query as $data) {
		array_push($json_links, array("source" => (int)$data['source']-1, "target" => (int)$data['destination']-1, "weight"=>(float)$data['weight'], "left"=> false, "right"=>true));
	}
	echo json_encode(array("nodes"=>$json_nodes, "links"=>$json_links));*/


?>
