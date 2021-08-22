{*
 *  tree.tpl
 *  gitphp: A PHP git repository browser
 *  Component: Tree view template
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
{if file_exists('asset/js/blob.min.js')}
require.paths.blob = "blob.min";
{/if}
{if $ace_mode == 'ace/mode/markdown'}
deps.push('showdown');
{else}
deps.push('ace');
{/if}

require.deps = deps;
{/block}
{block name=javascript}
require.deps = ['tree'];
{if file_exists('asset/js/tree.min.js')}
require.paths.tree = "tree.min";
{/if}
{/block}

{block name=main}

 {* Nav *}
   <div class="page_nav">
     {include file='nav.tpl' current='tree' logcommit=$commit}
   </div>
 {include file='title.tpl' pathobject=$tree target='blobplain'}
{include file='path.tpl' titlecommit=$commit target='blobplain'}
 
 <div class="page_body">
   {* List files *}
<table class="treeTable">
     {include file='treelist.tpl'}
</table>
{if $blob}
{include file='title.tpl' pathobject=$blob target='blob'}
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
 </div>

{/block}
