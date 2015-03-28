<?php
require_once(LIB_PATH.DS.'database.php');

class Series extends DatabaseObject {
	protected static $table_name="series";
	protected static $db_view_fields = array('series.id' => 'id',
										'series.code' => 'code',
										'series.title' => 'title',
										'SEC_TO_TIME((SELECT SUM( lesson.trt ) FROM series s JOIN languageSeries ON s.id = languageSeries.fkSeries JOIN lesson ON lesson.fkLanguageSeries = languageSeries.id WHERE s.id = series.id))' => 'series_trt',
										'series.shotAt' => 'shot_at',
										'series.checkableAt' => 'checkable_at',
										'series.ytTitleTemplate' => 'yt_title_template',
										'series.ytDescriptionTemplate' => 'yt_description_template'
										);
										
	protected static $db_edit_fields = array('series.code' => 'code',
										'series.title' => 'title',
										'series.shotAt' => 'shot_at',
										'series.checkableAt' => 'checkable_at',
										'series.ytTitleTemplate' => 'yt_title_template',
										'series.ytDescriptionTemplate' => 'yt_description_template'
										);
										
	protected static $db_join_fields = array();
	
	public $id;
	public $code;
	public $title;
	public $shot_at;
	public $checkable_at;
	public $series_trt;
	public $yt_title_template;
	public $yt_description_template;

	public static function get_series_title_from_id($series_id) {
		$series = Series::find_by_id($series_id);
		return $series->title;
	}
	
	public static function get_series_total_completion_value($series_id) {
  	global $db;
  	$sql  = "SELECT SUM(taskGlobal.completionValue) FROM series";
  	$sql .= " JOIN taskGlobal ON series.id = taskGlobal.fkSeries";
  	$sql .= " WHERE series.id = " . $series_id;
  	$result = $db->query($sql);
  	return mysql_fetch_row($result)[0];
	}
	
	public static function generate_render_threshold_array() {
  	$output = [];
  	
  	$sql  = "SELECT ";		
		$sql .= "series.id as id, ";
		$sql .= "series.checkableAt as checkable_at ";
		$sql .= "FROM ".self::$table_name." ";
		$result = static::find_by_sql($sql);
		foreach($result as $row) {
  		$output[$row->id] = $row->checkable_at;
		}
		
		return $output;
	}
	
}
?>