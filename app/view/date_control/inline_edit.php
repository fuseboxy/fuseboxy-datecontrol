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


// feed value to corresponding temp fields
foreach ( ['start','end'] as $periodType ) :
	$doc->find("input[name='data[tmp][{$periodType}Datetime]']")->val( DateControl::get($bean->key, $periodType) );
endforeach;
foreach ( ['before','after','now'] as $msgType ) :
	foreach ( $localeAll as $lang ) :
		$suffix = ( $lang == 'en' ) ? '' : ('__'.str_replace('-', '_', $lang));
		$doc->find("input[name='data[tmp][{$msgType}Message]']")->val( DateControl::message($bean->key, $msgType) );
	endforeach;
endforeach;

/*
// determine period by value
$arr = isset($bean->value) ? array_filter(explode('|', $bean->value)) : [];
$period = array('start' => $arr[0] ?? '', 'end' => $arr[1] ?? '');


// always start & never end
foreach ( $period as $fieldKey => $fieldValue ) :
	ob_start();
	?><div class="input-group input-group-sm">
		<div class="input-group-prepend">
			<span class="input-group-text">
				<small class="d-block" style="width: 30px;">
					<small><b><?php echo strtoupper($fieldKey); ?></b></small>
				</small>
			</span>
		</div>
		<input 
			type="text"
			name="tmp[<?php echo $fieldKey; ?>]"
			value="<?php echo $fieldValue; ?>"
			class="form-control scaffold-input-datetime"
			autocomplete="off"
			<?php if ( $fieldValue == '*' ) echo 'readonly'; ?>
			onchange="
				// sync values into hidden field
				var startDate = $(this.form).find('input[name^=tmp][name*=start]').val();
				var endDate   = $(this.form).find('input[name^=tmp][name*=end]').val();
				$(this.form).find('div.col-value input.form-control').val(startDate+'|'+endDate);
			"
		><div class="input-group-append">
			<label class="input-group-text cursor-pointer">
				<input 
					type="checkbox"
					<?php if ( $fieldValue == '*' ) echo 'checked'; ?>
					onclick="
						// toggle date field & trigger event
						var $checkbox = $(this);
						var $dateField = $(this).closest('.input-group').find('input.form-control');
						$dateField.val($checkbox.is(':checked') ? '*' : '').attr('readonly', $checkbox.is(':checked')).change();
					"
				/>
				<small class="d-inline-block text-left text-muted ml-1" style="width: 60px;">
					<em><?php echo ( $fieldKey == 'start' ) ? 'Always open' : 'Never close'; ?></em>
				</small>
			</label>
		</div>
	</div><?php
	$doc->find("div.col-{$fieldKey} > .scaffold-input")->html(ob_get_clean());
endforeach;
*/

// display
echo $doc;