{*
 *  blob.tpl
 *  gitphp: A PHP git repository browser
 *  Component: Blob view template
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
   {include file='nav.tpl' treecommit=$commit}
   <br />
   {if isset($head) && ($commit->GetHash() != $head->GetHash()) && ($tree->PathToHash($blob->GetPath()))}
     <a href="{geturl project=$project action=blob hashbase=HEAD file=$blob->GetPath()}">{t}HEAD{/t}</a> | 
   {else}
     {t}HEAD{/t} | 
   {/if}
   <a href="{geturl project=$project action=blob hash=$blob file=$blob->GetPath() output=plain}">{t}plain{/t}</a> | 
   {if $blob->GetPath()}
   <a href="{geturl project=$project action=history hash=$commit file=$blob->GetPath()}">{t}history{/t}</a>
   {if $is_text} | <a href="{geturl project=$project action=blame hash=$blob file=$blob->GetPath() hashbase=$commit}" id="blameLink">{t}blame{/t}</a>{/if}
   {/if}
   <br />
 </div>

 {include file='title.tpl' titlecommit=$commit}

{include file='path.tpl' pathobject=$blob target='blobplain'}

 <div class="page_body">
	{if $is_audio}
	<p><audio src="{geturl project=$project action=blob output=plain hash=$blob file=$blob->GetPath() hashbase=$commit}" controls preload="metadata"></audio></p>
	{elseif $is_video}
	<div class="preview-video"><video src="{geturl project=$project action=blob output=plain hash=$blob file=$blob->GetPath() hashbase=$commit}" width="640" height="360" controls preload="metadata"></video></div>
	{elseif $picture}
	{* We're trying to display an image *}
    <div class="picture">
      <img class="new" src="{geturl project=$project action=blob output=plain hash=$blob file=$blob->GetPath() hashbase=$commit}" />
    </div>
	{elseif $ace_mode}
   <div class="highlight" data-ace-mode="{$ace_mode}" data-ace-theme="ace/theme/github" data-ace-gutter="true">{$data|e|echobig}</div>
   {elseif $geshi}
     {* We're using the highlighted output from geshi *}
     {$geshiout}
   {else}
     {* Just plain display *}
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
 </div>
{/block}
