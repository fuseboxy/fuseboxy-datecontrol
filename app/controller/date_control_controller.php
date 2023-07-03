<?php
F::redirect('auth&callback='.base64_encode($_SERVER['REQUEST_URI']), !Auth::user());
F::error('Forbidden', !Auth::userInRole('SUPER,ADMIN'));


// message fields in different languages
$remarkFields = array_merge(...array_map(function($lang){
	$suffix = ( $lang == 'en' ) ? '' : ('__'.str_replace('-', '_', $lang));
	return array(
		'tmp.beforeMessage'.$suffix => array('label' => ( $lang == 'en' ) ? 'Messages' : '', 'inline-label' => '<small class="d-block" style="width: 40px;"><b>BEFORE</b></small>', 'pre-help' => ( $lang == 'en' ) ? '' : "<b class='text-info fa-sm'>{$lang}</b>" ),
		'tmp.afterMessage'.$suffix => array('label' => false, 'inline-label' => '<small class="d-block" style="width: 40px;"><b>AFTER</b></small>'),
		'tmp.nowMessage'.$suffix => array('label' => false, 'inline-label' => '<small class="d-block" style="width: 40px;"><b>NOW</b></small>'),
		'remark'.$suffix => array('format' => 'hidden', 'label' => false),
	);
}, class_exists('I18N') ? I18N::localeAll() : array('en')));


// config
$scaffold = array_merge([
	'beanType' => 'enum',
	'editMode' => 'inline',
	'allowDelete' => Auth::userInRole('SUPER'),
	'allowSort' => false,
	'layoutPath' => F::appPath('view/global/layout.php'),
	'listFilter' => array('type = ?', ['DATE_CONTROL']),
	'listOrder' => 'ORDER BY `key` ',
	'listField' => array(
		'id' => '60',
		'key|type' => '15%',
		'tmp.startDatetime|tmp.endDatetime|value' => '20%',
		implode('|', array_keys($remarkFields)) => '40%',
	),
	'fieldConfig' => array_merge([
		'key' => array('readonly' => !Auth::userInRole('SUPER')),
		'type' => array('label' => false, 'value' => 'DATE_CONTROL', 'readonly' => true),
		'value' => array('format' => 'hidden', 'label' => false),
		'tmp.startDatetime' => array('label' => 'Start', 'format' => 'datetime', 'inline-label' => '<small class="d-block" style="width: 30px;"><b>START</b></small>'),
		'tmp.endDatetime' => array('label' => 'End', 'format' => 'datetime', 'inline-label' => '<small class="d-block" style="width: 30px;"><b>END</b></small>'),
	], $remarkFields),
	'scriptPath' => array(
		'row' => F::appPath('view/date_control/row.php'),
//		'inline_edit' => F::appPath('view/date_control/inline_edit.php'),
	),
	'writeLog' => class_exists('Log'),
], $dateControlScaffold ?? $date_control_scaffold ?? []);


// component
include F::appPath('controller/scaffold_controller.php');