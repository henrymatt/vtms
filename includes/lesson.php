<?php
require_once(LIB_PATH.DS.'database.php');

class Lesson extends DatabaseObject {
	protected static $table_name="lesson";
	protected static $db_view_fields = array('lesson.id' => 'id',
										'series.id' => 'series_id',
										'series.title' => 'series_name',
										'lesson.fkLanguageSeries' => 'language_series_id',
										'languageSeries.seriesTitle' => 'language_series_title',
										'language.id' => 'language_id',
										'languageSeries.fkTalent' => 'talent_id',
										'language.name' => 'language_name',
										'lesson.number' => 'number',
										'IF ((SELECT SUM(taskGlobal.completionValue) FROM task JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id WHERE task.fkLesson=lesson.id AND task.isCompleted=1) >= (SELECT series.checkableAt FROM series WHERE lesson.fkLanguageSeries=languageSeries.id AND languageSeries.fkSeries=series.id), 1, 0)' => 'is_checkable',
										'(SELECT SUM(taskGlobal.completionValue) FROM task JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id WHERE task.fkLesson=lesson.id AND IF(taskGlobal.isAsset=1, task.isDelivered=1, task.isCompleted=1))' => 'comp_value',
										'lesson.title' => 'title',
										'lesson.trt' => 'trt',
										'lesson.checkedLanguage' => 'checked_language',
										'lesson.checkedVideo' => 'checked_video',
										'lesson.filesMoved' => 'files_moved',
										'level.code' => 'level_code',
										'level.name' => 'level_name',
										'lesson.customYTfield' => 'custom_yt_field',
										'lesson.ytCode' => 'yt_code',
										'lesson.publishDateSite' => 'publish_date_site',
										'lesson.publishDateYouTube' => 'publish_date_yt',
										'LEAST(
										  COALESCE(
										    NULLIF(lesson.publishDateSite, 0), NULLIF(lesson.publishDateYouTube, 0)
										  ),
										  COALESCE(
  										  NULLIF(lesson.publishDateYouTube, 0), NULLIF(lesson.publishDateSite, 0)
  										)
                    )' => 'publish_date',
										'lesson.publishDateSite - INTERVAL (lesson.bufferLength) DAY' => 'buffered_publish_date',
										'lesson.qa_log' => 'qa_log',
										'lesson.qa_url' => 'qa_url',
										'lesson.isQueued' => 'is_queued',
										'lesson.isDetected' => 'is_detected',
										'lesson.isUploadedYt' => 'is_uploaded_yt',
										'lesson.uploadedYtTime' => 'yt_uploaded_time',
										'lesson.queuedTime' => 'queued_time',
										'lesson.checkedLanguageTime' => 'checked_language_time',
										'lesson.checkedVideoTime' => 'checked_video_time',
										'lesson.filesMovedTime' => 'files_moved_time',
										'lesson.exportedTime' => 'exported_time',
										'lesson.detectedTime' => 'detected_time',
										'lesson.timeUploadedDropbox' => 'dropbox_time',
										'lesson.ytIneligible' => 'yt_ineligible',
										'series.checkableAt' => 'checkable_at',
										'lesson.isUploadedForIllTv' => 'is_uploaded_ill_tv',
                    'lesson.illtvTestDate' => 'ill_tv_test_date',
                    'lesson.illtvIsTested' => 'ill_tv_is_tested'
										);
										
	protected static $db_edit_fields = array('lesson.fkLanguageSeries' => 'language_series_id',
											'lesson.number' => 'number',
											'lesson.title' => 'title',
											'lesson.trt' => 'trt',
											'lesson.checkedLanguage' => 'checked_language',
											'lesson.checkedVideo' => 'checked_video',
											'lesson.filesMoved' => 'files_moved',
											'lesson.qa_log' => 'qa_log',
											'lesson.qa_url' => 'qa_url',
											'lesson.isQueued' => 'is_queued',
											'lesson.isUploadedYt' => 'is_uploaded_yt',
                      'lesson.uploadedYtTime' => 'yt_uploaded_time',
                      'lesson.ytCode' => 'yt_code',
                      'lesson.customYTfield' => 'custom_yt_field',
                      'lesson.ytIneligible' => 'yt_ineligible',
											'lesson.isDetected' => 'is_detected',
											'lesson.queuedTime' => 'queued_time',
											'lesson.checkedLanguageTime' => 'checked_language_time',
                      'lesson.checkedVideoTime' => 'checked_video_time',
                      'lesson.filesMovedTime' => 'files_moved_time',
											'lesson.exportedTime' => 'exported_time',
											'lesson.detectedTime' => 'detected_time',
											'lesson.publishDateSite' => 'publish_date_site',
											'lesson.publishDateYouTube' => 'publish_date_yt',
											'lesson.timeUploadedDropbox' => 'dropbox_time',
											'lesson.isUploadedForIllTv' => 'is_uploaded_ill_tv',
                      'lesson.illtvTestDate' => 'ill_tv_test_date',
                      'lesson.illtvIsTested' => 'ill_tv_is_tested'
											);
										
	protected static $db_join_fields = array('languageSeries' => 'languageSeries.id=lesson.fkLanguageSeries',
											 'language' => 'language.id=languageSeries.fkLanguage',
											 'series' => 'series.id=languageSeries.fkSeries',
											 //'task' => 'task.fkLesson=lesson.id',
											 //'taskComment' => 'taskComment.fkTask=task.id',
											 //'taskGlobal' => 'task.fkTaskGlobal=taskGlobal.id',
											 'level' => 'languageSeries.fkLevel=level.id'
											 );
	
	public $id;
	public $series_id;
	public $series_name;
	public $language_series_id;
	public $language_series_title;
	public $language_id;
	public $language_name;
	public $comp_value;
	public $number;
	public $title;
	public $trt;
	public $talent_id;
	public $is_shot;
	public $is_checkable;
	public $checked_video;
	public $checked_language;
	public $is_detected;
	public $files_moved;
	public $date_due;
	public $level_code;
	public $level_name;
	public $yt_code;
	public $issues_remaining;
	public $qa_log;
	public $qa_url;
	public $is_queued;
	public $is_uploaded_yt;
	public $yt_uploaded_time;
	public $yt_ineligible;
	public $queued_time;
	public $exported_time;
	public $publish_date;
	public $publish_date_site;
	public $publish_date_yt;
	public $custom_yt_field;
	public $buffered_publish_date;
	public $time_dropbox;
	public $last_task_id;
	public $last_issue_id;
	public $last_task_time;
	public $last_issue_time;
	public $last_action;
	public $detected_time;
	public $checked_language_time;
	public $checked_video_time;
	public $files_moved_time;
	public $checkable_at;
	public $is_uploaded_ill_tv;
	public $ill_tv_test_date;
  public $ill_tv_is_tested;
	
	// Yannick Inspired Functions
	// Functions meant to query for a basic subset of entries
	// which can be further divided down with PHP, not SQL
	public static function get_lessons_for_render_queue() {
  	
  	$sql  = "SELECT l.id AS id ";
  	$sql .= ", (SELECT SUM(taskGlobal.completionValue) ";
  	$sql .= "FROM task ";
  	$sql .= "JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id ";
  	$sql .= "WHERE task.fkLesson=l.id ";
  	$sql .= "AND task.isCompleted=1 ";
  	$sql .= ") as comp_value ";
  	$sql .= ", s.checkableAt as checkable_at ";
  	$sql .= ", lang.name as language_name ";
  	$sql .= ", s.title as series_name ";
  	$sql .= ", l.number as number ";
  	$sql .= ", l.publishDateSite as publish_date_site ";
  	$sql .= ", l.publishDateYouTube as publish_date_yt ";
  	$sql .= ", l.exportedTime as exported_time ";
  	$sql .= ", l.queuedTime as queued_time ";
  	$sql .= ", l.isQueued as is_queued ";
  	$sql .= ", l.qa_log as qa_log ";
  	$sql .= ", l.qa_url as qa_url ";
  	$sql .= ", level.code as level_code ";
  	$sql .= "FROM lesson l ";
  	$sql .= "JOIN languageSeries ls on l.fkLanguageSeries=ls.id ";
  	$sql .= "JOIN series s on ls.fkSeries=s.id ";
  	$sql .= "JOIN language lang on ls.fkLanguage = lang.id ";
  	$sql .= "JOIN level on ls.fkLevel=level.id ";
  	//$sql .= "WHERE NOT l.checkedLanguage = 1 ";
  	$sql .= "AND NOT l.filesMoved = 1 ";
  	
  	$result = static::find_by_sql($sql);
  	return $result;
	}
	
	public static function get_lessons_for_qa($sort_by) {
  	
  	$sql  = "SELECT l.id AS id ";
  	$sql .= ", lang.name as language_name ";
  	$sql .= ", s.title as series_name ";
  	$sql .= ", l.number as number ";
  	$sql .= ", l.qa_log as qa_log ";
  	$sql .= ", l.qa_url as qa_url ";
  	$sql .= ", level.code as level_code ";
  	$sql .= ", l.publishDateSite as publish_date_site ";
  	$sql .= ", l.publishDateYouTube as publish_date_yt ";
  	$sql .= ", l.exportedTime as exported_time ";
  	$sql .= "FROM lesson l ";
  	$sql .= "JOIN languageSeries ls on l.fkLanguageSeries=ls.id ";
  	$sql .= "JOIN series s on ls.fkSeries=s.id ";
  	$sql .= "JOIN language lang on ls.fkLanguage = lang.id ";
  	$sql .= "JOIN level on ls.fkLevel=level.id ";
  	$sql .= "WHERE NOT l.checkedLanguage = 1 ";
  	$sql .= "AND l.exportedTime > 0 ";
    $sql .= "ORDER BY lang.name ASC, s.title ASC, level.code ASC, l.number ASC ";
  	
  	$result = static::find_by_sql($sql);
  	return $result;
  	
	}
	
	
	//
	//
	//
	
	// ILL TV Functions
	
		public static function find_all_lessons_that_need_upload_to_ill_tv() {
		$sql  = "SELECT ";
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "LEFT JOIN ".$k." ON ".$v." ";
		}
		$sql .= "WHERE lesson.filesMoved = 1 ";
		$sql .= "AND NOT lesson.isUploadedForIllTv = 1 ";
		$sql .= "AND languageSeries.onIllTv = 1 ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY series_name, language_name, level.code, lesson.number ASC ";
		return static::find_by_sql($sql);
	}
	
	public static function find_all_lessons_that_need_testing_on_ill_tv() {
		$sql  = "SELECT ";
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "LEFT JOIN ".$k." ON ".$v." ";
		}
		$sql .= "WHERE lesson.isUploadedForIllTv = 1 ";
		$sql .= "AND NOT lesson.illtvIsTested = 1 ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY series_name, language_name, level.code, lesson.number ASC ";
		return static::find_by_sql($sql);
	}
	
	public static function find_all_ready_for_ill_tv_lessons_for_langauge_series($language_series_id) {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "LEFT JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.filesMoved = 1 ";
		$sql .= "AND lesson.isUploadedForIllTv = 1 ";		
		$sql .= "AND lesson.fkLanguageSeries = {$language_series_id} ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY lesson.number ASC ";
		return static::find_by_sql($sql);
	}
	
	public function lesson_code() {
  	
  	$series = Series::find_by_id($this->series_id);
  	
  	$output  = "";
  	$output .= strtolower(substr($this->language_name,0,3));
  	$output .= "_";
  	$output .= $series->code;
  	if($series->level_significant) {
    	$output .= "-";
    	$output .= $this->level_code;
  	}
  	$output .= "_";
  	if($this->number < 10) { $output .= "0"; }
  	$output .= $this->number;
  	
  	return $output;
	}
	
	//
	//
	//
	
	
	
	public static function find_all_lessons_for_language_series($language_series_id) {
		$child_table_name = "lesson";
		$parent_table_name = "LanguageSeries";
		$group_by_sql = "GROUP BY lesson.id ORDER BY lesson.number ASC";
		return self::find_all_child_for_parent($language_series_id, $child_table_name, $parent_table_name, $group_by_sql);
	}
	
	public function pending_issues() {
  	$sql  = "SELECT taskComment.id FROM taskComment ";
  	$sql .= "JOIN task ON taskComment.fkTask=task.id ";
  	$sql .= "WHERE task.fkLesson = {$this->id} ";
  	$sql .= "AND taskComment.isCompleted = 0 ";
  	$result = static::find_by_sql($sql);
  	return count($result);
	}
	
	public function past_exportable_threshold() {
  	$series = Series::find_by_id($this->series_id);
  	
  	if($this->comp_value >= $series->checkable_at) {
    	return true;
  	} else {
    	return false;
  	}
	}
	
	public function past_shot_threshold() {
  	$series = Series::find_by_id($this->series_id);
  	
  	if($this->comp_value >= $series->shot_at) {
    	return true;
  	} else {
    	return false;
  	}
	}
	
	public static function find_all_lessons_for_series($series_id) {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE languageSeries.fkSeries = " . $series_id ." ";
		return static::find_by_sql($sql);
	}
	
	public static function find_lesson_for_youtube_publish_date($date, $channel_id = NULL) {
  	$sql  = "SELECT ";
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.publishDateYouTube = '$date' ";
		if(isset($channel_id)) {
  		$sql .= "AND languageSeries.fkChannel = $channel_id ";
		}
		return static::find_by_sql($sql);
	}
	
	public static function find_all_youtube_videos_scheduled_in_period($days_from_now, $channel_id = NULL) {
  	$sql  = "SELECT ";
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.publishDateYouTube < CURDATE() + INTERVAL {$days_from_now} DAY ";
		$sql .= "AND lesson.publishDateYouTube > CURDATE() ";
		$sql .= "AND NOT lesson.isUploadedYt ";
		if(isset($channel_id)) {
  		$sql .= "AND languageSeries.fkChannel = $channel_id ";
		}
		return static::find_by_sql($sql);
	}
	
	public static function find_all_eligible_youtube_lessons($channel_id = NULL) {
  	$sql  = "SELECT ";
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE NOT lesson.isUploadedYt = 1 ";
		$sql .= "AND NOT lesson.ytIneligible = 1 ";
		$sql .= "AND lesson.publishDateYouTube = '0000-00-00' ";
		$sql .= "AND lesson.filesMoved = 1 ";
		$sql .= "";
		if(isset($channel_id)) {
  		$sql .= "AND languageSeries.fkChannel = $channel_id ";
		}
		return static::find_by_sql($sql);
	}
	
	
	public static function find_all_completed_lessons_for_language_series($language_series_id) {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.fkLanguageSeries = " . $language_series_id ." ";
		$sql .= "AND lesson.filesMoved=1 ";
		return static::find_by_sql($sql);
	}
	
	public static function find_all_upcoming_due_lessons($days_from_now=7) {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE NOT lesson.filesMoved = 1 ";
		$sql .= "AND DATE(LEAST( COALESCE(NULLIF(lesson.publishDateSite, 0), NULLIF(lesson.publishDateYouTube, 0)), COALESCE(NULLIF(lesson.publishDateYouTube, 0), NULLIF(lesson.publishDateSite, 0)))) < CURDATE() + INTERVAL {$days_from_now} DAY ";
		$sql .= "AND DATE(LEAST( COALESCE(NULLIF(lesson.publishDateSite, 0), NULLIF(lesson.publishDateYouTube, 0)), COALESCE(NULLIF(lesson.publishDateYouTube, 0), NULLIF(lesson.publishDateSite, 0)))) > 0 ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY publish_date ASC, series.title ASC, language.name ASC ";
		return static::find_by_sql($sql);
	}
	
	public static function find_all_lessons_publishing_on_date($date) {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= ', IF ((SELECT SUM(taskGlobal.completionValue) FROM task JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id WHERE task.fkLesson=lesson.id AND task.isCompleted=1) >= (SELECT series.shotAt FROM series WHERE lesson.fkLanguageSeries=languageSeries.id AND languageSeries.fkSeries=series.id), 1, 0) AS is_shot ';
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE DATE(lesson.publishDateSite) = '{$date}' ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY publish_date ASC, series.title ASC, language.name ASC, lesson.number ASC ";
		return static::find_by_sql($sql);
	}
	
	public static function find_all_lessons_from_last_week($current_time) {
  	$last_week = date ('Y-m-d H:i:s', strtotime('-7 day' . $current_time->format('Y-m-d H:i:s')));
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.filesMovedTime < '{$current_time->format('Y-m-d H:i:s')}' ";
		$sql .= "AND lesson.filesMovedTime > '{$last_week}' ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY language.name ASC, series.title ASC ";
				
		return static::find_by_sql($sql);
  }
	/*
	public static function find_all_qa_lessons() {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE NOT lesson.checkedLanguage = 1 ";
		// is_checkable
		$sql .= "AND IF ((SELECT SUM(taskGlobal.completionValue) FROM task JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id WHERE task.fkLesson=lesson.id AND task.isCompleted=1) >= (SELECT series.checkableAt FROM series WHERE lesson.fkLanguageSeries=languageSeries.id AND languageSeries.fkSeries=series.id), 1, 0) = 1 ";
		//$sql .= "AND LENGTH(lesson.qa_url) > 0 ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "ORDER BY language.name ASC, series.title ASC ";
		return static::find_by_sql($sql);
	}
	*/
	public function add_to_dropbox() {
		global $database;
		$current_time = new DateTime(null, new DateTimeZone('UTC'));
		
		// Then update
		$sql  = "UPDATE lesson ";
		$sql .= "SET timeUploadedDropbox='{$current_time->format('Y-m-d H:i:s')}' ";
		$sql .= "WHERE id={$this->id} ";
		$sql .= "LIMIT 1";
		
		$database->query($sql);
	}
	
	// Operations Page Functions
	
	public static function find_all_exportable_lessons() {
		// detect the latest task completion time and issue fixed time
		// last issue fixed, exported, yet it still appears. If last export time > last issue time, don't show
		$sql  = "SELECT ";	
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "LEFT JOIN task ON task.fkLesson=lesson.id ";
		$sql .= "LEFT JOIN taskComment ON taskComment.fkTask=task.id ";
		$sql .= "WHERE NOT lesson.filesMoved=1 ";
		$sql .= "AND NOT lesson.isQueued=1 ";
		
		return static::find_by_sql($sql);
	}

   	public static function find_all_queued_lessons() {
		// detect the latest task completion time and issue fixed time
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.isQueued = 1 ";
		$sql .= "AND NOT lesson.filesMoved =1 ";
		$sql .= "ORDER BY lesson.queuedTime DESC ";
		
		return static::find_by_sql($sql);
	}
	
	public static function get_ready_to_publish_lessons() {
  	// detect the latest task completion time and issue fixed time
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.filesMoved = 1 "; 
		$sql .= "AND NOT lesson.isDetected = 1 ";
		$sql .= "AND NOT lesson.publishDateSite = '0000-00-00' ";
		$sql .= "ORDER BY lesson.publishDateSite ASC, language.name ASC, series.title ASC, lesson.number ASC ";
		return static::find_by_sql($sql);
	}
	
	public static function get_ready_to_publish_youtube_lessons() {
  	// detect the latest task completion time and issue fixed time
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.filesMoved = 1 ";
		$sql .= "AND NOT lesson.isUploadedYt = 1 ";
		$sql .= "AND NOT lesson.publishDateYouTube = '0000-00-00' ";  
		$sql .= "ORDER BY lesson.publishDateYouTube ASC, language.name ASC, series.title ASC, lesson.number ASC ";
		return static::find_by_sql($sql);
	} 
	
	public static function get_recently_detected_lessons() {
		// detect the latest task completion time and issue fixed time
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.isDetected = 1 ";
		$sql .= "AND lesson.detectedTime > 1 ";
		$sql .= "ORDER BY lesson.detectedTime DESC ";
		
		return static::find_by_sql($sql);
	}
	
	public static function find_all_ready_to_video_check_lessons($sort_by='abc') {
		// detect the latest task completion time and issue fixed time
		// and there are no more missing tasks
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "JOIN task ON task.fkLesson=lesson.id ";
		$sql .= "LEFT JOIN taskComment ON taskComment.fkTask=task.id ";
		$sql .= "WHERE NOT lesson.checkedVideo = 1 ";
		$sql .= "AND lesson.checkedLanguage = 1 ";
		$sql .= "AND NOT lesson.filesMoved = 1 ";
		$sql .= "GROUP BY lesson.id ";
		// All issues for this lesson have been fixed, or there were never any issues
		$sql .= "HAVING ((Count(taskComment.id) - Sum(taskComment.isCompleted) = 0) ";
		$sql .= "        OR Count(taskComment.id) < 1) ";
		
		// Last issue fixed time OR last task finished time is less than last exported time
		$sql .= "	AND (lesson.exportedTime > ";
		$sql .= "			(SELECT MAX(task.timeCompleted) ";
		$sql .= "				FROM lesson sub_lesson ";
		$sql .= "					JOIN task ";
		$sql .= "					  ON sub_lesson.id=task.fkLesson ";
		$sql .= "				WHERE sub_lesson.id=lesson.id)) ";
		$sql .= "	AND	(lesson.exportedTime > ";
		$sql .= "			(SELECT IF( MAX( taskComment.timeCompleted ) IS NULL , 0, MAX( taskComment.timeCompleted ) ) ";
		$sql .= "				FROM lesson sub_lesson ";
		$sql .= "					JOIN task ";
		$sql .= "					  ON sub_lesson.id=task.fkLesson ";
		$sql .= "					JOIN taskComment ";
		$sql .= "					  ON task.id=taskComment.fkTask ";
		$sql .= "				WHERE sub_lesson.id=lesson.id) ";
		$sql .= "  		) ";
		
		// No missing tasks
		$sql .= "	AND (SELECT COUNT(task.id) ";
		$sql .= "		FROM lesson sub_lesson ";
		$sql .= "			JOIN task ";
		$sql .= "				 ON sub_lesson.id=task.fkLesson ";
		$sql .= "		WHERE sub_lesson.id=lesson.id ";
		$sql .= "		  AND task.isCompleted=1 ) = ";
		$sql .= "		(SELECT COUNT(task.id) ";
		$sql .= "		FROM lesson sub_lesson ";
		$sql .= "			JOIN task ";
		$sql .= "				 ON sub_lesson.id=task.fkLesson ";
		$sql .= "		WHERE sub_lesson.id=lesson.id) ";
		if ($sort_by=='abc') {
			$sql .= "ORDER BY language.name, series.title, level.id, lesson.number ASC ";
		} else if ($sort_by=='pub') {
			$sql .= "ORDER BY lesson.publishDateSite ASC ";	
		} else {
			$sql .= "ORDER BY lesson.exportedTime DESC ";
		}
		
		return static::find_by_sql($sql);
	}
	
	public static function find_all_checkable_lessons($sort_by='abc') {
		// Now doing double duty as the method that checks for operations and qa page
		// When there are no issues on a lesson, it is not appearing
		
		// $findQALessons->addFindCriterion('Completion Value Total', '>19');
		// $findQALessons->addFindCriterion('Exported Last Time', '*');
		// $findQALessons->addFindCriterion('QA URL', '*');
		// $findQALessons->addSortRule('Language::Language Name', 1, FILEMAKER_SORT_ASCEND);

		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "JOIN task ON task.fkLesson=lesson.id ";
		$sql .= "LEFT JOIN taskComment ON taskComment.fkTask=task.id ";
		$sql .= "WHERE NOT lesson.checkedLanguage = 1 ";
		$sql .= "AND NOT lesson.filesMoved=1 ";
		//$sql .= "AND NOT lesson.qa_url='' ";
		$sql .= "AND lesson.exportedTime > 0 ";
		$sql .= "AND IF ((SELECT SUM(taskGlobal.completionValue) FROM task JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id WHERE task.fkLesson=lesson.id AND task.isCompleted=1) >= (SELECT series.checkableAt FROM series WHERE lesson.fkLanguageSeries=languageSeries.id AND languageSeries.fkSeries=series.id), 1, 0) = 1 ";
		$sql .= "GROUP BY lesson.id ";
		$sql .= "HAVING (Count(taskComment.id) - Sum(taskComment.isCompleted) = 0 ";
		$sql .= "        OR Count(taskComment.id) < 1) ";

		
		// Exported after last issue
		$sql .= "		AND (lesson.exportedTime > ";
		$sql .= "			(SELECT IF( MAX( taskComment.timeCompleted ) IS NULL , 0, MAX( taskComment.timeCompleted ) ) ";
		$sql .= "				FROM lesson sub_lesson ";
		$sql .= "					JOIN task ";
		$sql .= "					  ON sub_lesson.id=task.fkLesson ";
		$sql .= "					JOIN taskComment ";
		$sql .= "					  ON task.id=taskComment.fkTask ";
		$sql .= "				WHERE sub_lesson.id=lesson.id)) ";
		
		// Past Checkable Completion Value
		$sql .= "       AND (SELECT Sum(taskGlobal.completionValue) ";
		$sql .= "            FROM   lesson sub_lesson ";
		$sql .= "                   JOIN task ";
		$sql .= "                     ON task.fkLesson = sub_lesson.id ";
		$sql .= "                   JOIN taskGlobal ";
		$sql .= "                     ON task.fkTaskGlobal = taskGlobal.id ";
		$sql .= "                   JOIN languageSeries ";
		$sql .= "                     ON sub_lesson.fkLanguageSeries = languageSeries.id ";
		$sql .= "                   JOIN series ";
		$sql .= "                     ON languageSeries.fkSeries = series.id ";
		$sql .= "            WHERE  task.isCompleted = 1 ";
		$sql .= "                    AND sub_lesson.id = lesson.id) >= ";
		$sql .= "            (SELECT series.checkableAt ";
		$sql .= "             FROM   lesson sub_lesson_series ";
		$sql .= "                    JOIN languageSeries ";
		$sql .= "                      ON ";
		$sql .= "        sub_lesson_series.fkLanguageSeries = languageSeries.id ";
		$sql .= "                JOIN series ";
		$sql .= "                  ON ";
		$sql .= "        languageSeries.fkSeries = series.id ";
		$sql .= "         WHERE  lesson.id = sub_lesson_series.id ";
		$sql .= "			) ";
		if ($sort_by=='abc') {
			$sql .= "ORDER BY language.name, series.title, level.id, lesson.number ASC ";
		} else if ($sort_by=='pub') {
			$sql .= "ORDER BY lesson.publishDateSite ASC ";	
		} else {
			$sql .= "ORDER BY lesson.exportedTime DESC ";
		}
		return static::find_by_sql($sql);
	}
	
	public static function find_qa_lessons($sort_by='abc') {
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "JOIN task ON task.fkLesson=lesson.id ";
		$sql .= "LEFT JOIN taskComment ON taskComment.fkTask=task.id ";
		$sql .= "WHERE NOT lesson.checkedLanguage = 1 ";
		$sql .= "AND NOT lesson.filesMoved=1 ";
		$sql .= "AND NOT lesson.qa_url='' ";
		$sql .= "AND lesson.exportedTime > 0 ";
		$sql .= "AND NOT language.id=9 ";
		$sql .= "AND NOT language.id=39 ";
		//$sql .= "AND IF ((SELECT SUM(taskGlobal.completionValue) FROM task JOIN taskGlobal ON task.fkTaskGlobal=taskGlobal.id WHERE task.fkLesson=lesson.id AND task.isCompleted=1) >= (SELECT series.checkableAt FROM series WHERE lesson.fkLanguageSeries=languageSeries.id AND languageSeries.fkSeries=series.id), 1, 0) = 1 ";
		$sql .= "GROUP BY lesson.id ";
		//$sql .= "HAVING (Count(taskComment.id) - Sum(taskComment.isCompleted) = 0 ";
		//$sql .= "        OR Count(taskComment.id) < 1) ";
		$sql .= "	HAVING lesson.exportedTime > ";
		$sql .= "			(SELECT MAX(task.timeCompleted) ";
		$sql .= "				FROM lesson sub_lesson ";
		$sql .= "					JOIN task ";
		$sql .= "					  ON sub_lesson.id=task.fkLesson ";
		$sql .= "				WHERE sub_lesson.id=lesson.id) ";
		
		// Past Checkable Completion Value
		$sql .= "       AND (SELECT Sum(taskGlobal.completionValue) ";
		$sql .= "            FROM   lesson sub_lesson ";
		$sql .= "                   JOIN task ";
		$sql .= "                     ON task.fkLesson = sub_lesson.id ";
		$sql .= "                   JOIN taskGlobal ";
		$sql .= "                     ON task.fkTaskGlobal = taskGlobal.id ";
		$sql .= "                   JOIN languageSeries ";
		$sql .= "                     ON sub_lesson.fkLanguageSeries = languageSeries.id ";
		$sql .= "                   JOIN series ";
		$sql .= "                     ON languageSeries.fkSeries = series.id ";
		$sql .= "            WHERE  task.isCompleted = 1 ";
		$sql .= "                    AND sub_lesson.id = lesson.id) >= ";
		$sql .= "            (SELECT series.checkableAt ";
		$sql .= "             FROM   lesson sub_lesson_series ";
		$sql .= "                    JOIN languageSeries ";
		$sql .= "                      ON ";
		$sql .= "        sub_lesson_series.fkLanguageSeries = languageSeries.id ";
		$sql .= "                JOIN series ";
		$sql .= "                  ON ";
		$sql .= "        languageSeries.fkSeries = series.id ";
		$sql .= "         WHERE  lesson.id = sub_lesson_series.id ";
		$sql .= "			) ";
		if($sort_by == 'pub') {
			$sql .= "ORDER BY lesson.publishDateSite ASC ";
		} else {
			$sql .= "ORDER BY language.name, series.title, level.id, lesson.number ASC ";
		}
		
		return static::find_by_sql($sql);
	}
	
	public static function find_all() {
		return self::find_all_limit(0);
	}
	
	public static function find_all_moveable_lessons() {
		// detect the latest task completion time and issue fixed time
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.checkedVideo = 1 ";
		$sql .= "AND lesson.checkedLanguage = 1 ";
		$sql .= "AND NOT lesson.filesMoved = 1 ";
		$sql .= "ORDER BY lesson.publishDateSite ASC ";
		
		return static::find_by_sql($sql);
	}
	
	public static function find_all_recently_completed_lessons() {
		// detect the latest task completion time and issue fixed time
		$sql  = "SELECT ";		
		foreach (self::$db_view_fields as $k => $v) {
			$sql .= $k." AS ".$v;
			$i++;
			$i <= count(self::$db_view_fields) - 1 ? $sql .= ", " : $sql .= " ";
		}
		$sql .= "FROM ".self::$table_name." ";
		foreach (self::$db_join_fields as $k => $v) {
			$sql .= "JOIN ".$k." ON ".$v." ";
			}
		$sql .= "WHERE lesson.filesMoved = 1 ";
		$sql .= "AND lesson.trt < 1 ";
		$sql .= "ORDER BY lesson.publishDateSite ASC ";
		
		return static::find_by_sql($sql);
	}
	
	public function display_full_lesson() {
		echo $this->language_name . " - " . $this->series_name . " (" . $this->level_code . ") #" . $this->number;
	}
	
	public function display_full_lesson_with_link() {
  	$output  = "<a href='lesson.php?id=";
  	$output .= $this->id;
  	$output .= "'>";
  	$output .= $this->language_name . " - " . $this->series_name . " (" . $this->level_code . ") #" . $this->number;
  	$output .= "</a>";
  	return $output;
	}
	
	public function display_list_of_issues_with_link() {
		$issues = Issue::get_unfinished_issues_for_lesson($this->id);
		echo "<a href='issues-for-lesson.php?id=".$this->id."'>Issues: ".count($issues)."</a>";
	}
	
	public function display_full_lesson_navigation() {
		echo "<a href='series.php?id=".$this->series_id."'>";
		echo $this->series_name;
		echo "</a>";
		echo " > ";
		echo "<img src='images/{$this->level_code}.png'> ";
		echo "<a href='language-series.php?series=".$this->series_id."&id=".$this->language_series_id."'>";
		echo $this->language_series_title;
		echo "</a>";
		echo " > ";
		echo "#{$this->number} {$this->title}";
	}
	
	public function display_lesson_topbar($active_page="main") {
		
		if($active_page=="main") {
			echo "Lesson";
		} else {
			echo "<a href='lesson.php?series={$this->series_id}&langSeries={$this->language_series_id}&lesson={$this->id}'>Lesson</a>";
		}
		echo " | "; 
		if ($active_page=="script") {
			echo "Script";
		} else {
			echo "<a href='lesson-script.php?id={$this->id}'>Script</a>";
		}
	}
	
	public function display_lesson_status_bar() {
  	$issues = $this->pending_issues();
  	
	  echo "<div class='lesson-production'>";
	  echo "  <div class='lesson-issues'>";
  	echo "    <a class='issues-bar' href='#'>Issues: ".$issues."</a>";
  	echo "  </div>";
	  echo "  <div class='lesson-status'>";
    echo "	  <p class='lesson-status-item'>";
  	echo "      <img src='";
  	echo $this->past_shot_threshold() ? 'img/lesson-status-yes-shot.png' : 'img/lesson-status-not-shot.png';
  	echo "'>";
  	echo "    </p>";
  	echo "    <p class='lesson-status-item'>";
  	echo "      <img src='";
  	echo $this->past_exportable_threshold() ? 'img/lesson-status-yes-checkable.png' : 'img/lesson-status-not-checkable.png';
  	echo "'>";
  	echo "    </p>";
  	echo "	  <p class='lesson-status-item'>";
  	echo "      <img src='";
  	echo $this->checked_language ? 'img/lesson-status-yes-language.png' : 'img/lesson-status-not-language.png';
  	echo "'>";
  	echo "    </p>";
  	echo "	  <p class='lesson-status-item'>";
  	echo "      <img src='";
  	echo $this->checked_video ? 'img/lesson-status-yes-video.png' : 'img/lesson-status-not-video.png';
  	echo "'>";
  	echo "    </p>";
  	echo "	  <p class='lesson-status-item'>";
  	echo "      <img src='";
  	echo $this->files_moved ? 'img/lesson-status-yes-moved.png' : 'img/lesson-status-not-moved.png';
  	echo "'>";
  	echo "    </p>";
	  echo "  </div>";
	  echo "</div>";
	}
	
}
?>