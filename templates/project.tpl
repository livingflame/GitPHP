{*
 *  project.tpl
 *  gitphp: A PHP git repository browser
 *  Component: Project summary template
 *
 *  Copyright (C) 2009 Christopher Han <xiphux@gmail.com>
 *}
{extends file='projectbase.tpl'}
{block name=css}
{if $geshicss}
  <style type="text/css">
  {$geshicss}
  </style>
{/if}
{/block}

{block name=javascript}
var deps = [];
deps.push('blob');
{if file_exists('js/blob.min.js')}
require.paths.blob = "blob.min";
{/if}
{if $ace_mode == 'ace/mode/markdown'}
deps.push('showdown');
{else}
deps.push('ace');
{/if}

require.deps = deps;
{/block}
{block name=main}

 <div class="page_nav">
 {include file='nav.tpl' commit=$head current='summary'}
 <br /><br />
 </div>

 {include file='title.tpl'}

 {* Project brief *}
 <table>
   <tr><td>{t}description{/t}</td><td>{$project->GetDescription()|escape}</td></tr>
   <tr><td>{t}owner{/t}</td><td>{$project->GetOwner()|escape:'html'}</td></tr>
   {if $head}
   <tr><td>{t}last change{/t}</td><td><time datetime="{$head->GetCommitterEpoch()|date_format:"%Y-%m-%dT%H:%M:%S+00:00"}">{$head->GetCommitterEpoch()|date_format:"%a, %d %b %Y %H:%M:%S %z"}</time></td></tr>
   {/if}
   {if $project->GetCloneUrl()}
     <tr><td>{t}clone{/t}</td><td>git clone {$project->GetCloneUrl()}</td></tr>
   {/if}
   {if $project->GetPushUrl()}
     <tr><td>{t}push{/t}</td><td>git push {$project->GetPushUrl()}</td></tr>
   {/if}
   {if $project->GetWebsite()}
     <tr><td>{t}website{/t}</td><td><a href="{$project->GetWebsite()}" rel="nofollow">{$project->GetWebsite()}</a></td></tr>
   {/if}
 </table>

 {if !$head}
   {include file='title.tpl' target='shortlog' disablelink=true}
 {else}
   {include file='title.tpl' target='shortlog'}
 {/if}

 {include file='shortloglist.tpl' source='summary'}
 
 {if $taglist}
  
  {include file='title.tpl' target='tags'}

  {include file='taglist.tpl' source=summary}
   
 {/if}

 {if $headlist}

  {include file='title.tpl' target='heads'}

  {include file='headlist.tpl' source=summary}

 {/if}
 {if $remotelist}

  {include file='title.tpl' target='remotes'}

  {include file='remotelist.tpl' source='summary'}

 {/if}
{if $blob}
  {if $ace_mode}
    <div class="highlight" data-ace-mode="{$ace_mode}" data-ace-theme="ace/theme/github" data-ace-gutter="true">{$data|e|echobig}</div>
  {else}
	<table class="code" id="blobData">
		<tbody>
			<tr class="li1">
				<td class="ln">
<pre class="de1">
{foreach from=$bloblines item=line name=bloblines}
<a id="l{$smarty.foreach.bloblines.iteration}" href="#l{$smarty.foreach.bloblines.iteration}" class="linenr">{$smarty.foreach.bloblines.iteration}</a>
{/foreach}
</pre>
				</td>
				<td>
<pre class="de1">
{foreach from=$bloblines item=line name=bloblines}
{$line|escape}
{/foreach}
</pre>
				</td>
			</tr>
		</tbody>
	</table>
  {/if}

{/if}
{/block}
