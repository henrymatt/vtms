<?php require_once("../includes/initialize.php"); ?>
<?php		
	$lesson_id = $db->escape_value($_GET['lesson']);
	$langSeries_id = $db->escape_value($_GET['langSeries']);
	$series_id = $db->escape_value($_GET['series']);
	
	if($_POST['edited_lesson']) {
		$lesson = Lesson::find_by_id($lesson_id);
		$lesson->title = $_POST['edited_lesson_title'];
		$lesson->trt = ($_POST['edited_lesson_trt_minutes'] * 60) + $_POST['edited_lesson_trt_seconds'];
		$lesson->checked_language = $_POST['edited_lesson_checked_language'];
		$lesson->checked_video = $_POST['edited_lesson_checked_video'];
		$lesson->files_moved = $_POST['edited_lesson_files_moved'];
		$lesson->is_detected = $_POST['edited_lesson_is_detected'];
		$lesson->publish_date = $_POST['edited_lesson_publish_date'];
		$lesson->qa_log = $_POST['edited_qa_log'];
		$lesson->qa_url = $_POST['edited_qa_url'];
		$lesson->update();
		$message = "Updated";
	}
	
	$lesson = Lesson::find_by_id($lesson_id);
	$trt = $lesson->trt;
	$trt_minutes = (int) ($trt/60);
	$trt_seconds = $trt%60;
	$tasks = Task::find_all_tasks_for_lesson($lesson_id);
	$assets = Task::find_all_assets_for_lesson($lesson_id);
	$shots = Shot::find_all_shots_for_lesson($lesson_id);
	$issues = Issue::get_unfinished_issues_for_lesson($lesson_id);
?>

