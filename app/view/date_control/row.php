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
// capture original output
ob_start();
include F::appPath('view/scaffold/row.php');
$doc = Util::phpQuery(ob_get_clean());


// start & end
// ===> parse from raw data instead of using DateControl methods
// ===> because DateControl methods can only obtain non-disabled records
$tmpPeriod = explode('|', $bean->value);
$doc->find('div.col-tmp-startDatetime')->html($tmpPeriod[0] ?? '');
$doc->find('div.col-tmp-endDatetime')->html($tmpPeriod[1] ?? '');


// messages (could be multi-languages)
// ===> parse from raw data instead of using DateControl methods
// ===> because DateControl methods can only obtain non-disabled records
$localeAll = class_exists('I18N') ? I18N::localeAll() : ['en'];
foreach ( $localeAll as $lang ) :
	// determine field suffix
	if ( $lang == 'en' ) $langSuffix = '';
	else $langSuffix = '__'.str_replace('-', '_', $lang);
	// display message
	$remarkField = 'remark'.$langSuffix;
	$tmpMessage = explode("\n", $bean->{$remarkField} ?? $bean->remark);
	$doc->find('div.col-tmp-beforeMessage'.$langSuffix)->html($tmpMessage[0] ?? '');
	$doc->find('div.col-tmp-afterMessage'.$langSuffix)->html($tmpMessage[1] ?? '');
	$doc->find('div.col-tmp-nowMessage'.$langSuffix)->html($tmpMessage[2] ?? '');
	// display badge & adjust style
	foreach ( ['before','after','now'] as $msgType ) :
		$badge = '<span class="badge badge-light b-1 small mr-1" style="width: 50px;">'.strtoupper($msgType).'</span>';
		$doc->find('div.col-tmp-'.$msgType.'Message'.$langSuffix)->prepend($badge)->removeClass('text-muted')->removeClass('small');
	endforeach;
endforeach;


// separater for message of different languages
$doc->find('div[class*=col-tmp-beforeMessage__]')->before('<div>--</div>');


// highlight or dim (when active or closed)
try {
	if ( DateControl::isActive($bean->key) ) $doc->find('.scaffold-row')->addClass('alert-primary');
	elseif ( DateControl::isEnded($bean->key) ) $doc->find('.scaffold-row')->addClass('text-muted');
} catch (Exception $e) {
	F::error($e->getMessage());
}


// display
echo $doc;