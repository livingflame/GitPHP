{*
 * Tree list
 *
 * Tree filelist template fragment
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Template
 *}

{foreach from=$tree->GetContents() item=treeitem}
	{if $treeitem instanceof GitPHP_Tree}
	<tr>
		<td class="col_icon"><i class="fas fa-folder"></i></td>
		<td class="list fileName">
			<a href="{geturl project=$project action=tree hash=$treeitem hashbase=$commit file=$treeitem->GetPath()}" class="treeLink">{$treeitem->GetName()}</a>
		</td>
		<td class="monospace perms">{$treeitem->GetMode()}</td>
		<td class="filesize"></td>
		<td class="link">
			<a href="{geturl project=$project action=history hash=$commit file=$treeitem->GetPath()}">{t}history{/t}</a>
			| 
			<a href="{geturl project=$project action=snapshot hash=$treeitem file=$treeitem->GetPath()}" class="snapshotTip">{t}snapshot{/t}</a>
		</td>
	</tr>
  {/if}
{/foreach}
{foreach from=$tree->GetContents() item=treeitem}
	{if $treeitem instanceof GitPHP_Blob}
	<tr>
		<td class="col_icon"><i class="{$treeitem->GetPath()|getFileIconClass}" aria-hidden="true"></i></td>
		<td class="list fileName">
			<a href="{geturl project=$project action=blob hash=$treeitem hashbase=$commit file=$treeitem->GetPath()}" class="list">{$treeitem->GetName()}</a>
		</td>
		<td class="monospace perms">{$treeitem->GetMode()}</td>
		<td class="filesize">{$treeitem->GetSize()|convertSize}</td>
		<td class="link">
			<a href="{geturl project=$project action=history hash=$commit file=$treeitem->GetPath()}">{t}history{/t}</a>
			| 
			<a href="{geturl project=$project action=blob hash=$treeitem file=$treeitem->GetPath() output=plain}">{t}plain{/t}</a>
		</td>
	</tr>
	{/if}
{/foreach}
