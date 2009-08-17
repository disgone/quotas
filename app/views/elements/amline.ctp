<div id="chart-holder">
	<strong>Please update to the latest version of Adobe Flash Player</strong>
</div>
<script type="text/javascript">
	// <![CDATA[
	var so = new SWFObject("/quotas/amcharts/amline.swf", "amline", '960', '400', "8", "#FFFFFF");
	so.addVariable("path", "/quotas/amcharts/");
	so.addVariable("settings_file", escape("/quotas/amcharts/line_chart.xml?<?php echo microtime(); ?>"));
	so.addVariable("data_file", "/quotas/projects/projectData/<?php echo $project_id; ?>.xml");
	so.addVariable("preloader_color", "#FFFFFF");
	so.write("chart-holder");
	// ]]>
</script>