<?php include_layout_template('header.php'); ?>
		<div>
		<h3><?php $lesson->display_full_lesson_navigation(); ?></h3>
		<h4><?php $lesson->display_lesson_topbar(); ?></h4>
		<?php echo $session->message(); ?>
		<p><a href="language-series.php?series=<?php echo $series_id; ?>&id=<?php echo $langSeries_id; ?>"><- Return to Lesson List</a></p>
		<h2>Assets and Tasks</h2>
		<table>
			<tr><th>Asset Name</th><th>Team Member</th><th>Actionable?</th><th>Completed?</th><th>Delivered?</th><th>Time Spent</th><?php if($session->is_admin()) echo "<th>Actions</th>";?></tr>
			<?php 
				foreach($assets as $task) {
					echo "<tr ";
						if ($task->is_completed ) {
							echo "class='completed'";
						} else if ($task->is_actionable) {
							echo "class='actionable'";
						} else if ($task->is_completed) {
							echo "class='completed'";
						}
						echo ">";
					echo "<td>{$task->task_name}</td>";
					echo "<td>{$task->team_member_name}</td>";
					echo "<td>". ($task->is_actionable ? 'yes' : 'no') ."</td>";
					echo "<td>". ($task->is_completed ? 'yes' : 'no')."</td>";
					echo "<td>". ($task->is_delivered ? 'yes' : 'no')."</td>";
					echo "<td>".seconds_to_timecode($task->time_actual, 6)."</td>";
					if ($session->is_admin()) {
						echo "<td><a href='edit-task.php?id=".$task->id."'>Edit</a></td>";
					}
					echo "</tr>"; 
					}
				?>
		</table>
		<br />
		<table>
			<tr><th>Task Name</th><th>Team Member</th><th>Actionable?</th><th>Completed?</th><th>Time Spent</th><?php if($session->is_admin()) echo "<th>Actions</th>";?></tr>
			<?php 
				foreach($tasks as $task) {
					echo "<tr ";
					if ($task->is_completed ) {
							echo "class='completed'";
						} else if ($task->actionable_at <= $lesson->comp_value) {
							echo "class='actionable'";
						}
						echo ">";
					echo "<td>{$task->task_name}</td>";
					echo "<td>{$task->team_member_name}</td>";
					echo "<td>".($task->actionable_at <= $lesson->comp_value ? 'yes' : 'no')."</td>";
					echo "<td>". ($task->is_completed ? 'yes' : 'no')."</td>";
					echo "<td>".seconds_to_timecode($task->time_actual, 6)."</td>";
					if ($session->is_admin()) {
						echo "<td><a href='edit-task.php?id=".$task->id."'>Edit</a></td>";
					}
					echo "</tr>"; 
					}
				?>
		</table>
		</div>
		<?php if($session->is_admin()) { ?>
		<div>
			<h3><?php $lesson->display_list_of_issues_with_link(); ?></h3>
			<form action='lesson.php?series=<?php echo $lesson->series_id; ?>&langSeries=<?php echo $lesson->language_series_id;  ?>&lesson=<?php echo $lesson->id; ?>' method='post'>
				<p>Log: <input type='text' size=60 name='edited_qa_log' value='<?php echo $lesson->qa_log; ?>'></p>
				<p>URL: <input type='text' size=60 name='edited_qa_url' value='<?php echo $lesson->qa_url; ?>'></p>
				<p><label for="edited_lesson_title">Title:</label> <input type="text" size="50" name="edited_lesson_title" value="<?php echo $lesson->title; ?>"></p>
				<p><label for="edited_lesson_publish_date">Publish Date:</label> <input type="text" size="50" name="edited_lesson_publish_date" value="<?php echo $lesson->publish_date; ?>"></p>
				<p><label for="edited_lesson_trt_minutes">TRT:</label> 
				<select name="edited_lesson_trt_minutes" id="edited_lesson_trt_minutes">
					<?php for($i=0; $i<20; $i++) {
						echo "<option value='{$i}'";
						if ($i == $trt_minutes) {
							echo " selected";
						}
						echo ">{$i}</option>";
					} ?>
				</select>
				<select name="edited_lesson_trt_seconds" id="edited_lesson_trt_seconds">
					<?php for($i=0; $i<60; $i++) {
						echo "<option value='{$i}'";
						if ($i == $trt_seconds) {
							echo " selected";
						}
						echo ">{$i}</option>";
					} ?>
				</select></p>
				Language Checked<input type="checkbox" name="edited_lesson_checked_language" value="1" <?php echo $lesson->checked_language ? "checked" : ""; ?>><br />
				Video Checked<input type="checkbox" name="edited_lesson_checked_video" value="1" <?php echo $lesson->checked_video ? "checked" : ""; ?>><br />
				Files Moved<input type="checkbox" name="edited_lesson_files_moved" value="1" <?php echo $lesson->files_moved ? "checked" : ""; ?>><br />
				Lesson Detected<input type="checkbox" name="edited_lesson_is_detected" value="1" <?php echo $lesson->is_detected ? "checked" : "" ?>>
				<input type="hidden" name="edited_lesson_id" value="<?php echo $current_record->id; ?>">
				<p><input type="submit" name="edited_lesson" id="edited_lesson" value="Edit"></p>
			</form>
		</div>
		
		<?php } ?> <!-- End if(is_admin) -->
		<div id="script">
		<h2>Script Preview</h2>
		<?php if($shots) { ?>
		<p><a href="lesson-script.php?id=<?php echo $lesson->id; ?>">Go to full script page</a></p>
		<table class="script">
			<th>Section</th><th>Shot</th><th>Script</th><th>Script English</th>
			<?php 
				foreach($shots as $shot) {
					echo "<tr>";
					echo "<td>{$shot->section}</td>";
					echo "<td>{$shot->shot} - {$shot->type}</td>";
					echo "<td>";
					echo nl2br($shot->script);
					echo "</td>";
					echo "<td>";
					echo nl2br($shot->script_english);
					echo "</td>";
					echo "</tr>";
				}
				?>
		</table>
		</div>
		<?php } else { ?> <!-- End of if($shots) -->
			<table class="script">
				<tr><td><a href="lesson-script.php?id=<?php echo $lesson->id; ?>">No script. Click to edit.</a></td></tr>
			</table>
		<?php } ?>
		<p><a href="language-series.php?series=<?php echo $series_id; ?>&id=<?php echo $langSeries_id; ?>"><- Return to Language Series List</a></p>

<?php include_layout_template('footer.php'); ?>