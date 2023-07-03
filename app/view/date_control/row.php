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
foreach ( ['start','end'] as $periodType ) :
	$doc->find('div.col-tmp-'.$periodType.'Datetime')->html( DateControl::get($bean->key, $periodType) );
endforeach;


// messages
foreach ( $localeAll as $lang ) :
	$langSuffix = ( $lang == 'en' ) ? '' : ('__'.str_replace('-', '_', $lang));
	foreach ( ['before','after','now'] as $msgType ) :
		$msg = $doc->find('div.col-tmp-'.$msgType.'Message'.$langSuffix);
		$msg->removeClass('small')->removeClass('text-muted');
		I18N::set($lang);
		$msg->html( DateControl::message($bean->key, $msgType) );
		I18N::reset();
		$msg->prepend('<span class="badge badge-light b-1 small" style="width: 50px;">'.strtoupper($msgType).'</span> ');
	endforeach;
endforeach;


// separater for message of different languages
$doc->find('div[class*=col-tmp-beforeMessage__]')->before('<div>--</div>');


// highlight when active
if ( DateControl::isActive($bean->key) ) $doc->find('.scaffold-row')->addClass('table-warning');


// display
echo $doc;