<?php /*
<fusedoc>
	<io>
		<in>
			<object name="$bean" type="enum">
				<string name="type" value="DATE_CONTROL" />
				<string name="key" example="enrol-UG-Y1" />
				<string name="value|value__{lang}" />
				<string name="remark|remark__{lang}" />
			</object>
		<in>
		<out />
	</io>
</fusedoc>
*/
// multi-language (when necessary)
$localeAll = class_exists('I18N') ? I18N::localeAll() : ['en'];


// capture original output
ob_start();
include F::appPath('view/scaffold/row.php');
$doc = Util::phpQuery(ob_get_clean());


// start & end
$doc->find('div.col-tmp-startDatetime')->html( DateControl::get($bean->key, 'start') );
$doc->find('div.col-tmp-endDatetime')->html( DateControl::get($bean->key, 'end') );


// messages
foreach ( $localeAll as $lang ) :
	foreach ( ['before','after','now'] as $msgType ) :
		$msg = $doc->find('div.col-tmp-'.$msgType.'Message');
		$msg->removeClass('small')->removeClass('text-muted');
		$msg->html( DateControl::message($bean->key, $msgType) );
		$msg->prepend('<span class="badge badge-light b-1 small" style="width: 50px;">'.strtoupper($msgType).'</span> ');
	endforeach;
endforeach;


// separater for message of different languages
$doc->find('div[class*=col-tmp-beforeMessage__]')->before('<div>--</div>');


// highlight when active
if ( DateControl::isActive($bean->key) ) $doc->find('.scaffold-row')->addClass('table-warning');


// display
echo $doc;