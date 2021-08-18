<?php
/**
 * Head list load strategy using git exe
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Git\RemoteList
 */
class GitPHP_RemoteListLoad_Git extends GitPHP_RefListLoad_Git implements GitPHP_RemoteListLoadStrategy_Interface
{
	/**
	 * Loads the head list
	 *
	 * @param GitPHP_RemoteList $RemoteList head list
	 * @return array array of head hashes
	 */
	public function Load($RemoteList)
	{
		$data = $this->GetRefs($RemoteList, 'remotes');
		return $data[0];
	}

	/** 
	 * Loads sorted heads
	 *
	 * @param GitPHP_RemoteList $RemoteList head list
	 * @param string $order list order
	 * @param integer $count number to load
	 * @param integer $skip number to skip
	 */
	public function LoadOrdered($RemoteList, $order, $count = 0, $skip = 0)
	{
		if (!$RemoteList)
			return;

		$ordered = $this->GetOrderedRefs($RemoteList, 'remotes', $order, $count, $skip);

		if (!$ordered)
			return;

		$headObjs = array();
		foreach ($ordered as $head) {
			if ($RemoteList->Exists($head)) {
				$headObjs[] = $RemoteList->GetRemote($head);
			}
		}

		return $headObjs;
	}
}
