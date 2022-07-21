<?php /*
<fusedoc>
	<description>
		generate UI for date-control CRUD operations
	</description>
	<io>
		<in>
			<structure name="$dateControl" comments="config">
				<!-- essentials -->
				<string name="layoutPath" />
				<string_or_structure name="retainParam" />
				<!-- permissions -->
				<boolean name="allowNew" />
				<boolean name="allowEdit" />
				<boolean name="allowDelete" />
				<boolean name="allowToggle" />
				<boolean name="allowSort" />
				<!-- filter & order -->
				<structure name="listFilter">
					<string name="sql" />
					<array name="param" />
				</structure>
				<string name="listOrder" />
				<!-- others -->
				<boolean name="writeLog" />
			</structure>
		</in>
		<out>
		</out>
	</io>
</fusedoc>
*/



/*
// breadcrumb
$arguments['breadcrumb'] = array('*', 'Setup', 'Date Control');


// remark fields in different language
$localeAll = I18N::localeAll();
$localeCount = count($localeAll);
$remarkFields = array();
foreach ( $localeAll as $lang ) {
	$fieldName = ( $lang == 'en' ) ? 'remark' : str_replace('-', '_', "remark__{$lang}");
	$remarkFields[$fieldName] = array(
		'format' => 'textarea',
		'style' => 'height: 66px',
		'label' => ( $fieldName == 'remark' ) ? 'Message' : false,
		'inline-label' => ( $localeCount > 1 ) ? "<div class='text-uppercase' style='width: 2.5rem'>{$lang}</div>" : false,
	);
}


// config
$scaffold = array(
	'beanType' => 'enum',
	'editMode' => 'inline',
	'allowNew' => Auth::userInRole('SUPER'),
	'allowDelete' => Auth::userInRole('SUPER'),
	'allowToggle' => Auth::userInRole('SUPER'),
	'layoutPath' => F::appPath('view/academic_year/layout.php'),
	'listFilter' => array('type = ?', ['DATE_CONTROL']),
	'listOrder' => 'ORDER BY `key` ',
	'listField' => array(
		'id' => '60',
		'key|type' => '15%',
		'start|end|value' => '20%',
		implode('|', array_keys($remarkFields)) => '40%',
	),
	'fieldConfig' => array_merge([
		'key' => array('readonly' => !Auth::userInRole('SUPER')),
		'type' => array('label' => false, 'value' => 'DATE_CONTROL', 'readonly' => true),
		'start' => array('format' => 'output'),
		'end' => array('format' => 'output'),
		'value' => array('format' => 'hidden', 'label' => false),
	], $remarkFields),
	'scriptPath' => array(
		'row' => F::appPath('view/date_control/row.php'),
		'inline_edit' => F::appPath('view/date_control/inline_edit.php'),
	),
	'writeLog' => class_exists('Log'),
);


// component
include F::appPath('controller/scaffold_controller.php');
*/