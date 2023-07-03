<?php /*
<fusedoc>
	<io>
		<in>
			<object name="$bean" type="enum">
				<string name="type" value="DATE_CONTROL" />
				<string name="key" example="mainland-appfee" />
				<string name="value|value__{lang}" />
				<string name="remark|remark__{lang}" />
			</object>
			<array name="$localeAll">
				<string name="~lang~" example="en|zh-hk|zh-cn|.." />
			</array>
		<in>
		<out>
			<structure name="data" scope="form">
				<string name="type" />
				<string name="key" />
				<list name="value" />
				<string name="remark" />
			</structure>
		</out>
	</io>
</fusedoc>
*/
// multi-language (when necessary)
$localeAll = class_exists('I18N') ? I18N::localeAll() : ['en'];


// capture original output
ob_start();
include F::appPath('view/scaffold/inline_edit.php');
$doc = Util::phpQuery(ob_get_clean());


// put value to temp datetime fields
foreach ( ['start','end'] as $periodType ) :
	$field = $doc->find("input[name='data[tmp][{$periodType}Datetime]']");
	$fieldValue = DateControl::get($bean->key, $periodType);
	$field->val($fieldValue);
	if ( $fieldValue == '*' ) $field->attr('readonly', true);
endforeach;


// put value to temp message fields
foreach ( $localeAll as $lang ) :
	$langSuffix = ( $lang == 'en' ) ? '' : ('__'.str_replace('-', '_', $lang));
	foreach ( ['before','after','now'] as $msgType ) :
		$field = $doc->find("input[name='data[tmp][{$msgType}Message{$langSuffix}]']");
		I18N::set($lang);
		$field->val( DateControl::message($bean->key, $msgType) );
		I18N::reset();
	endforeach;
endforeach;


// always start & never end
foreach ( ['start','end'] as $periodType ) :
	$fieldContainer = $doc->find("div.col-tmp-{$periodType}Datetime > .scaffold-input > .input-group");
	// remove calendar icon
	$fieldContainer->find('.input-group-append')->remove();
	// append checkbox to field
	ob_start();
	?><div class="input-group-append bl-1">
		<label class="input-group-text cursor-pointer">
			<input 
				type="checkbox"
				<?php if ( DateControl::get($bean->key, $periodType) == '*' ) echo 'checked'; ?>
				onclick="
					// toggle date field & trigger event
					var $checkbox = $(this);
					var $dateField = $(this).closest('.input-group').find('input.form-control');
					$dateField.val($checkbox.is(':checked') ? '*' : '').attr('readonly', $checkbox.is(':checked')).change();
				"
			/>
			<small class="d-inline-block text-left text-muted ml-1" style="width: 60px;">
				<em><?php echo ( $periodType == 'start' ) ? 'Always open' : 'Never closed'; ?></em>
			</small>
		</label>
	</div><?php
	$fieldContainer->append(ob_get_clean());
endforeach;


// display
echo $doc;