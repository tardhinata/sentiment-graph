<?php
require '../configPDO.php';
?>

<table class="table table-condensed table-bordered table-hover" cellpadding="0" cellspacing="0">
<thead>
	<tr>
		<th style="width:20px">#</th>
		<th style="width:120px">Name</th>
		<th style="width:100px">Action</th>
	</tr>
</thead>
<tbody>
<?php
	$i = 1;
	//$query = $db->query("show databases")->fetchAll();

	$query = array("gerrit_ovirt", "gerrit_chromium", "gerrit_typo3");
	foreach ($query as $data) {
    if (strpos($data,'gerrit') !== false) {
?>
		<tr>
			<td><?php echo $i ?></td>
			<td><?php echo $data ?></td>
			<td>
				<input type="checkbox" checked id="sentiment_<?php echo $data['Database']; ?>" value="Bike"> sentiment
				<a href="#" onClick="showGraph('<?php echo $data ?>')" class="edit" data-toggle="modal">
					<i class="icon-refresh"> Show Graph </i>
				</a> | | 
				<a href="#" onClick="showGraphFullScreen('<?php echo $data ?>')" class="edit" data-toggle="modal">
					<i class="icon-plus"> Full Screen Graph</i>
				</a>
			</td>
		</tr>
<?php
		$i++;
    }
	}
?>

<script>

function showGraph(projectName){
	sentiment = $("#sentiment_"+projectName).is(':checked')
	$("#project_name").html("<h5> "+projectName+"</h5>");
	var url = "graph/"+_GRAPH_TYPE+".php?projectName="+projectName+"&sentiment="+sentiment;
	loadContent('graph-view-gerritDeveloper', url);
}

function showGraphFullScreen(projectName){ 
  var fullscreenUrl = "http://sentimentgraph.ardhinata.com/graph/gerrit_fullscreen.php?projectName="+projectName+"&sentiment=true";
  window.open(fullscreenUrl);
}

</script>

</tbody>
</table>
