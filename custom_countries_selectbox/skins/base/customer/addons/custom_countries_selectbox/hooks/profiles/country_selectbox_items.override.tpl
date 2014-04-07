		<option value="">- {$lang.select_country} -</option>
{if $addons.custom_countries_selectbox.selectbox_style != 'alphabetical'}
		{assign var="formatted_countries" value=$countries|fn_custom_countries_selectbox_format_countries}
		{assign var="pop_countries" value=$formatted_countries.popular}
		{assign var="alp_countries" value=$formatted_countries.alphabetical}

		<optgroup label="{$lang.ccs_most_popular}">
			{foreach from=$pop_countries item=country}
			<option {if $_country == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{$lang.ccs_alphabetical}">
{else}
			{assign var="alp_countries" value=$countries}
{/if}
	
			{foreach from=$alp_countries item=country}
			<option {if $_country == $country.code}selected="selected"{/if} value="{$country.code}">{$country.country}</option>
			{/foreach}

{if $addons.custom_countries_selectbox.selectbox_style != 'alphabetical'}
		</optgroup>
{/if}





