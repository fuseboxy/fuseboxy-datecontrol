<?php /*
<fusedoc>
	<io>
		<in>
			<object name="$bean" type="enum">
				<string name="type" value="DATE_CONTROL" />
				<string name="key" example="mainland-appfee" />
				<string name="value" comments="start and end" />
				<string name="remark" comments="messages" />
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
$doc->find('div.col-tmp-startDatetime')->html( DateControl::get($bean->key, 'start') );
$doc->find('div.col-tmp-endDatetime')->html( DateControl::get($bean->key, 'end') );


// messages
foreach ( ['before','after','now'] as $msgType ) :
	$msg = $doc->find('div.col-tmp-'.$msgType.'Message');
	$msg->removeClass('small')->removeClass('text-muted');
	$msg->html( DateControl::message($bean->key, $msgType) );
	$msg->prepend('<span class="badge badge-light b-1 small" style="width: 50px;">'.strtoupper($msgType).'</span> ');
endforeach;


// highlight active rows
if ( DateControl::isActive($bean->key) ) $doc->find('.scaffold-row')->addClass('table-warning');


// display
echo $doc;