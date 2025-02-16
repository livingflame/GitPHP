<?php
/**
 * Controller for displaying a project summary
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Controller
 */
class GitPHP_Controller_Project extends GitPHP_ControllerBase
{

	/**
	 * Gets the template for this controller
	 *
	 * @return string template filename
	 */
	protected function GetTemplate()
	{
		return 'project.tpl';
	}

	/**
	 * Gets the cache key for this controller
	 *
	 * @return string cache key
	 */
	protected function GetCacheKey()
	{
		return '';
	}

	/**
	 * Gets the name of this controller's action
	 *
	 * @param boolean $local true if caller wants the localized action name
	 * @return string action name
	 */
	public function GetName($local = false)
	{
		if ($local && $this->resource) {
			return $this->resource->translate('summary');
		}
		return 'summary';
	}

	/**
	 * Loads data for this template
	 */
	protected function LoadData()
	{

		$head = $this->GetProject()->GetHeadCommit();
		$this->tpl->assign('head', $head);
		if (!$head)
			$this->tpl->assign('enablesearch', false);

		$compat = $this->GetProject()->GetCompat();
		$strategy = null;
		//if ($compat) {
			$strategy = new GitPHP_LogLoad_Git($this->exe);
		//} else {
		//	$strategy = new GitPHP_LogLoad_Raw();
		//}
		$revlist = new GitPHP_Log($this->GetProject(), $this->GetProject()->GetHeadCommit(), $strategy, 17);

		if ($revlist->GetCount() > 16) {
			$this->tpl->assign('hasmorerevs', true);
			$revlist->SetLimit(16);
		}
		$this->tpl->assign('revlist', $revlist);

		$taglist = $this->GetProject()->GetTagList()->GetOrderedTags('-creatordate', 17);
		if ($taglist) {
			if (count($taglist) > 16) {
				$this->tpl->assign('hasmoretags', true);
				$taglist = array_slice($taglist, 0, 16);
			}
			$this->tpl->assign('taglist', $taglist);
		}

		$headlist = $this->GetProject()->GetHeadList()->GetOrderedHeads('-committerdate', 17);
		if ($headlist) {
			if (count($headlist) > 17) {
				$this->tpl->assign('hasmoreheads', true);
				$headlist = array_slice($headlist, 0, 16);
			}
			$this->tpl->assign('headlist', $headlist);
		}

		$remotelist = $this->GetProject()->GetRemotes(10);
		if ($remotelist) {
			if (count($remotelist) > 9) {
				$this->tpl->assign('hasmoreremotes', true);
				$remotelist = array_slice($remotelist, 0, 9);
			}
		}
        $this->tpl->assign('remotelist', $remotelist);

		$tree = $head->GetTree();
		$this->tpl->assign('tree', $tree);
		$hash = null;
		$file = null;
		$files = array(
			'readme.md',
			'README.md',
			'README.txt',
			'readme.txt',
			'README',
			'readme'
		);
		foreach($files as $file_name){
			
			if($tree->PathToHash($file_name)){
				$hash = $tree->PathToHash($file_name);
				$file = $file_name;
				break;
			}
		}
		
		$blob = null;
		if($hash){
			
			$blob = $this->GetProject()->GetObjectManager()->GetBlob($hash);
			
			if (!empty($file)){
				$blob->SetPath($file);
			}

			$blob->SetCommit($head);

			$file_mime = $this->GetProject()->GetObjectManager()->getFileMime($blob);

			$ace_mode_name = null;
			$ace_mode_mode = null;
			$ace_mode = $file_mime->getAceModeForPath();
			if($ace_mode !== null){
				$ace_mode_name = $ace_mode['name'];
				$ace_mode_mode = $ace_mode['mode'];
			}
			if($ace_mode_name === null){
				$ace_mode_name = "text";
				$ace_mode_mode = "ace/mode/text";
			}
			$this->tpl->assign('ace_name', $ace_mode_name);
			$this->tpl->assign('ace_mode', $ace_mode_mode);
			$this->tpl->assign('data', $blob->GetData());
			$this->tpl->assign('bloblines', hex_dump($blob->GetData()));		
		}
		$this->tpl->assign('blob', $blob);
		$this->tpl->assign('file', $file);
	}

}
