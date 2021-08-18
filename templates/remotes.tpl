{*
 *  remotes.tpl
 *  gitphp: A PHP git repository browser
 *  Component: Remote Head view template
 *}
{extends file='projectbase.tpl'}

{block name=links append}
{if $page > 0}
<link rel="prev" href="{geturl project=$project action=remotes page=$page-1}" />
{/if}
{if $hasmoreheads}
<link rel="next" href="{geturl project=$project action=remotes page=$page+1}" />
{/if}
{/block}

{block name=main}

 {* Nav *}
 <div class="page_nav">
   {include file='nav.tpl' commit=$head treecommit=$head}
   <br />
   {if $page > 0}
     <a href="{geturl project=$project action=remotes}">{t}first{/t}</a>
   {else}
     {t}first{/t}
   {/if}
     &sdot;
   {if $page > 0}
     <a href="{geturl project=$project action=remotes page=$page-1}">{t}prev{/t}</a>
   {else}
     {t}prev{/t}
   {/if}
     &sdot;
   {if $hasmoreheads}
     <a href="{geturl project=$project action=remotes page=$page+1}">{t}next{/t}</a>
   {else}
     {t}next{/t}
   {/if}
 </div>

 {include file='title.tpl' target='remotes'}

 {include file='remotelist.tpl'}

{/block}
