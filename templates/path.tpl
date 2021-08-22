{*
 * Path
 *
 * Path template
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Template
 *}
<div class="page_path">
	{if isset($pathobject)}
		{assign var=pathobjectcommit value=$pathobject->GetCommit()}
		{assign var=pathobjecttree value=$pathobjectcommit->GetTree()}
		/ 
		{foreach from=$pathobject->GetPathTree() item=pathtreepiece}
			<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathtreepiece file=$pathtreepiece->GetPath()}"><strong>{$pathtreepiece->GetName()|escape}</strong></a> / 
		{/foreach}
		{if $pathobject instanceof GitPHP_Blob}
			{if $target == 'blobplain'}
				<a href="{geturl project=$project action=blob hash=$pathobject file=$pathobject->GetPath() output=plain}">{$pathobject->GetName()|escape}</a>
			{elseif $target == 'blob'}
				<a href="{geturl project=$project action=blob hash=$pathobject hashbase=$pathobjectcommit file=$pathobject->GetPath()}">{$pathobject->GetName()|escape}</a>
			{else}
				{$pathobject->GetName()|escape}
			{/if}
		{elseif $pathobject->GetName()}
			{if $target == 'tree'}
				<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathobject file=$pathobject->GetPath()}">{$pathobject->GetName()|escape}</a> / 
			{else}
				<strong>{$pathobject->GetName()|escape}</strong> / 
			{/if}
		{/if}
	{elseif isset($titlecommit)}
		{if $target == 'commitdiff'}
<a href="{geturl project=$project action=commitdiff hash=$titlecommit}" class="no_text_decore">{$titlecommit->GetTitle()|escape}</a>
		{elseif $target == 'tree'}
			<a href="{geturl project=$project action=tree hash=$titletree hashbase=$titlecommit}"  class="no_text_decore">{$titlecommit->GetTitle()|escape}</a>
		{elseif $target == 'summary'}
			{if $branch}
			{t}repo branch{/t}: <b>{$branch}</b>
			{/if}
			<a href="{geturl project=$project action=heads}">Summary</a>
		{else}
			<a href="{geturl project=$project action=commit hash=$titlecommit}"  class="no_text_decore">{$titlecommit->GetTitle()|escape}</a>
		{/if}
		{include file='refbadges.tpl' commit=$titlecommit}
		<a href="{geturl project=$project action=snapshot hash=$titlecommit}" class="snapshotTip">{t}snapshot{/t}</a>
		<span class="timestamp">{time()|date_format:"%a, %d %b %Y %H:%M"} {date('T')}</span>
	{else}
		&nbsp;
	{/if}

</div>
