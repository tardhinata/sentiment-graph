<?php
require '../configPDO.php';
$projectName = $_GET['projectName'];
$sentiment = $_GET['sentiment'];
$graph_type = "commit";

if($graph_type=="commit"){
	$sql = "select @n := @n + 1 AS idx, user_id, name from (
						SELECT distinct p.user_id, u.name FROM github.commit_comments p, github.users u where  p.commit_id in
													(select id from github.commits where project_id=1 )
													and u.id = p.user_id
						) tab, (SELECT @n:=0) r order by user_id";

	$users = $db->query($sql)->fetchAll();

	$json_nodes=array();
	$json_links=array();

	for($i=0; $i<count($users); $i++){
		$user_a = $users[$i]['idx']-1;
		$user_a_name = $users[$i]['name'];
		$data_links = array();
		for($j=($i+1); $j<count($users); $j++){
			$user_b = $users[$j]['idx']-1;
			$user_b_name = $users[$i]['name'];
			if($user_a!=$user_b){

				if($sentiment=='false'){
					$weight = $db->query("	SELECT count(*) as sum FROM github.commit_comments
											where commit_id in  (select id from github.commits where project_id=1 )
											and  user_id = ".$users[$i]['user_id']."
											and commit_id in (
												SELECT commit_id FROM github.commit_comments
												where commit_id in  (select id from github.commits where project_id=1 )
												and  user_id =".$users[$j]['user_id'].")")->fetchAll();

					if($weight[0]['sum']>0){
						array_push($data_links, (int)$user_b);
						array_push($json_links, array("source" => (int)$user_a, "target" => (int)$user_b, "weight"=>(int)$weight[0]['sum'], "left"=> false, "right"=>false));
					}
				}

				if($sentiment=='true'){
					$positive = $db->query("	SELECT count(*) as sum FROM github.commit_comments
												where commit_id in  (select id from github.commits where project_id=1 )
												and sentiment='positive'
												and  user_id = ".$users[$i]['user_id']."
												and commit_id in (
													SELECT commit_id FROM github.commit_comments
													where commit_id in  (select id from github.commits where project_id=1)
													and sentiment='positive'
													and  user_id =".$users[$j]['user_id'].")")->fetchAll();

					$negative = $db->query("	SELECT count(*) as sum FROM github.commit_comments
												where commit_id in  (select id from github.commits where project_id=1)
												and sentiment='negative'
												and user_id = ".$users[$i]['user_id']."
												and commit_id in (
													SELECT commit_id FROM github.commit_comments
													where commit_id in  (select id from github.commits where project_id=1 )
													and sentiment='negative'
													and user_id =".$users[$j]['user_id'].")")->fetchAll();

					$neutral = $db->query("		SELECT count(*) as sum FROM github.commit_comments
												where commit_id in  (select id from github.commits where project_id=1 )
												and sentiment='neutral'
												and user_id = ".$users[$i]['user_id']."
												and commit_id in (
													SELECT commit_id FROM github.commit_comments
													where commit_id in  (select id from github.commits where project_id=1 )
													and sentiment='neutral'
													and user_id =".$users[$j]['user_id'].")")->fetchAll();

					if($positive[0]['sum']!=0 || $negative[0]['sum']!=0 || $neutral[0]['sum']!=0){
						$weight = ($positive[0]['sum'] - $negative[0]['sum']) + ($neutral[0]['sum'] / 2);
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
