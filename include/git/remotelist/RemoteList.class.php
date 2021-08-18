<?php
/**
 * Class representing a list of heads
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Git\RemoteList
 */
class GitPHP_RemoteList extends GitPHP_RefList
{
	/**
	 * Data load strategy
	 *
	 * @var GitPHP_RemoteListLoadStrategy_Interface
	 */
	protected $strategy;

	/**
	 * Constructor
	 *
	 * @param GitPHP_Project $project project
	 * @param GitPHP_RemoteListLoadStrategy_Interface $strategy load strategy
	 */
	public function __construct($project, GitPHP_RemoteListLoadStrategy_Interface $strategy)
	{
		parent::__construct($project);

		if (!$strategy)
			throw new Exception('Head list load strategy is required');

		$this->SetStrategy($strategy);
	}

	/**
	 * Set the load strategy
	 *
	 * @param GitPHP_RemoteListLoadStrategy_Interface $strategy load strategy
	 */
	public function SetStrategy(GitPHP_RemoteListLoadStrategy_Interface $strategy)
	{
		if (!$strategy)
			return;

		$this->strategy = $strategy;
	}

	/**
	 * Gets the heads
	 *
	 * @return GitPHP_Head[] array of heads
	 */
	public function GetRemotes()
	{
		if (!$this->dataLoaded)
			$this->LoadData();

		$heads = array();

		foreach ($this->refs as $head => $hash) {
			$heads[] = $this->project->GetObjectManager()->GetRemote($head, $hash);
		}

		return $heads;
	}

	/**
	 * Gets heads that point to a commit
	 *
	 * @param GitPHP_Commit $commit commit
	 * @return GitPHP_Head[] array of heads
	 */
	public function GetCommitRemotes($commit)
	{
		if (!$commit)
			return array();

		$commitHash = $commit->GetHash();

		if (!$this->dataLoaded)
			$this->LoadData();

		$heads = array();

		foreach ($this->refs as $head => $hash) {
			if ($commitHash == $hash) {
				$heads[] = $this->project->GetObjectManager()->GetRemote($head, $hash);
			}
		}

		return $heads;
	}

	/**
	 * Load head data
	 */
	protected function LoadData()
	{
		$this->dataLoaded = true;

		$this->refs = $this->strategy->Load($this);
	}

	/**
	 * Gets a head
	 *
	 * @param string $head head
	 */
	public function GetRemote($head)
	{
		if (empty($head))
			return null;

		if (!$this->dataLoaded)
			$this->LoadData();

		if (!isset($this->refs[$head]))
			return;

		return $this->project->GetObjectManager()->GetRemote($head, $this->refs[$head]);
	}

	/**
	 * Gets heads in a specific order
	 *
	 * @param string $order order to use
	 * @param int $count limit the number of results
	 * @return GitPHP_Head[] array of heads
	 */
	public function GetOrderedRemotes($order, $count = 0)
	{
		return $this->strategy->LoadOrdered($this, $order, $count);
	}

	/**
	 * Returns the current head (overrides base)
	 *
	 * @return GitPHP_Head
	 */
	function current()
	{
		if (!$this->dataLoaded) {
			$this->LoadData();
		}

		return $this->project->GetObjectManager()->GetRemote(key($this->refs), current($this->refs));
	}

}
