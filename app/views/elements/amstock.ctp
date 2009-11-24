<div id="chart-holder">
	<strong>Please update to the latest version of Adobe Flash Player</strong>
</div>
<script type="text/javascript">
	// <![CDATA[
	var so = new SWFObject("<?php echo $html->url('/amcharts/amstock.swf');?>", "quota", '100%', '500', "8");
	so.addVariable("chart_id", "quota");
	so.addVariable("settings_file", escape("<?php echo $html->url('/amcharts/stock_chart.xml');?>?<?php echo microtime(); ?>"));
	so.addVariable("additional_chart_settings", escape("<settings><data_sets><data_set did='0'><file_name><?php echo $html->url(array("controller" => "projects", "action" => "projectData", "$project_id", "ext" => "csv", "?" => microtime()));?></file_name></data_set></data_sets><charts><chart cid='0'><values><y_left><unit> <?php echo $project['Meta']['unit']['label']; ?></unit></y_left></values></chart><chart cid='1'><values><y_left><unit> <?php echo $project['Meta']['unit']['changelabel']; ?></unit></y_left></values></chart></charts></settings>"));
	so.addVariable("preloader_color", "#333333");
	so.write("chart-holder");
	// ]]>
</script>