	var posts_dataset_var = JSON.parse(wp_ulike_statistics.posts_dataset);
	var comments_dataset_var = JSON.parse(wp_ulike_statistics.comments_dataset);
	var activities_dataset_var = JSON.parse(wp_ulike_statistics.activities_dataset);
	var activities_dataset_sum = comments_dataset_sum = posts_dataset_sum = 0;
	
	//posts dataset
	if(posts_dataset_var  !== null){
	for (var i = 0; i < posts_dataset_var.length; i++) {
		posts_dataset_sum += parseInt(posts_dataset_var[i]);
	}
	var posts_date = {
		labels : JSON.parse(wp_ulike_statistics.posts_date_labels),
		datasets : [
			{
				label: "My First dataset",
				fillColor: "rgba(151,187,205,0.2)",
				strokeColor: "rgba(151,187,205,1)",
				pointColor: "rgba(151,187,205,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : posts_dataset_var
			}
		]
	}
	}
	
	//comments dataset
	if(comments_dataset_var  !== null){
	for (var i = 0; i < comments_dataset_var.length; i++) {
		comments_dataset_sum += parseInt(comments_dataset_var[i]);
	}
	var comments_date = {
		labels : JSON.parse(wp_ulike_statistics.comments_date_labels),
		datasets : [
			{
				label: "My First dataset",
				fillColor : "rgba(253,180,92,0.2)",
				strokeColor: "rgba(255,200,112,1)",
				pointColor : "rgba(230,126,34,1)",
				pointStrokeColor : "#f39c12",
				pointHighlightFill : "#f39c12",
				pointHighlightStroke : "rgba(211,84,0,1)",
				data : comments_dataset_var
			}
		]
	}
	}
	
	
	//activities dataset
	if(activities_dataset_var  !== null){
	for (var i = 0; i < activities_dataset_var.length; i++) {
		activities_dataset_sum += parseInt(activities_dataset_var[i]);
	}
	var activities_date = {
		labels : JSON.parse(wp_ulike_statistics.activities_date_labels),
		datasets : [
			{
				label: "My First dataset",
				fillColor: "rgba(231,79,64,0.2)",
				strokeColor: "rgba(192,57,43,1)",
				pointColor: "rgba(141,42,32,1)",
				pointStrokeColor : "#f7464a",
				pointHighlightFill : "#f7464a",
				pointHighlightStroke : "rgba(255,90,94,1)",
				data : activities_dataset_var
			}
		]
	}
	}
	
	var pieData = [
			{
				value: posts_dataset_sum,
				color:"#5cc6fd",
				highlight: "#7dd1fd",
				label: "Posts"
			},
			{
				value: comments_dataset_sum,
				color: "#FDB45C",
				highlight: "#FFC870",
				label: "Comment"
			},
			{
				value: activities_dataset_sum,
				color: "#F7464A",
				highlight: "#FF5A5E",
				label: "Activities"
			}
		];

	(function(){
		var chart1 		= document.getElementById('chart1');
		var chart2 		= document.getElementById('chart2');
		var chart3		= document.getElementById('chart3');
		var piechart 	= document.getElementById('piechart');
		
		if (chart1 != null) {
			if(posts_dataset_var  !== null){
				var ctx1 = chart1.getContext("2d");
				new Chart(ctx1).Line(posts_date, {
					responsive: true
				});
			}else{
				document.getElementById("posts_likes_stats").getElementsByClassName("main")[0].innerHTML = "No Data Found!";		
			}
		}
		
		if (chart2 != null) {
			if(comments_dataset_var  !== null){
				var ctx2 = chart2.getContext("2d");
				new Chart(ctx2).Line(comments_date, {
					responsive: true
				});
			}else{
				document.getElementById("comments_likes_stats").getElementsByClassName("main")[0].innerHTML = "No Data Found!";		
			}
		}
		
		if (chart3 != null) {
			if(activities_dataset_var  !== null){
				var ctx3 = chart3.getContext("2d");
				new Chart(ctx3).Line(activities_date, {
					responsive: true
				});
			}else{
				document.getElementById("activities_likes_stats").getElementsByClassName("main")[0].innerHTML = "No Data Found!";		
			}
		}
		
		if (piechart != null) {
			if(activities_dataset_var  !== null || comments_dataset_var  || null && posts_dataset_var  || null){
				var ctx4 = piechart.getContext("2d");
				new Chart(ctx4).Pie(pieData, {
					responsive: true
				});
			}else{
				document.getElementById("piechart_stats").getElementsByClassName("main")[0].innerHTML = "No Data Found!";		
			}
		}
		
	})();
	

	jQuery(document).on('ready', function($){
		postboxes.save_state = function(){
			return;
		};
		postboxes.save_order = function(){
			return;
		};
		postboxes.add_postbox_toggles();
	});