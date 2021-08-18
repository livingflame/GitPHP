<?php
/**
 * Head list load strategy using raw objects
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Git\RemoteList
 */
class GitPHP_RemoteListLoad_Raw extends GitPHP_RefListLoad_Raw implements GitPHP_RemoteListLoadStrategy_Interface
{
	/**
	 * Loads the head list
	 *
	 * @param GitPHP_RemoteList $RemoteList head list
	 * @return array array of head hashes
	 */
	public function Load($RemoteList)
	{
		return $this->GetRefs($RemoteList, 'remotes');
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

		if (empty($order))
			return;

		$heads = $RemoteList->GetRemotes();

		/* TODO add different orders */
		if ($order == '-committerdate') {
			usort($heads, array('GitPHP_Remote', 'CompareAge'));
		}

		if ((($count > 0) && (count($heads) > $count)) || ($skip > 0)) {
			if ($count > 0)
				$heads = array_slice($heads, $skip, $count);
			else
				$heads = array_slice($heads, $skip);
		}

		return $heads;
	}
}
