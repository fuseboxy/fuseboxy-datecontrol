<?php
class DateControl {


	// get (latest) error message
	private static $error;
	public static function error() { return self::$error; }




	/**
	<fusedoc>
		<description>
			obtain start date & end date of specific date-control
		</description>
		<io>
			<in>
				<string name="$dateControl" example="mainland-apply" />
				<string name="$startOrEnd" comments="start|end" optional="yes" />
			</in>
			<out>
				<!-- get both start & end -->
				<structure name="~return~" optional="yes" oncondition="when {$startOrEnd} not specified">
					<date name="start" comments="{null} when not specified & never start; {*} when start anytime" />
					<date name="end" comments="{null} when not specified & end anytime; {*} when never end" />
				</structure>
				<!-- get either start or end -->
				<date name="~return~" optional="yes" oncondition="when {$startOrEnd} specified" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function get($dateControl, $startOrEnd='', $format='Y-m-d H:i') {
		$result = array();
		// obtain specific date-control settings
		$dateRange = Enum::value('DATE_CONTROL', $dateControl);
		if ( $dateRange === false ) {
			self::$error = '[DateControl::get] '.Enum::error();
			return false;
		}
		// parse value & put into container
		$dateRange = array_map('trim', explode('|', $dateRange));
		$result['start'] = isset($dateRange[0]) ? $dateRange[0] : '';
		$result['end']   = isset($dateRange[1]) ? $dateRange[1] : '';
		// apply format
		foreach ( $result as $key => $val ) if ( !empty($val) and $val != '*' ) $result[$key] = date($format, strtotime($val));
		// done!
		return empty($startOrEnd) ? $result : ( $result[$startOrEnd] ?? null );
	}
	// alias methods
	public static function range($dateControl, $startOrEnd='', $format='Y-m-d H:i') { return self::get($dateControl, $startOrEnd, $format); }
	public static function start($dateControl, $format='Y-m-d H:i') { return self::get($dateControl, 'start', $format); }
	public static function end($dateControl, $format='Y-m-d H:i') { return self::get($dateControl, 'end', $format); }




	/**
	<fusedoc>
		<description>
			check whether specific date-control is started but not yet ended
		</description>
		<io>
			<in>
				<string name="$dateControl" />
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isActive($dateControl) {
		return ( self::isStarted($dateControl) and !self::isEnded($dateControl) );
	}
	// alias methods
	public static function active($dateControl) { return self::isActive($dateControl); }
	public static function inactive($dateControl) { return !self::isActive($dateControl); }




	/**
	<fusedoc>
		<description>
			check whether specific date-control is ended
		</description>
		<io>
			<in>
				<string name="$dateControl" />
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isEnded($dateControl) {
		$end = self::end($dateControl);
		if ( $end === false ) throw new Exception('[DateControl::isEnded] '.self::error());
		// when always end...
		if ( empty($end) ) return true;
		// when never end...
		if ( $end == '*' ) return false;
		// when specified...
		return ( date('YmdHis') > date('YmdHis', strtotime($end)) );
	}
	// alias methods
	public static function ended($dateControl) { return self::isEnded($dateControl); }
	public static function closed($dateControl) { return self::isEnded($dateControl); }
	public static function isClosed($dateControl) { return self::isEnded($dateControl); }




	/**
	<fusedoc>
		<description>
			check whether specific date-control is started
		</description>
		<io>
			<in>
				<string name="$dateControl" />
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isStarted($dateControl) {
		$start = self::start($dateControl);
		if ( $start === false ) throw new Exception('[DateControl::isStarted] '.self::error());
		// when never start...
		if ( empty($start) ) return false;
		// when always start...
		if ( $start == '*' ) return true;
		// when specified...
		return ( date('YmdHis') >= date('YmdHis', strtotime($start)) );
	}
	// alias methods
	public static function opened($dateControl) { return self::isStarted($dateControl); }
	public static function started($dateControl) { return self::isStarted($dateControl); }
	public static function isOpened($dateControl) { return self::isStarted($dateControl); }




	/**
	<fusedoc>
		<description>
			obtain message (enum-remark) of specific date-control
		</description>
		<io>
			<in>
				<string name="$dateControl" example="mainland-apply" />
			</in>
			<out>
				<string name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function message($dateControl) {
		$result = Enum::remark('DATE_CONTROL', $dateControl);
		if ( $result === false ) {
			self::$error = '[DateControl::message] Error loading enum remark ('.Enum::error().')';
			return false;
		}
		// done!
		return $result;
	}
	// alias method
	public static function remark($dateControl) { return self::message($dateControl); }


} // class