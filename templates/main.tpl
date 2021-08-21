{*
 * Main
 *
 * Main page template
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2011 Christopher Han
 * @packge GitPHP
 * @subpackage Template
 *}
<!DOCTYPE html>
<html lang="{$currentprimarylocale}">
  <!-- gitphp web interface {$version}, (C) 2006-2011 Christopher Han <xiphux@gmail.com> -->
  <head>
    <title>{block name=title}{$pagetitle}{/block}</title>
    {block name=feeds}{/block}
    {block name=links}{/block}
    {if file_exists('css/gitphp.min.css')}
    <link type="text/css" rel="stylesheet" href="{$baseurl}/assets/css/gitphp.min.css" />
    {else}
    <link type="text/css" rel="stylesheet" href="{$baseurl}/assets/css/gitphp.css" />
    {/if}
    {if file_exists("assets/css/$stylesheet.min.css")}
    <link type="text/css" rel="stylesheet" href="{$baseurl}/assets/css/{$stylesheet}.min.css" />
    {else}
    <link type="text/css" rel="stylesheet" href="{$baseurl}/assets/css/{$stylesheet}.css" />
    {/if}
    <link type="text/css" rel="stylesheet" href="{$baseurl}/assets/css/ext/jquery.qtip.min.css" />
    <link type="text/css" rel="stylesheet" href="{$baseurl}/assets/css/all.min.css" />
    {block name=css}
    {/block}

    {if $javascript}
    <script type="text/javascript">
    var require = {
    	baseUrl: '{$baseurl}/assets/js',
	paths: {
		jquery: [
			{if $googlejs}
			'https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min',
			{/if}
			'ext/jquery-1.8.2.min'
		],
		d3: 'ext/d3.v2.min',
		qtip: 'ext/jquery.qtip.min',
		modernizr: 'ext/modernizr.custom',
        ace : "ace"
	},
	config: {
		'modules/snapshotformats': {
			formats: {
				{foreach from=$snapshotformats key=format item=extension name=formats}
				"{$format}": "{$extension}"{if !$smarty.foreach.formats.last},{/if}
				{/foreach}
			}
		},
		{if $project}
		'modules/getproject': {
			project: '{$project->GetProject()}'
		},
		{/if}
		'modules/geturl': {
			baseurl: '{$baseurl}/'
		},
		'modules/resources': {
			resources: {
				Loading: "{t escape='js'}Loading…{/t}",
				LoadingBlameData: "{t escape='js'}Loading blame data…{/t}",
				Snapshot: "{t escape='js'}snapshot{/t}",
				NoMatchesFound: '{t escape=no}No matches found for "%1"{/t}',
        UsernameLabel: "{t escape='js'}username:{/t}",
        PasswordLabel: "{t escape='js'}password:{/t}",
        Login: "{t escape='js'}login{/t}",
        AnErrorOccurredWhileLoggingIn: "{t escape='js'}An error occurred while logging in{/t}",
        LoginTitle: "{t escape='js'}Login{/t}",
        UsernameIsRequired: "{t escape='js'}Username is required{/t}",
        PasswordIsRequired: "{t escape='js'}Password is required{/t}"
			}
		}
	}
    };
    {block name=javascript}
      {if file_exists('assets/js/common.min.js')}
      require.paths.common = 'common.min';
      {/if}
      require.deps = ['common'];
    {/block}
    </script>
    <script type="text/javascript" src="{$baseurl}/assets/js/ext/require.js"></script>
    {/if}
  </head>
  <body>
    <div class="page_header">
      {if $loginenabled}
      <div class="login">
      {if $loggedinuser}
        <a href="{geturl action=logout}" /><i class="fa fa-sign-out" aria-hidden="true"></i> {t 1=$loggedinuser}logout{/t}</a>
      {else if $action == 'login'}
        {t}login{/t}
      {else}
        <a href="{geturl action=login}" class="loginLink" /><i class="fa fa-sign-in" aria-hidden="true"></i> {t}login{/t}</a>
      {/if}
      </div>
      {/if}
      {block name=header}
      <a href="{geturl}">{if $homelink}{$homelink}{else}{t}projects{/t}{/if}</a> /
      {/block}
    </div>
{block name=main}

{/block}
    <div class="page_footer">
        {if $supportedlocales}
        <div class="lang_select">
            <form action="{$requesturl}" method="get" id="frmLangSelect">
                {foreach from=$requestvars key=var item=val}
                {if $var != "l"}
                <input type="hidden" name="{$var}" value="{$val|escape}" />
                {/if}
                {/foreach}
                <label for="selLang">{t}language:{/t}</label>
                <select name="l" id="selLang">
                    {foreach from=$supportedlocales key=locale item=language}
                    <option {if $locale == $currentlocale}selected="selected"{/if} value="{$locale}">{if $language}{$language} ({$locale}){else}{$locale}{/if}</option>
                    {/foreach}
                </select>
                <input type="submit" value="{t}set{/t}" id="btnLangSet" />
            </form>
        </div>
        {/if}
		<div class="attr_footer">
			<a href="http://www.gitphp.org/" target="_blank">GitPHP by Chris Han</a>
		</div>
      {block name=footer}
      {/block}
    </div>
  </body>
</html>
