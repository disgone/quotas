<div id="chart-holder2">
	<strong>Please update to the latest version of Adobe Flash Player</strong>
</div>
<script type="text/javascript">
	// <![CDATA[
	var so = new SWFObject("/quotas/amcharts/amstock.swf", "amline", '960', '500', "8", "#FFFFFF");
	so.addVariable("settings_file", escape("/quotas/amcharts/stock_chart.xml?<?php echo microtime(); ?>"));
	so.addVariable("additional_chart_settings", escape("<settings><data_sets><data_set did='0'><file_name>/quotas/projects/projectData/<?php echo $project_id; ?>.csv</file_name></data_set></data_sets><charts><chart cid='0'><values><y_left><unit> <?php echo $project['Meta']['unit']['label']; ?></unit></y_left></values></chart><chart cid='1'><values><y_left><unit> <?php echo $project['Meta']['unit']['label']; ?></unit></y_left></values></chart></charts></settings>"));
	so.addVariable("preloader_color", "#FFFFFF");
	so.write("chart-holder2");
	// ]]>
</script>