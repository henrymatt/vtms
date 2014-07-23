<?php require_once("../includes/initialize.php"); ?>
<?php
	confirm_logged_in();
	$days_past = 1;
	$recent_tasks = Task::get_recently_completed_tasks($days_past, 25);
	$active_tasks = Task::get_all_active_tasks();
	$actionable_tasks = Task::get_all_actionable_tasks(25);
	$logged_in_user = User::find_by_id($session->user_id);
?>

<?php include_layout_template('header.php'); ?>
  
    <?php if($active_tasks) { ?>
      <div id="active-tasks" class="small-6 medium-10 medium-centered columns">
  		<h3 class="lesson-group-heading">Active Tasks</h3>
      <ol class="lesson-group">
      <?php
      foreach($active_tasks as $task) : ?>
        <div class="lesson">
          <div class="lesson-member">
    				<?php if($session->is_admin()) {
  				    echo "<a href='task-sheet.php?member={$task->team_member_id}'>{$task->team_member_name}</a>";  
  				  } else {
    				  echo $task->team_member_name;
  				  } ?>
  				</div>
  				<div class="lesson-info">
    				<p class="lesson-title"><?php echo $task->display_full_task_lesson(); ?></p>
    				<p class="task-title"><?php echo $task->task_name; ?></p>
    				<p class="lesson-due-date"><?php echo "Activated ".$logged_in_user->local_time($task->activated_time); ?></p>
  				</div>
  				<div class="lesson-actions">
  				  <a class="lesson-action-item" href="#"><img src="img/icon-add-issue.png"></a>
  					<a class="lesson-action-item" href="#"><img src="img/icon-add-issue.png"></a>
  				</ul>
    			</div>
    		</div>
      <?php endforeach; ?>
      </ol>
		</div>
		<?php } ?>
		
		<div id="recent-tasks" class="medium-6 small-12 columns">
  		<h3 class="lesson-group-heading">Recent Completed Tasks</h3>
      <?php
      if($recent_tasks) { ?>
      <ol class="lesson-group">
      <?php
      foreach($recent_tasks as $task) : ?>
        <div class="lesson">
          <div class="lesson-member">
    				<?php if($session->is_admin()) {
  				    echo "<a href='task-sheet.php?member={$task->team_member_id}'>{$task->team_member_name}</a>";  
  				  } else {
    				  echo $task->team_member_name;
  				  } ?>
  				</div>
  				<div class="lesson-info">
    				<p class="lesson-title"><?php echo $task->display_full_task_lesson(); ?></p>
    				<p class="task-title"><?php echo $task->task_name; ?></p>
    				<p class="lesson-due-date"><?php echo "Completed ".$logged_in_user->local_time($task->completed_time); ?></p>
  				</div>
  				<div class="lesson-actions">
  				  <a class="lesson-action-item" href="#"><img src="img/icon-add-issue.png"></a>
  					<a class="lesson-action-item" href="#"><img src="img/icon-add-issue.png"></a>
  				</ul>
    			</div>
    		</div>
      <?php endforeach; ?>
      </ol>
      <?php } ?>
      </div>
		
		<div id="actionable-tasks" class="medium-6 small-12 columns">
  		<h3 class="lesson-group-heading">Actionable Tasks</h3>
      <?php
      if($actionable_tasks) { ?>
      <ol class="lesson-group">
      <?php
      foreach($actionable_tasks as $task) : ?>
        <div class="lesson">
          <div class="lesson-member">
  				<?php if($session->is_admin()) {
				    echo "<a href='task-sheet.php?member={$task->team_member_id}'>{$task->team_member_name}</a>";  
				  } else {
  				  echo $task->team_member_name;
				  } ?>
  				</div>
  				<div class="lesson-info">
    				<p class="lesson-title"><?php echo $task->display_full_task_lesson(); ?></p>
    				<p class="task-title"><?php echo $task->task_name; ?></p>
    				<p class="lesson-due-date"><?php echo "Due ".$task->task_due_date; ?></p>
  				</div>
          <div class="lesson-actions">
  					<a class="lesson-action-item" href="#"><img src="img/icon-add-issue.png"></a>
  				</ul>
          </div>
  			</div>
      <?php endforeach; ?>
      </ol>
      <?php } ?>
		</div>	

<?php include_layout_template('footer.php'); ?>