<div class = "activities_stream">

	<div class = "activities_feed">

		<a href="#"><img src="html/ico/rss.png" title="RSS Feed of Activity" border="0"></a>

	</div>

	<?php if (empty($activities))
	{echo "<p class='end'>There has been no activity in the last sixty days.  If you have just installed ClinicCases 7, it may take a while for this to start filling up.</p>"; die;}

		foreach ($activities as $activity) {
		echo "<p><img src='" . $activity['thumb'] . "'>" . $activity['by'] . $activity['action_text'] . "<a href='" .  $activity['follow_url']."'>" . $activity['casename'] . "</a> on " . $activity['time_formatted'] . "</p><p class = 'grey'>" . $activity['what'] ."</p><hr>";
	}
	?>

	<p class="end">End of activities from the last sixty days</p>

</div>