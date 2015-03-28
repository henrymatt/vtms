<?php require_once("../includes/initialize.php"); ?>
<?php
	if (!$session->is_admin()) {
		$_SESSION['message'] = "You need admin privileges to access this page.";
		redirect_to('login.php');
	}
	if(isset($_POST['mark_lesson_as_checked'])) {
		$qa_lesson_id = $db->escape_value($_POST['qa_lesson_id']);
		$current_time = new DateTime(null, new DateTimeZone('UTC'));
		$lesson = Lesson::find_by_id($qa_lesson_id);
		$lesson->checked_video_time = $current_time->format('Y-m-d H:i:s');
		$lesson->checked_video = 1;
		$lesson->update();
		$_SESSION['message'] = "You've marked that video as approved.";
		redirect_to('admin-video-check.php');
	}
	
	if(isset($_POST['mark_lesson_as_moved'])) {
		$qa_lesson_id = $db->escape_value($_POST['qa_lesson_id']);
		$current_time = new DateTime(null, new DateTimeZone('UTC'));
		$lesson = Lesson::find_by_id($qa_lesson_id);
		$lesson->files_moved_time = $current_time->format('Y-m-d H:i:s');
		$lesson->files_moved = 1;
		$lesson->update();
		$_SESSION['message'] = "This lesson has been moved.";
		redirect_to('admin-video-check.php');
	}
	$lessons = Lesson::find_all_video_check_page_lessons($sort_by);
	
	$moveable_lessons = [];
	$language_checked_lessons = [];
	
	foreach($lessons as $lesson) {
  	$series = Series::find_by_id($lesson->series_id);
  	$lesson->comp_value = Lesson::get_comp_value($lesson->id);
  	$lesson->comp_value_total = Series::get_series_total_completion_value($lesson->series_id);
  	$lesson->all_tasks_complete = Lesson::all_tasks_complete($lesson->id);
  	
  	if($lesson->all_tasks_complete($lesson->id) && $lesson->pending_issues() == false && $lesson->exported_time > $lesson->queued_time) {
    	if($lesson->checked_video) {
      	$moveable_lessons[] = $lesson;
    	} else {
      	$language_checked_lessons[] = $lesson;
    	}
  	}
	}
	
?>

<?php include_layout_template('header.php'); ?>
	<div id="breadcrumbs" class="row">
		<ul class="breadcrumbs">
			<li><a href="operations.php">Operations</a></li>
			<li class="current"><a href="admin-video-check.php">Video Checking</a></li>
		</ul>
	</div>

    <div class="row">
  		<div class="small-12 columns">
  		  <h3>Video Checking</h3>
  		</div>
		</div>

    <?php
    if($moveable_lessons) { ?>
    <div id="upcoming-lessons" class="small-12 medium-6 medium-centered columns">
  		<h3 class="group-heading">Move These</h3>
      <ol class="group">
      <?php
      foreach($moveable_lessons as $lesson) : ?>
        <div class="group-item<?php if (strtotime($lesson->publish_date) < time()) { echo " overdue"; } ?>">
			    <div class="lesson-info">
    				<a class="lesson-title" href="lesson.php?id=<?php echo $lesson->id; ?>"><?php echo $lesson->display_full_lesson(); ?></a>
    				<p class="date"><?php echo "Due ".$lesson->publish_date; ?></p>
  				</div>
  				<ul class="actions">
  					<li class="action-item">
  						<form action='admin-video-check.php' method='post'>
  							<input type='hidden' name='qa_lesson_id' value='<?php echo $lesson->id; ?>'>
  							<button type="submit" class="no-format" name="mark_lesson_as_moved" data-tooltip class="has-tip" title="Move Files"><img src="img/icon-move-files.png"></button>
  						</form>
  					</li>
  				</ul>
    		</div>
      <?php endforeach; ?>
      </ol>
    </div>
    <?php } ?>
    
    <?php
    if($language_checked_lessons) { ?>
    <div id="upcoming-lessons" class="small-12 medium-6 medium-centered columns">
  		<h3 class="group-heading">Check These</h3>
      <ol class="group">
      <?php
      foreach($language_checked_lessons as $lesson) : ?>
        <div class="group-item<?php if (strtotime($lesson->publish_date) < time()) { echo " overdue"; } ?>">
			    <div class="lesson-info">
    				<a class="lesson-title" href="lesson.php?id=<?php echo $lesson->id; ?>"><?php echo $lesson->display_full_lesson(); ?></a>
    				<p class="date"><?php echo "Due ".$lesson->publish_date; ?></p>
  				</div>
  				<ul class="actions">
						<li class="action-item">
							<a class="item" href="issues-for-lesson.php?id=<?php echo $lesson->id; ?>" data-tooltip class="has-tip" title="Add Issue"><img src="img/icon-add-issue.png"></a>
						</li>
						<li class="action-item">
							<form action='admin-video-check.php' method='post'>
								<input type='hidden' name='qa_lesson_id' value='<?php echo $lesson->id; ?>'>
                <button type="submit" class="no-format" name="mark_lesson_as_checked" data-tooltip class="has-tip" title="Mark Lesson as Checked"><img src="img/icon-move-files.png"></button>
							</form>
						</li>
					</ul>	
    		</div>
      <?php endforeach; ?>
      </ol>
    </div>
    <?php } ?>

		
<?php include_layout_template('footer.php'); ?>