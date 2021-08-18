<?php
/**
 * Controller for returning raw graph data
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Controller
 */
class GitPHP_Controller_GraphData extends GitPHP_ControllerBase
{
	/**
	 * Initialize controller
	 */
	public function Initialize()
	{
		parent::Initialize();
		
		if (!$this->config->GetValue('graphs')) {
			throw new Exception('Graphing has been disabled');
		}
		
		if (empty($this->params['hash'])){$this->params['hash'] = 'HEAD';}

		$this->preserveWhitespace = true;
		$this->DisableLogging();
	}

	/**
	 * Gets the template for this controller
	 *
	 * @return string template filename
	 */
	protected function GetTemplate()
	{
		return 'graphdata.tpl';
	}

	/**
	 * Gets the cache key for this controller
	 *
	 * @return string cache key
	 */
	protected function GetCacheKey()
	{
		return isset($this->params['graphtype']) ? $this->params['graphtype'] : '';
	}

	/**
	 * Gets the name of this controller's action
	 *
	 * @param boolean $local true if caller wants the localized action name
	 * @return string action name
	 */
	public function GetName($local = false)
	{
		return 'graphdata';
	}

	/**
	 * Loads headers for this template
	 */
	protected function LoadHeaders()
	{
		$this->headers[] = 'Content-Type: application/json';
	}

	/**
	 * Loads data for this template
	 */
	protected function LoadData()
	{
		$head = $this->GetProject()->GetHeadCommit();
		$data = array();

		if ($this->params['graphtype'] == 'commitactivity') {
			$commits = $this->exe->Execute($this->GetProject()->GetPath(), 'rev-list', array('--format=format:"%H %ct"', $head->GetHash()));
			foreach ($commits as $commit) {
				if (preg_match('/^([0-9a-fA-F]{40}) ([0-9]+)$/', $commit, $regs)) {
					$data[] = array('CommitEpoch' => (int)$regs[2]);
				}
			}
		} else if ($this->params['graphtype'] == 'languagedist') {
			$files = $this->exe->Execute($this->GetProject()->GetPath(), 'ls-tree', array('-r', '--name-only', $head->GetTree()->GetHash()));

			foreach ($files as $file) {
				$filename = GitPHP_Util::BaseName($file);
                $file_mime = $this->GetProject()->GetObjectManager()->getFileMime($filename);
				$ace_mode = $file_mime->getAceModeForPath();
				if($ace_mode !== null){
					$lang = $ace_mode['name'];
				} else {
                    $lang = 'Other';
                }

				if (isset($data[$lang])) {
					$data[$lang]++;
				} else {
					$data[$lang] = 1;
				}
			}

		} else if ($this->params['graphtype'] == 'contributors') {
			$arr = array();
			$arr[] = '--pretty=format:"%aN||%aE"';
			$arr[] = $this->params['hash'];
			$logs = $this->exe->Execute($this->GetProject()->GetPath(), 'log', $arr);
			$logs = array_count_values($logs);
			arsort($logs);

			foreach ($logs as $user => $count) {
				$user = explode('||', $user);
				$data[] = array('name' => $user[0], 'email' => $user[1], 'commits' => $count);
			}

		}else if ($this->params['graphtype'] == 'branching') {
			$arr = array();
			$arr[] = '--graph';
			$arr[] = '--date-order';
			$arr[] = '--all';
			$arr[] = '-C';
			$arr[] = '-M';
			$arr[] = '-n';
			$arr[] = '100';
			$arr[] = '--date=iso';
			$arr[] = '--pretty=format:"B[%d] C[%H] D[%ad] A[%an] E[%ae] H[%h] S[%s]"';
			$rawRows = $this->exe->Execute($this->GetProject()->GetPath(), 'log', $arr);

			foreach ($rawRows as $row) {
				if (preg_match("/^(.+?)(\s(B\[(.*?)\])? C\[(.+?)\] D\[(.+?)\] A\[(.+?)\] E\[(.+?)\] H\[(.+?)\] S\[(.+?)\])?$/", $row, $output)) {
					if (!isset($output[4])) {
						$data[] = array(
							'relation' => $output[1],
						);
						continue;
					}
					$data[] = array(
						'relation' => $output[1],
						'branch' => $output[4],
						'rev' => $output[5],
						'date' => $output[6],
						'author' => $output[7],
						'author_email' => $output[8],
						'short_rev' => $output[9],
						'subject' => preg_replace('/(^|\s)(#[[:xdigit:]]+)(\s|$)/', '$1<a href="$2">$2</a>$3', $output[10]),
					);
				}
			}
		}

		$this->tpl->assign('data', json_encode($data));
	}

}
