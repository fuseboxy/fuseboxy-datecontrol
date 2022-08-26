<?php /*
<fusedoc>
	<io>
		<in>
			<object name="$bean" type="enum">
				<string name="type" value="DATE_CONTROL" />
				<string name="key" example="mainland-appfee" />
				<list name="value" delim="|">
					<string name="0" comments="start date" />
					<string name="1" comments="end date" />
				</list>
				<string name="remark" />
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


// start date & end date
$period = isset($bean->value) ? array_filter(explode('|', $bean->value)) : [];
$doc->find('div.col-start')->html($period[0] ?? '');
$doc->find('div.col-end')->html($period[1] ?? '');


// highlight active rows
if ( DateControl::isActive($bean->key) ) $doc->find('.scaffold-row')->addClass('table-warning');


// display
echo $doc;