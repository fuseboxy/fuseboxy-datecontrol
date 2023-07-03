<?php
F::redirect('auth&callback='.base64_encode($_SERVER['REQUEST_URI']), !Auth::user());
F::error('Forbidden', !Auth::userInRole('SUPER,ADMIN'));


// message fields in different language
$localeAll = class_exists('I18N') ? I18N::localeAll() : array('en');
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
$scaffold = array_merge([
	'beanType' => 'enum',
	'editMode' => 'inline',
	'allowDelete' => Auth::userInRole('SUPER'),
	'layoutPath' => F::appPath('view/global/layout.php'),
	'listFilter' => array('type = ?', ['DATE_CONTROL']),
	'listOrder' => 'ORDER BY `key` ',
	'listField' => array(
		'id' => '60',
		'key|type' => '15%',
		'tmp.startDatetime|tmp.endDatetime|value' => '20%',
		'tmp.beforeMessage|tmp.afterMessage|tmp.nowMessage|remark' => '40%',
	),
	'fieldConfig' => array(
		'key' => array('readonly' => !Auth::userInRole('SUPER')),
		'type' => array('label' => false, 'value' => 'DATE_CONTROL', 'readonly' => true),
		'value' => array('format' => 'hidden', 'label' => false),
		'remark' => array('format' => 'hidden', 'label' => false),
		'tmp.startDatetime' => array('label' => 'Start', 'format' => 'datetime', 'inline-label' => '<small class="d-block" style="width: 30px;"><b>START</b></small>'),
		'tmp.endDatetime' => array('label' => 'End', 'format' => 'datetime', 'inline-label' => '<small class="d-block" style="width: 30px;"><b>END</b></small>'),
		'tmp.beforeMessage' => array('label' => 'Messages', 'inline-label' => '<small class="d-block" style="width: 40px;"><b>BEFORE</b></small>'),
		'tmp.afterMessage' => array('label' => false, 'inline-label' => '<small class="d-block" style="width: 40px;"><b>AFTER</b></small>'),
		'tmp.nowMessage' => array('label' => false, 'inline-label' => '<small class="d-block" style="width: 40px;"><b>NOW</b></small>'),
	),
	'scriptPath' => array(
		'row' => F::appPath('view/date_control/row.php'),
//		'inline_edit' => F::appPath('view/date_control/inline_edit.php'),
	),
	'writeLog' => class_exists('Log'),
], $dateControlScaffold ?? $date_control_scaffold ?? []);


// component
include F::appPath('controller/scaffold_controller.php');