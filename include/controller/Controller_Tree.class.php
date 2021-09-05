<?php
/**
 * Controller for displaying a tree
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Controller
 */
class GitPHP_Controller_Tree extends GitPHP_ControllerBase
{

	/**
	 * Initialize controller
	 */
	public function Initialize()
	{	
		parent::Initialize();

		if (!isset($this->params['hashbase'])) {
			$this->params['hashbase'] = 'HEAD';
		}

		if (isset($this->params['output']) && ($this->params['output'] == 'js')) {
			$this->DisableLogging();
		}
	}

	/**
	 * Gets the template for this controller
	 *
	 * @return string template filename
	 */
	protected function GetTemplate()
	{
		if (isset($this->params['output']) && ($this->params['output'] == 'js')) {
			return 'treelist.tpl';
		}
		return 'tree.tpl';
	}

	/**
	 * Gets the cache key for this controller
	 *
	 * @return string cache key
	 */
	protected function GetCacheKey()
	{
		return (isset($this->params['hashbase']) ? $this->params['hashbase'] : '') . '|' . (isset($this->params['hash']) ? $this->params['hash'] : '') . '|' . (isset($this->params['file']) ? sha1($this->params['file']) : '');
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
			return $this->resource->translate('tree');
		}
		return 'tree';
	}

	/**
	 * Loads data for this template
	 */
	protected function LoadData()
	{
		if (!isset($this->params['hashbase'])) {
			// TODO: write a lookup for hash (tree) -> hashbase (commithash) and remove this
			throw new Exception('Hashbase is required');
		}

		$commit = $this->GetProject()->GetCommit($this->params['hashbase']);

		$this->tpl->assign('commit', $commit);

		if (!isset($this->params['hash'])) {
			if (isset($this->params['file'])) {
				$this->params['hash'] = $commit->GetTree()->PathToHash($this->params['file']);
				if (empty($this->params['hash']))
					throw new GitPHP_DirectoryNotFoundException($this->params['file']);
			} else {
				$this->params['hash'] = $commit->GetTreeHash();
			}
		}

		$tree = $this->GetProject()->GetObjectManager()->GetTree($this->params['hash']);
		if (!$tree->GetCommit()) {
			$tree->SetCommit($commit);
		}
		if (isset($this->params['file'])) {
			$tree->SetPath($this->params['file']);
		}
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

			$blob->SetCommit($commit);

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
