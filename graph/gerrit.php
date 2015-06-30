<?php
$projectName=$_GET['projectName'];
$sentiment=$_GET['sentiment'];
?>

<div>
<link rel="stylesheet" href="css/citation_network_new.css">
    <script>

      // Sniff MSIE version
      var ie = ( function() {
        var undef,
        v = 3,
        div = document.createElement('div'),
        all = div.getElementsByTagName('i');
        while (
         div.innerHTML='<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->',all[0]
        );
        return v > 4 ? v : undef;
      }() );

      function takeAction() {
        if( ie && ie < 9 ) {
			D3notok();
        } else {
			D3ok(<?php echo "'".$projectName."',".$sentiment; ?>);
       }
     }
    </script>

  <div id="nocontent">
    <h1>Sadly your browser is not compatible with this site</h1>

    <div>You can use <a href="http://www.google.com/chrome/">Google
    Chrome</a>, <a href="http://www.mozilla.org/firefox">Mozilla Firefox</a>
    or <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">Microsoft
    Internet Explorer (v9 or above)</a> to access the Sentiment Network</div>

  </div>
  <div>

	<br><br>
  </div>
  <div id="network_loader" style="visibility:hidden;"><img src="img/ajax-loader.gif" width="220" height="19" /></div>
  <div id="githubNetwork"></div>

  <div id="sidepanel"> 
    <div id="title">
      <br/>Sentiment in Code Review Graph<br/>
      <img id="helpIcon"
           src="img/help-question.png" title="Click for help"
           onClick="toggleDiv('help');"/> 
    </div>

    <div id="citationInfo" class="panel_off"></div>
	<svg>
	<defs>
		<marker id="arrow" viewbox="0 -5 10 10" refX="18" refY="0"
				markerWidth="10" markerHeight="10" orient="auto" >
			<path d="M0,-5L10,0L0,5Z">
		</marker>
    </defs>
	</svg>
</div>
</div>

<script src="js/d3js/d3.v3.js"></script>
<script src="js/d3tip/d3tip.js"></script>
<script src="js/github_network.js"></script>
<script>
takeAction(); 
</script>

</div>
