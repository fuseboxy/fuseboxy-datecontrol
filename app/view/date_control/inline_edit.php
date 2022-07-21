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
// capture original output
ob_start();
include F::appPath('view/scaffold/inline_edit.php');
$doc = Util::phpQuery(ob_get_clean());


// determine period by value
$arr = isset($bean->value) ? array_filter(explode('|', $bean->value)) : [];
$period = array('start' => $arr[0] ?? '', 'end' => $arr[1] ?? '');


// start date & end date
// ===> update hidden [value] field as pipe-delim list
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
			required
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


// display
echo $doc;