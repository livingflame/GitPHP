{*
 * Title
 *
 * Title template
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Template
 *}

<div class="title">
	{if isset($titlecommit)}
		{if $target == 'commitdiff'}
			<a href="{geturl project=$project action=commitdiff hash=$titlecommit}" class="title">{$titlecommit->GetTitle()|escape}</a>
		{elseif $target == 'tree'}
			<a href="{geturl project=$project action=tree hash=$titletree hashbase=$titlecommit}" class="title">tree</a>
		{elseif $target == 'summary'}
			{if $branch}
			{t}repo branch{/t}: <b>{$branch}</b>
			{/if}
			<a href="{geturl project=$project action=heads}" class="title">Summary</a>
		{else}
			<a href="{geturl project=$project action=commit hash=$titlecommit}" class="title">{$titlecommit->GetTitle()|escape}</a>
		{/if}
		{include file='refbadges.tpl' commit=$titlecommit}
		<a href="{geturl project=$project action=snapshot hash=$titlecommit}" class="snapshotTip">{t}snapshot{/t}</a>
	{elseif isset($pathobject)}		
		{assign var=pathobjectcommit value=$pathobject->GetCommit()}
		{assign var=pathobjecttree value=$pathobjectcommit->GetTree()}
		<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathobjecttree}"><strong>[{$project->GetProject()}]</strong></a> / 
		{foreach from=$pathobject->GetPathTree() item=pathtreepiece}
			<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathtreepiece file=$pathtreepiece->GetPath()}"><strong>{$pathtreepiece->GetName()|escape}</strong></a> / 
		{/foreach}
		{if $pathobject instanceof GitPHP_Blob}
			{if $target == 'blobplain'}
				<a href="{geturl project=$project action=blob hash=$pathobject file=$pathobject->GetPath() output=plain}"><strong>{$pathobject->GetName()|escape}</strong></a>
			{elseif $target == 'blob'}
				<a href="{geturl project=$project action=blob hash=$pathobject hashbase=$pathobjectcommit file=$pathobject->GetPath()}"><strong>{$pathobject->GetName()|escape}</strong></a>
			{else}
				<strong>{$pathobject->GetName()|escape}</strong>
			{/if}
		{elseif $pathobject->GetName()}
			{if $target == 'tree'}
				<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathobject file=$pathobject->GetPath()}"><strong>{$pathobject->GetName()|escape}</strong></a> / 
			{else}
				<strong>{$pathobject->GetName()|escape}</strong> / 
			{/if}
		{/if}

	{else}
		{if $target == 'summary'}
			<a href="{geturl project=$project}" class="title">Summary</a>
		{elseif $target == 'shortlog'}
			{if $disablelink}
			  {t}shortlog{/t}
			{else}
			  <a href="{geturl project=$project action=shortlog}" class="title">{t}shortlog{/t}</a>
			{/if}
		{elseif $target == 'tags'}
			{if $disablelink}
			  {t}tags{/t}
			{else}
			  <a href="{geturl project=$project action=tags}" class="title">{t}tags{/t}</a>
			{/if}
		{elseif $target == 'heads'}
			{if $disablelink}
			  {t}branches{/t}
			{else}
			  <a href="{geturl project=$project action=heads}" class="title">{t}branches{/t}</a>
			{/if}
		{elseif $target == 'remotes'}
			{if $disablelink}
			  {t}heads{/t}
			{else}
			  <a href="{geturl project=$project action=remotes}" class="title">{t}remote heads{/t}</a>
			{/if}
		{elseif $target == 'blob' || $target == 'blobplain'}
			{if $pathobject}
				{assign var=pathobjectcommit value=$pathobject->GetCommit()}
				{assign var=pathobjecttree value=$pathobjectcommit->GetTree()}
				{foreach from=$pathobject->GetPathTree() item=pathtreepiece}
					<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathtreepiece file=$pathtreepiece->GetPath()}"><strong>{$pathtreepiece->GetName()|escape}</strong></a> / 
				{/foreach}
				{if $pathobject instanceof GitPHP_Blob}
					{if $target == 'blobplain'}
						<a href="{geturl project=$project action=blob hash=$pathobject file=$pathobject->GetPath() output=plain}"><strong>{$pathobject->GetName()|escape}</strong></a>
					{elseif $target == 'blob'}
						<a href="{geturl project=$project action=blob hash=$pathobject hashbase=$pathobjectcommit file=$pathobject->GetPath()}"><strong>{$pathobject->GetName()|escape}</strong></a>
					{else}
						<strong>{$pathobject->GetName()|escape}</strong>
					{/if}
				{elseif $pathobject->GetName()}
					{if $target == 'tree'}
						<a href="{geturl project=$project action=tree hashbase=$pathobjectcommit hash=$pathobject file=$pathobject->GetPath()}"><strong>{$pathobject->GetName()|escape}</strong></a> / 
					{else}
						<strong>{$pathobject->GetName()|escape}</strong> / 
					{/if}
				{/if}
			{else}
				&nbsp;
			{/if}
		{elseif isset($target)}
			{$target}
		{else}
			&nbsp;
		{/if}
	{/if}
</div>
