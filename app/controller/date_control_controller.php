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
$scaffold = array(
	'beanType' => 'enum',
	'editMode' => 'inline',
	'allowDelete' => Auth::userInRole('SUPER'),
	'layoutPath' => F::appPath('view/global/layout.php'),
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
		'value' => array('format' => 'hidden', 'label' => false),
		'start' => array('format' => 'output'),
		'end' => array('format' => 'output'),
	], $remarkFields),
	'scriptPath' => array(
		'row' => F::appPath('view/date_control/row.php'),
		'inline_edit' => F::appPath('view/date_control/inline_edit.php'),
	),
	'writeLog' => class_exists('Log'),
);


// component
include F::appPath('controller/scaffold_controller.php');