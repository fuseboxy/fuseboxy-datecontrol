<?php
class DateControl {


	// get (latest) error message
	private static $error;
	public static function error() { return self::$error; }




	/**
	<fusedoc>
		<description>
			convert list of enum keys into array
			===> when any enum key has wildcard (e.g. [%-event], sports-*, ...)
			===> obtain related enum keys accordingly
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" comments="might have wildcard" />
				</mixed>
			</in>
			<out>
				<array name="~return~">
					<string name="+" />
				</array>
			</out>
		</io>
	</fusedoc>
	*/
	private static function explodeEnumKeys($enumKeys) {
		return array_unique(array_merge(...array_map(function($key){
			return array_keys(Enum::array('DATE_CONTROL', $key));
		}, is_string($enumKeys) ? explode(',', $enumKeys) : $enumKeys)));
	}




	/**
	<fusedoc>
		<description>
			obtain start date & end date of specific date-control
		</description>
		<io>
			<in>
				<string name="$enumKey" example="mainland-apply" />
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
	public static function get($enumKey, $startOrEnd='', $format='Y-m-d H:i') {
		$result = array();
		// obtain specific date-control settings
		$dateRange = Enum::value('DATE_CONTROL', $enumKey);
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
	public static function range($enumKey, $startOrEnd='', $format='Y-m-d H:i') { return self::get($enumKey, $startOrEnd, $format); }
	public static function start($enumKey, $format='Y-m-d H:i') { return self::get($enumKey, 'start', $format); }
	public static function end($enumKey, $format='Y-m-d H:i') { return self::get($enumKey, 'end', $format); }




	/**
	<fusedoc>
		<description>
			check whether (single) specific date-control is started and not ended yet
		</description>
		<io>
			<in>
				<string name="$enumKey" />
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isActive($enumKey) { return ( self::isStarted($enumKey) and !self::isEnded($enumKey) ); }




	/**
	<fusedoc>
		<description>
			check whether all specified date-controls are active
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" comments="can have wildcard" />
				</mixed>
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isAllActive($enumKeys) {
		$enumKeys = self::explodeEnumKeys($enumKeys);
		if ( $enumKeys === false ) return false;
		return ( array_sum(array_map(fn($key)=>(int)self::isActive($key), $enumKeys)) == count($enumKeys) );
	}




	/**
	<fusedoc>
		<description>
			check whether all specified date-controls are ended
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" />
				</mixed>
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isAllEnded($enumKeys) {
		$enumKeys = self::explodeEnumKeys($enumKeys);
		if ( $enumKeys === false ) return false;
		return ( array_sum(array_map(fn($key)=>(int)self::isEnded($key), $enumKeys)) == count($enumKeys) );
	}




	/**
	<fusedoc>
		<description>
			check whether all specified date-controls are ended
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" />
				</mixed>
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isAllStarted($enumKeys) {
		$enumKeys = self::explodeEnumKeys($enumKeys);
		if ( $enumKeys === false ) return false;
		return ( array_sum(array_map(fn($key)=>(int)self::isStarted($key), $enumKeys)) == count($enumKeys) );
	}




	/**
	<fusedoc>
		<description>
			check whether any specified date-controls is active
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" />
				</mixed>
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isAnyActive($enumKeys) {
		$enumKeys = self::explodeEnumKeys($enumKeys);
		if ( $enumKeys === false ) return false;
		return ( array_sum(array_map(fn($key)=>(int)self::isActive($key), $enumKeys)) != 0 );
	}




	/**
	<fusedoc>
		<description>
			check whether any specified date-controls is active
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" />
				</mixed>
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isAnyEnded($enumKeys) {
		$enumKeys = self::explodeEnumKeys($enumKeys);
		if ( $enumKeys === false ) return false;
		return ( array_sum(array_map(fn($key)=>(int)self::isEnded($key), $enumKeys)) != 0 );
	}




	/**
	<fusedoc>
		<description>
			check whether all specified date-controls are ended
		</description>
		<io>
			<in>
				<mixed name="$enumKeys" comments="array|list|string" delim=",">
					<string name="+" />
				</mixed>
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isAnyStarted($enumKeys) {
		$enumKeys = self::explodeEnumKeys($enumKeys);
		if ( $enumKeys === false ) return false;
		return ( array_sum(array_map(fn($key)=>(int)self::isStarted($key), $enumKeys)) != 0 );
	}




	/**
	<fusedoc>
		<description>
			check whether (single) specific date-control is ended
		</description>
		<io>
			<in>
				<string name="$enumKey" />
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isEnded($enumKey) {
		$end = self::end($enumKey);
		if ( $end === false ) throw new Exception('[DateControl::isEnded] '.self::error());
		// when always end...
		if ( empty($end) ) return true;
		// when never end...
		if ( $end == '*' ) return false;
		// when specified...
		return ( date('YmdHis') > date('YmdHis', strtotime($end)) );
	}




	/**
	<fusedoc>
		<description>
			check whether specific date-control is started
		</description>
		<io>
			<in>
				<string name="$enumKey" />
			</in>
			<out>
				<boolean name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function isStarted($enumKey) {
		$start = self::start($enumKey);
		if ( $start === false ) throw new Exception('[DateControl::isStarted] '.self::error());
		// when never start...
		if ( empty($start) ) return false;
		// when always start...
		if ( $start == '*' ) return true;
		// when specified...
		return ( date('YmdHis') >= date('YmdHis', strtotime($start)) );
	}




	/**
	<fusedoc>
		<description>
			obtain message (enum-remark) of specific date-control
		</description>
		<io>
			<in>
				<string name="$enumKey" example="mainland-apply" />
			</in>
			<out>
				<string name="~return~" />
			</out>
		</io>
	</fusedoc>
	*/
	public static function message($enumKey) {
		$result = Enum::remark('DATE_CONTROL', $enumKey);
		if ( $result === false ) {
			self::$error = '[DateControl::message] Error loading enum remark ('.Enum::error().')';
			return false;
		}
		// done!
		return $result;
	}


} // class