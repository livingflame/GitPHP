<?php
/**
 * Represents differences between two commit trees
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Git
 */
class GitPHP_TreeDiff implements Iterator
{
	
	/**
	 * The from tree hash
	 *
	 * @var string
	 */
	protected $fromHash;

	/**
	 * The to tree hash
	 *
	 * @var string
	 */
	protected $toHash;

	/**
	 * Whether to detect renames
	 *
	 * @var boolean
	 */
	protected $renames;

	/**
	 * The project
	 *
	 * @var GitPHP_Project
	 */
	protected $project;

	/**
	 * The individual file diffs
	 *
	 * @var GitPHP_FileDiff[]
	 */
	protected $fileDiffs = array();

	/**
	 * Whether data has been read
	 *
	 * @var boolean
	 */
	protected $dataRead = false;

	/**
	 * Used to preview changes size
	 * array("file" => array(added, deleted));
	 */
	protected $fileStat = array();

	/**
	 * Store total of modified lines
	 */
	protected $totalStat = -1;

	/**
	 * Show trailing spaces changes (diff -w)
	 */
	protected $whiteSpaces = true;

	/**
	 * Path filter for the Diff
	 * @var string
	 */
	protected $path = '';

	/**
	 * Executable
	 *
	 * @var GitPHP_GitExe
	 */
	protected $exe;

	/**
	 * Constructor
	 *
	 * @param GitPHP_Project $project project
	 * @param GitPHP_GitExe $exe executable
	 * @param string $toHash to commit hash
	 * @param string $fromHash from commit hash
	 * @param boolean $renames whether to detect file renames
	 */
	public function __construct($project, $exe, $toHash, $fromHash = '', $renames = false)
	{
		if (!$project)
			throw new Exception('Project is required');
		$this->project = $project;

		if (!$exe)
			throw new Exception('Git executable is required');
		$this->exe = $exe;

		$toCommit = $project->GetCommit($toHash);
		$this->toHash = $toHash;

		if (empty($fromHash)) {
			$parent = $toCommit->GetParent();
			if ($parent) {
				$this->fromHash = $parent->GetHash();
			}
		} else {
			$this->fromHash = $fromHash;
		}

		$this->renames = $renames;
	}

	/**
	 * Gets the project
	 *
	 * @return GitPHP_Project project
	 */
	public function GetProject()
	{
		return $this->project;
	}

	/**
	 * Reads the tree diff data
	 */
	private function ReadData()
	{
		if ($this->totalStat == -1)
			$this->GetStats();
		$this->dataRead = true;

		$this->fileDiffs = array();

		$args = array();

		$args[] = '-r';
		if ($this->renames)
			$args[] = '-M';
        $args[] = '-w';
		if (empty($this->fromHash))
			$args[] = '--root';
		else
			$args[] = $this->fromHash;

		$args[] = $this->toHash;
		$diffTreeLines = $this->exe->Execute($this->GetProject()->GetPath(), GIT_DIFF_TREE, $args);
		foreach ($diffTreeLines as $line) {
			$trimmed = trim($line);
			if ((strlen($trimmed) > 0) && (substr_compare($trimmed, ':', 0, 1) === 0)) {
				try {
                    $fileDiff = $this->GetProject()->GetObjectManager()->GetFileDiff($trimmed);
                    $file = $fileDiff->GetFromFile();
                    $mime = $this->GetProject()->GetObjectManager()->getFileMime($file);
                    $fileDiff->isPicture = $mime->isImage();
                    if (!$fileDiff->isPicture) {
						if (isset($this->fileStat[$file])) {
							$arStat = $this->fileStat[$file];
							$fileDiff->totAdd = reset($arStat);
							$fileDiff->totDel = next($arStat);
						}
					}
					$this->fileDiffs[] = $fileDiff;
				} catch (Exception $e) {
				}
			}
		}
	}
	/**
	 * Reads the tree diff --numstat
	 */
	private function GetStats()
	{
		$this->fileStat = array();
		$this->totalStat = 0;

		$args = array();

		$args[] = '--numstat';
		$args[] = '-r';
		if ($this->renames)
			$args[] = '-M';

			$args[] = '-w';

		if (empty($this->fromHash))
			$args[] = '--root';
		else
			$args[] = $this->fromHash;

		$args[] = $this->toHash;

		if (!empty($this->path)) {
			$args[] = '--';
			$args[] = escapeshellarg($this->path);
		}

		//Sample output : (added, deleted, file)
		//14      0       css/gitweb.css
		//0       5       doc/AUTHORS
		//0       124     gitphp.css

		$output = $this->exe->Execute($this->GetProject()->GetPath(), GIT_DIFF_TREE, $args);
		foreach($output as $line){
			if (preg_match('/^(\d+)\s+(\d+)\s+(.*)$/m', $line, $m)) {
				$file = $m[3];
				$add = intval($m[1]);
				$del = intval($m[2]);
				$this->fileStat[$file] = array($add, $del);
				$this->totalStat += $add + $del;
			}
		}

	}
    
	/**
	 * Gets the number of line changes in this treediff
	 *
	 * @return integer count of line changes
	 */
	public function StatCount()
	{
		if ($this->totalStat == -1)
			$this->GetStats();

		return $this->totalStat;
	}
	/**
	 * Gets the from hash for this treediff
	 *
	 * @return string from hash
	 */
	public function GetFromHash()
	{
		return $this->fromHash;
	}

	/**
	 * Gets the to hash for this treediff
	 *
	 * @return string to hash
	 */
	public function GetToHash()
	{
		return $this->toHash;
	}

	/**
	 * Get whether this treediff is set to detect renames
	 *
	 * @return boolean true if renames will be detected
	 */
	public function GetRenames()
	{
		return $this->renames;
	}

	/**
	 * Set whether this treediff is set to detect renames
	 *
	 * @param boolean $renames whether to detect renames
	 */
	public function SetRenames($renames)
	{
		if ($renames == $this->renames)
			return;

		$this->renames = $renames;
		$this->dataRead = false;
	}

	/**
	 * Rewinds the iterator
	 *
	 * @return GitPHP_FileDiff
	 */
	function rewind()
	{
		if (!$this->dataRead)
			$this->ReadData();

		return reset($this->fileDiffs);
	}

	/**
	 * Returns the current element in the array
	 *
	 * @return GitPHP_FileDiff
	 */
	function current()
	{
		if (!$this->dataRead)
			$this->ReadData();

		return current($this->fileDiffs);
	}

	/**
	 * Returns the current key
	 *
	 * @return int
	 */
	function key()
	{
		if (!$this->dataRead)
			$this->ReadData();

		return key($this->fileDiffs);
	}

	/**
	 * Advance the pointer
	 *
	 * @return GitPHP_FileDiff
	 */
	function next()
	{
		if (!$this->dataRead)
			$this->ReadData();

		return next($this->fileDiffs);
	}

	/**
	 * Test for a valid pointer
	 *
	 * @return boolean
	 */
	function valid()
	{
		if (!$this->dataRead)
			$this->ReadData();

		return key($this->fileDiffs) !== null;
	}

	/**
	 * Gets the number of file changes in this treediff
	 *
	 * @return integer count of file changes
	 */
	public function Count()
	{
		if (!$this->dataRead)
			$this->ReadData();

		return count($this->fileDiffs);
	}

}
