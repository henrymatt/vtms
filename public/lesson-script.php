<?php require_once("../includes/initialize.php"); ?>
<?php		
	$lesson_id = $db->escape_value($_GET['id']);
	$lesson = Lesson::find_by_id($lesson_id);
	
	if($_POST['edited_script']) {
		$shot_ids = $_POST['shot_id'];
		$asset_ids = $_POST['asset'];
		$sections = $_POST['section'];
		$scripts = $_POST['script'];
		$shots = $_POST['shot'];
		$scripts_english = $_POST['script_english'];
		$i = 0;
		foreach($shot_ids as $shot_id) {
			$shot = Shot::find_by_id($shot_id);
			$shot->asset_id = $asset_ids[$i];
			$shot->section = $sections[$i];
			$shot->shot = $shots[$i];
			$shot->script = $db->escape_value($scripts[$i]);
			$shot->script_english = $db->escape_value($scripts_english[$i]);
			$shot->update();
			$i++;
		}
		
		$new_shot_ids = $_POST['new_ID'];
		$new_asset_ids = $_POST['new_asset_id'];
		$new_sections = $_POST['new_section'];
		$new_scripts = $_POST['new_script'];
		$new_shots = $_POST['new_shot'];
		$new_scripts_english = $_POST['new_script_english'];
		$i = 0;
		foreach($new_shot_ids as $new_shot_id) {
			$shot = new Shot();
			$shot->lesson_id = $lesson_id;
			$shot->asset_id = $new_asset_ids[$i];
			$shot->section = $new_sections[$i];
			$shot->shot = $new_shots[$i];
			$shot->script = $db->escape_value($new_scripts[$i]);
			$shot->script_english = $db->escape_value($new_scripts_english[$i]);
			$shot->create();
			$i++;
		}
	}
	$assets = Task::find_all_assets_for_lesson($lesson->id);
	$shots = Shot::find_all_shots_for_lesson($lesson->id);
?>

<?php include_layout_template('header.php'); ?>
		<p><a href="lesson.php?series=<?php echo $lesson->series_id; ?>&langSeries=<?php echo $lesson->language_series_id; ?>&lesson=<?php echo $lesson->id; ?>">Return to Lesson Page</a></p>
		<?php foreach($assets as $asset): ?>
			<div class="panel">
				<h3><?php echo $asset->task_name; ?></h3>
				<p>Total Shots: <?php echo count(Shot::find_all_shots_for_asset($asset->id)); ?></p>
				<p>Total Done: <?php echo count(Shot::find_all_completed_shots_for_asset($asset->id)); ?></p>
			</div>
		<?php endforeach; ?>
		<div id="script">
		<h2>Script</h2>
		<form action="lesson-script.php?id=<?php echo $lesson->id; ?>" method="post">
		<table class="script" id="lesson-script">
			<th>Asset</th><th>Section</th><th>Shot</th><th>Script</th><th>Script English</th>
			<?php foreach($shots as $shot): ?>
					<tr>
						<td>
							<input type="hidden" name="shot_id[]" value="<?php echo $shot->id; ?>">
							<select name="asset[]">
							<option value="0">not set</option>
							<?php foreach($assets as $asset) {
								echo "<option value='{$asset->id}' ";
								if($shot->asset_id == $asset->id) { echo "selected"; }
								echo ">{$asset->task_name}</option>";
							} ?>
						</td>
						<td><input type="text" name="section[]" size="10" value="<?php echo $shot->section; ?>"></td>
						<td><input type="text" name="shot[]" size="3" value="<?php echo $shot->shot; ?>"></td>
						<td><textarea name="script[]" rows="15" cols="35"><?php echo $shot->script; ?></textarea></td>
						<td><textarea name="script_english[]" rows="15" cols="35"><?php echo $shot->script_english; ?></textarea></td>
					</tr>
				<?php endforeach; ?>
				<tr><td><span onclick="addRow()">+ Row</span></td></tr>
		</table>
		<input type="submit" name="edited_script" value="Submit Changes">
		</form>
		</div>
		<p><a href="language-series.php?series=<?php echo $lesson->series_id; ?>&id=<?php echo $lesson->language_series_id; ?>"><- Return to Language Series List</a></p>
		<script>
		newRowIndex = 0;
		
		function addRow() {
			var table = document.getElementById("lesson-script");
			var tableLength = document.getElementsByTagName("tr").length;
			var row = table.insertRow(tableLength-1);
			var cellAsset = row.insertCell(0);
			var cellSection = row.insertCell(1);
			var cellShot = row.insertCell(2);
			var cellScript = row.insertCell(3);
			var cellEnglish = row.insertCell(4);
			
			var inputAsset = document.createElement("select");
			inputAsset.name = "new_asset_id[]";
			
			<?php foreach($assets as $asset) { ?>
				var option = document.createElement("option");
				option.value = "<?php echo $asset->id; ?>";
				option.innerHTML = "<?php echo $asset->task_name; ?>";
				inputAsset.appendChild(option);
			<?php } ?>
			
			var inputId = document.createElement("input");
			inputId.type = "hidden";
			inputId.name = "new_ID[]";
			inputId.value = newRowIndex;
			
			var inputSection = document.createElement("input");
			inputSection.type = "text";
			inputSection.size = 10;
			inputSection.name = "new_section[]";
			
			var inputShot = document.createElement("input");
			inputShot.type = "text";
			inputShot.size = 3;
			inputShot.name = "new_shot[]";
			
			var inputScript = document.createElement("textarea");
			inputScript.rows = 15;
			inputScript.cols = 35;
			inputScript.name = "new_script[]";
			
			var inputEnglish = document.createElement("textarea");
			inputEnglish.rows = 15;
			inputEnglish.cols = 35;
			inputEnglish.name = "new_script_english[]";
			
			cellAsset.appendChild(inputId);
			cellAsset.appendChild(inputAsset);
			cellSection.appendChild(inputSection);
			cellShot.appendChild(inputShot);
			cellScript.appendChild(inputScript);
			cellEnglish.appendChild(inputEnglish);
			
			newRowIndex++;
		}
		
		</script>

<?php include_layout_template('footer.php'); ?>