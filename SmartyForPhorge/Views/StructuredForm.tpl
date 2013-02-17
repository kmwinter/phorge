<div class="structured-form">

<form name="{$form_name|default:_form}" action="{$form_action}" method="{$form_method|default:POST}">
	{foreach from=$hidden_inputs item=input}
		<input type="hidden" name="{$input.name}" value="{$input.value}"/>
	{/foreach}
	<table border="0" cellpadding="2" cellspacing="2" width=100%>
	{if $form_title}
		<tr>
			<td colspan="2" class="structured-form-title">
			{$form_title}
			</td>
		</tr>
	{/if}
	{foreach from=$inputs item=input}
		{if $input.type == "heading"}
			<tr>
				<td colspan="2" class="structured-form-heading">
					{$input.label}
				</td>
			</tr>	
		{elseif $input.type == "error"}
				<tr>
				<td colspan="2" class="structured-form-error">
					{$input.label}
				</td>
			</tr>	
		{elseif $input.type == "spamfilter"}						
			<tr>
				<td class="structured-assurance" colspan="2">
					Assurance: <input name="{$input.name}">
				</td>
			</tr>
			
		{elseif $input.type == "textarea"}
			<tr>
				<td colspan="2" class="structured-form-label">
					{if $input.required}
						<span class="structured-form-required">*</span>
					{/if}
					{$input.label}
					{if $input.error}
						<div class="structured-form-error">{$input.error}</div>
					{/if}
				</td>
			</tr>
			<tr>
				<td colspan="2" class="structured-form-input">
					<textarea name="{$input.name}" {if $input.rows}rows="{$input.rows}"{/if} {if $input.cols}cols="{$input.cols}"{/if}>{$input.value}</textarea> 
				</td>
			</tr>
		{elseif	$input.type == "fckeditor"}
			<tr>
				<td colspan="2" class="structured-form-label">
					{if $input.required}
						<span class="structured-form-required">*</span>
					{/if}
					{$input.label}
					{if $input.error}
						<div class="structured-form-error">{$input.error}</div>
					{/if}
				</td>
			</tr>
			<tr>
				<td colspan="2" class="structured-form-input">
					{fckeditor InstanceName=$input.name Height='400' Value=$input.value}
					
				</td>
			</tr>								
		{else}
			<tr>
				<td class="structured-form-label" width="100">
				{if $input.required}
					<span class="structured-form-required">*</span>
				{/if}
				{$input.label}
				{if $form_errors[$input.name]}
					<div class="structured-form-error">{$form_errors[$input.name]}</div>
				{/if}
				</td>
				<td class="structured-form-input">
				
				{if $input.type == "select"}
					<select name="{$input.name}">
					{foreach from=$input.options item=oLabel key=oValue}					
						<option value="{$oValue}" {if $oValue == $input.value}selected{/if}>{$oLabel}</option>																	
					{/foreach}
					</select>
				{elseif $input.type == "checkbox" OR $input.type == "radio"}
					{foreach from=$input.options item=oLabel key=oValue}						
						<input type="{$input.type}" name="{$input.name}" value="{$oValue}" {if $oValue == $input.value}checked{/if} /> {$oLabel}
					{/foreach}
				{else}									
					<input type="text" name="{$input.name}" value="{$input.value}"  {if $input.size}suize="{$input.size}"{/if} {if $input.maxlength}maxlength="{$input.maxLength}"{/if} />	
				{/if}
				</td>
			</tr>
		{/if}
	{/foreach}
	<tr>
		<td colspan="2" class="structured-form-buttons" align="right">
		{foreach from=$buttons item=button}
			<input type="{$button.type}" {if $button.name}name="{$button.name}"{/if} value="{$button.label}">	
		{/foreach}
		</td>
	</tr>
	</table>
</form>
</div>
