	  var container = document.getElementById('mygraph');
	  var url = document.getElementById('url');
	  var graph = new vis.Graph(container);
	  $("#loader").hide();

	  function loadData () {
		$("#loader").show();
	    $.ajax({
	      type: "GET",
	      url: url.value
	    }).done(function(data) {
	          $("#loader").hide();
	          graph.setOptions({
	            stabilize: true
	          });
	          graph.setData( {
	            dot: data
	          });
	        });
	  }
	  loadData();
