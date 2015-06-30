function D3notok() {
	document.getElementById('sidepanel').style.visibility = 'hidden';
	var nocontent = document.getElementById('nocontent');
	nocontent.style.visibility = 'visible';
	nocontent.style.pointerEvents = 'all';
	var t = document.getElementsByTagName('body');
	var body = document.getElementsByTagName('body')[0];
	body.style.backgroundImage = "url('citation-network-screenshot-d.png')";
	body.style.backgroundRepeat = "no-repeat";
}

// -------------------------------------------------------------------
// A number of forward declarations. These variables need to be defined since
// they are attached to static code in HTML. But we cannot define them yet
// since they need D3.js stuff. So we put placeholders.

// Highlight a citation in the graph. It is a closure within the d3.json() call.
var selectGithubProject = undefined;

// Change status of a panel from visible to hidden or viceversa
var toggleDiv = undefined;

// Clear all help boxes and select a citation in network and in citation details panel
var clearAndSelect = undefined;

// The call to set a zoom value -- currently unused
// (zoom is set via standard mouse-based zooming)
var zoomCall = undefined;

// To view graph for given project_id 
var project_id;

// To view graph for given projectName
var projectName;

// To view graph with sentiment analysis or no
var sentiment;

// -------------------------------------------------------------------

// Do the stuff -- to be called after D3.js has loaded
function D3ok(_projectName, _sentiment) {

	projectName = _projectName;
	sentiment = _sentiment;
	
	d3.select("#network_loader").attr('style', 'visibility:visible;');

	// Some constants
	//var WIDTH = window.screen.availWidth,
	//HEIGHT = window.screen.availHeight,
	var WIDTH = 1800,
	HEIGHT = 1800,
	SHOW_THRESHOLD = 2.5;

	// Variables keeping graph state
	var activeGithubProject = undefined;
	var currentOffset = {
		x : 0,
		y : 0
	};
	var currentZoom = 1.0;

	// The D3.js scales
	var xScale = d3.scale.linear()
		.domain([0, WIDTH])
		.range([0, WIDTH]);
	var yScale = d3.scale.linear()
		.domain([0, HEIGHT])
		.range([0, HEIGHT]);
	var zoomScale = d3.scale.linear()
		.domain([1, 6])
		.range([1, 6])
		.clamp(true);

	/* .......................................................................... */

	// The D3.js force-directed layout
	var force = d3.layout.force()
		.charge(-400)
		.size([WIDTH, HEIGHT])
		.linkStrength(function (d, idx) {
			return (Math.abs(d.weight)/10);
		})
		.linkDistance(100);

	// Add to the page the SVG element that will contain the citation network
	var svg = d3.select("#githubNetwork").append("svg:svg")
		.attr('xmlns', 'http://www.w3.org/2000/svg')
		.attr("width", WIDTH)
		.attr("height", HEIGHT)
		.attr("id", "graph")
		.attr("viewBox", "0 0 " + WIDTH + " " + HEIGHT)
		.attr("preserveAspectRatio", "xMidYMid meet");

	var boxContainer = svg.append('svg:rect')
		.attr('width', WIDTH)
		.attr('height', HEIGHT)
		.attr('fill', 'rgba(1,1,1,0)')
 
		// GithubProject panel: the div into which the citation details info will be written
		citationInfoDiv = d3.select("#citationInfo");

	/* ....................................................................... */

	// Get the current size & offset of the browser's viewport window
	function getViewportSize(w) {
		var w = w || window;
		if (w.innerWidth != null)
			return {
				w : w.innerWidth,
				h : w.innerHeight,
				x : w.pageXOffset,
				y : w.pageYOffset
			};
		var d = w.document;
		if (document.compatMode == "CSS1Compat")
			return {
				w : d.documentElement.clientWidth,
				h : d.documentElement.clientHeight,
				x : d.documentElement.scrollLeft,
				y : d.documentElement.scrollTop
			};
		else
			return {
				w : d.body.clientWidth,
				h : d.body.clientHeight,
				x : d.body.scrollLeft,
				y : d.body.scrollTop
			};
	}

	function getQStringParameterByName(name) {
		var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
		return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
	}

	/* Change status of a panel from visible to hidden or viceversa
	id: identifier of the div to change
	status: 'on' or 'off'. If not specified, the panel will toggle status
	 */
	toggleDiv = function (id, status) {
		d = d3.select('div#' + id);
		if (status === undefined)
			status = d.attr('class') == 'panel_on' ? 'off' : 'on';
		d.attr('class', 'panel_' + status);
		return false;
	}

	/* Clear all help boxes and select a citation in the network and in the
	citation details panel
	 */
	clearAndSelect = function (id) {
		toggleDiv('faq', 'off');
		toggleDiv('help', 'off');
		selectGithubProject(id, true); // we use here the selectGithubProject() closure
	}

	/* Compose the content for the panel with citation details.
	Parameters: the node data, and the array containing all nodes
	 */
	function getGithubProjectInfo(n, nodeArray) {
		info = '<div id="cover">';
		if (n.title)
			info += '<br><br><div class=t style="float: right; text-transform:capitalize;">' + n.title + '</div>';

		info += '<br><br><br><br><div><center><img style="width: 150px; height: 150px;" src="img/authors/no_image.jpg" title="' + n.label + '"/></center></div>';
		info +=
		'<img src="img/close.png" class="action" style="top: 5px;" title="close panel" onClick="toggleDiv(\'citationInfo\');"/>' +
		'<img src="img/target-center.png" class="action" style="top: 297px;" title="center graph on citation" onclick="selectGithubProject(' + n.index + ',true);"/>';

		info += '<br/><br/></div><div style="clear: both;"><button class="btn btn-success">Induced Graph</button> '
		info += '<a href="http://ieeexplore.ieee.org/xpl/articleDetails.jsp?tp=&arnumber=' + n.ieee_id + '" target="_blank"><button class="btn btn-warning">Full Paper</button></a><hr>'

		info += '</div><div style="clear: both;">Details:</hr>'
		if (n.author)
			info += '<div class=f><span class=l>Authors</span>: <span class=g>' + n.author + '</span></div>';
		if (n.year)
			info += '<div class=f><span class=l>Year</span>: <span class=d>' + n.year + '</span></div>';
		if (n.links) {
			info += '<div class=f><span class=l>Cited By</span>: ';
			n.links.forEach(function (idx) {
				info += '[<a href="javascript:void(0);" onclick="selectGithubProject(' + idx + ',true);">' + nodeArray[idx].label + '</a>]'
			});
			info += '</div>';
		}
		return info;
	}

	// ************************************************************************* 
	
	var json_url = 'graph_generator.php?projectName='+projectName+'&sentiment='+sentiment;
	d3.json( json_url, //'backend/data.json',
		function (data) {

			// Declare the variables pointing to the node & link arrays
			var nodeArray = data.nodes;
			var linkArray = data.links;

			minLinkWeight =
				Math.min.apply(null, linkArray.map(function (n) {
						return n.weight;
					}));
			maxLinkWeight =
				Math.max.apply(null, linkArray.map(function (n) {
						return n.weight;
					}));

			// Add the node & link arrays to the layout, and start it
			force
			.nodes(nodeArray)
			.links(linkArray)
			.start();

			/* --------------------------------------------------------------------- */
			/* Perform node drag
			 */
			var drag_node = d3.behavior.drag()
				.on("dragstart", dragNodeStart)
				.on("drag", dragNodeMove)
				.on("dragend", drageNodeEnd);

			function dragNodeStart(d, i) {
				//d3.event.sourceEvent.stopPropagation();
				force.stop() // stops the force auto positioning before you start dragging
			}

			function dragNodeMove(d, i) {
				d.px += d3.event.dx;
				d.py += d3.event.dy;
				d.x += d3.event.dx;
				d.y += d3.event.dy;
				repositionGraph(undefined, undefined, 'tick'); // this is the key to make it work together with updating both px,py,x,y on d !
			}

			function drageNodeEnd(d, i) {
				d.fixed = true; // of course set the node to fixed so the force doesn't include the node in its auto positioning stuff
				repositionGraph(undefined, undefined, 'tick');
				force.resume();
			}

			// A couple of scales for node radius & edge width
			var node_size = d3.scale.linear()
				.domain([5, 10]) // we know score is in this domain
				.range([1, 16])
				.clamp(true);
			var edge_width = d3.scale.pow().exponent(8)
				.domain([minLinkWeight, maxLinkWeight])
				.range([1, 3])
				.clamp(true);

			// ------- Create the elements of the layout (links and nodes) ------

			var networkGraph = svg.append('svg:g').attr('class', 'grpParent');

			// links: simple lines
			var graphLinks = networkGraph.append('svg:g').attr('class', 'grp gLinks')
				.selectAll("line")
				.data(linkArray, function (d) {
					return d.source.index + '-' + d.target.index;
				})
				.enter().append("line")
				//.style('stroke-width', function(d) { return edge_width(d.weight);} )
				.attr("class", "link");
				//.attr("marker-end", "url(#arrow)");

			var graphLinksLabel = networkGraph.append('svg:g').attr('class', 'grp gLinksLabel')
				.selectAll("line")
				.data(linkArray, function (d) {
					return 'lbl_' + d.source.index + '-' + d.target.index;
				})
				.enter().append("svg:text")
				.attr("text-anchor", "middle")
				.attr("startoffset", "50%")
				.text(function (d) {
					return d.weight;
				});

			// nodes: an SVG circle
			var graphNodes = networkGraph.append('svg:g').attr('class', 'grp gNodes')
				.selectAll("circle")
				.data(nodeArray, function (d) {
					return d.label
				})
				.enter().append("svg:circle")
				.attr('id', function (d) {
					return "c" + d.index;
				})
				.attr('class', function (d) {
					return 'node level' + d.level;
				})
				.attr('r', function (d) {
					return node_size(d.score);
				})
				.attr('pointer-events', 'all')
				.call(drag_node)
				//.on("click", function(d) { highlightGraphNode(d,true,this); } )
				.on("click", function (d) {
					//if (d3.event.defaultPrevented) return; 
					//showGithubProjectPanel(d);
				})
				.on("mouseover", function (d) {
					highlightGraphNode(d, true, this);
				})
				.on("mouseout", function (d) {
					highlightGraphNode(d, false, this);
				});

			// labels: a group with two SVG text: a title and a shadow (as background)
			var graphLabels = networkGraph.append('svg:g').attr('class', 'grp gLabel')
				.selectAll("g.label")
				.data(nodeArray, function (d) {
					return d.label
				})
				.enter().append("svg:g")
				.attr('id', function (d) {
					return "l" + d.index;
				})
				.attr('class', 'label');

			labels = graphLabels.append('svg:text')
				.attr('x', '-2em')
				.attr('y', '-.3em')
				.attr('pointer-events', 'none') // they go to the circle beneath
				.attr('id', function (d) {
					return "lf" + d.index;
				})
				.attr('class', 'nlabel')
				.text(function (d) {
					return d.label;
				});

			/* --------------------------------------------------------------------- */
			/* Select/unselect a node in the network graph.
			Parameters are:
			- node: data for the node to be changed,
			- on: true/false to show/hide the node
			 */
			function highlightGraphNode(node, on) {
				//if( d3.event.shiftKey ) on = false; // for debugging

				// If we are to activate a citation, and there's already one active,
				// first switch that one off
				if (on && activeGithubProject !== undefined) {
					highlightGraphNode(nodeArray[activeGithubProject], false);
				}

				// locate the SVG nodes: circle & label group
				circle = d3.select('#c' + node.index);
				label = d3.select('#l' + node.index);

				// activate/deactivate the node itself
				circle
				.classed('main', on);
				label
				.classed('on', on || currentZoom >= SHOW_THRESHOLD);
				label.selectAll('text')
				.classed('main', on);

				// activate all siblings
				Object(node.links).forEach(function (id) {
					d3.select("#c" + id).classed('sibling', on);
					label = d3.select('#l' + id);
					label.classed('on', on || currentZoom >= SHOW_THRESHOLD);
					label.selectAll('text.nlabel')
					.classed('sibling', on);
				});

				// set the value for the current active citation
				activeGithubProject = on ? node.index : undefined;
			}

			/* --------------------------------------------------------------------- */
			/* Show the details panel for a citation AND highlight its node in
			the graph. Also called from outside the d3.json context.
			Parameters:
			- new_idx: index of the citation to show
			- doMoveTo: boolean to indicate if the graph should be centered
			on the citation
			 */
			selectGithubProject = function (new_idx, doMoveTo) {

				// do we want to center the graph on the node?
				doMoveTo = doMoveTo || false;
				if (doMoveTo) {
					s = getViewportSize();
					width = s.w < WIDTH ? s.w : WIDTH;
					height = s.h < HEIGHT ? s.h : HEIGHT;
					offset = {
						x : s.x + width / 2 - nodeArray[new_idx].x * currentZoom,
						y : s.y + height / 2 - nodeArray[new_idx].y * currentZoom
					};
					repositionGraph(offset, undefined, 'move');
				}
				// Now highlight the graph node and show its citation panel
				highlightGraphNode(nodeArray[new_idx], true);
				showGithubProjectPanel(nodeArray[new_idx]);
			}

			/* --------------------------------------------------------------------- */
			/* Show the citation details panel for a given node
			 */
			function showGithubProjectPanel(node) {
				// Fill it and display the panel
				citationInfoDiv
				.html(getGithubProjectInfo(node, nodeArray))
				.attr("class", "panel_on");
			}

			/* --------------------------------------------------------------------- */
			/* Move all graph elements to its new positions. Triggered:
			- on node repositioning (as result of a force-directed iteration)
			- on translations (user is panning)
			- on zoom changes (user is zooming)
			- on explicit node highlight (user clicks in a citation panel link)
			Set also the values keeping track of current offset & zoom values
			 */
			function repositionGraph(off, z, mode) {

				// do we want to do a transition?
				var doTr = (mode == 'move');

				// drag: translate to new offset
				if (off !== undefined &&
					(off.x != currentOffset.x || off.y != currentOffset.y)) {
					g = d3.select('g.grpParent')
						if (doTr)
							g = g.transition().duration(500);
						g.attr("transform", function (d) {
							return "translate(" +
							off.x + "," + off.y + ")"
						});
					currentOffset.x = off.x;
					currentOffset.y = off.y;
				}

				// zoom: get new value of zoom
				if (z === undefined) {
					if (mode != 'tick')
						return; // no zoom, no tick, we don't need to go further
					z = currentZoom;
				} else
					currentZoom = z;

				// move edges
				e = doTr ? graphLinks.transition().duration(500) : graphLinks;
				e
				.attr("x1", function (d) {
					return z * (d.source.x);
				})
				.attr("y1", function (d) {
					return z * (d.source.y);
				})
				.attr("x2", function (d) {
					return z * (d.target.x);
				})
				.attr("y2", function (d) {
					return z * (d.target.y);
				});

				// move edges label
				ll = doTr ? graphLinksLabel.transition().duration(500) : graphLinksLabel;
				ll
				.attr("transform", function (d) {
					return "translate(" + z * (d.source.x + d.target.x) / 2 + "," + z * (d.source.y + d.target.y) / 2 + ")";
				});

				// move nodes
				n = doTr ? graphNodes.transition().duration(500) : graphNodes;
				n
				.attr("transform", function (d) {
					return "translate(" + z * d.x + "," + z * d.y + ")"
				});
				// move labels
				l = doTr ? graphLabels.transition().duration(500) : graphLabels;
				l
				.attr("transform", function (d) {
					return "translate(" + z * d.x + "," + z * d.y + ")"
				});
			}

			/* --------------------------------------------------------------------- */
			/* Perform Container drag
			 */
			boxContainer.call(d3.behavior.drag()
				.on("drag", dragCanvasMove));

			function dragCanvasMove(d) {
				offset = {
					x : currentOffset.x + d3.event.dx,
					y : currentOffset.y + d3.event.dy
				};
				repositionGraph(offset, undefined, 'drag');
			}

			/* --------------------------------------------------------------------- */
			/* Perform zoom. We do "semantic zoom", not geometric zoom
			 * (i.e. nodes do not change size, but get spread out or stretched
			 * together as zoom changes)
			 */
			boxContainer.call(d3.behavior.zoom()
				.x(xScale)
				.y(yScale)
				.scaleExtent([1, 6])
				.on("zoom", doZoom));

			function doZoom(increment) {
				newZoom = increment === undefined ? d3.event.scale : zoomScale(currentZoom + increment);
				if (currentZoom == newZoom)
					return; // no zoom change

				// See if we cross the 'show' threshold in either direction
				if (currentZoom < SHOW_THRESHOLD && newZoom >= SHOW_THRESHOLD)
					svg.selectAll("g.label").classed('on', true);
				else if (currentZoom >= SHOW_THRESHOLD && newZoom < SHOW_THRESHOLD)
					svg.selectAll("g.label").classed('on', false);

				// See what is the current graph window size
				s = getViewportSize();
				width = s.w < WIDTH ? s.w : WIDTH;
				height = s.h < HEIGHT ? s.h : HEIGHT;

				// Compute the new offset, so that the graph center does not move
				zoomRatio = newZoom / currentZoom;
				newOffset = {
					x : currentOffset.x * zoomRatio + width / 2 * (1 - zoomRatio),
					y : currentOffset.y * zoomRatio + height / 2 * (1 - zoomRatio)
				};

				// Reposition the graph
				repositionGraph(newOffset, newZoom, "zoom");
			}

			zoomCall = doZoom; // unused, so far

			/* --------------------------------------------------------------------- */

			/* process events from the force-directed graph */
			force.on("tick", function () {
				repositionGraph(undefined, undefined, 'tick');
			});

			/* A small hack to start the graph with a citation pre-selected */
			mid = getQStringParameterByName('id')
				if (mid != null)
					clearAndSelect(mid);
			

	  
			svg.on("contextmenu", function (d) {   
				tipNewNode.show();
				d3.event.preventDefault();
			});
			
			d3.select("#network_loader").attr('style', 'visibility:hidden;');
		}
	);

} // end of D3ok()
