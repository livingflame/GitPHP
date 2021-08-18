<?php
/**
 * Controller for getting a snapshot
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Controller
 */
class GitPHP_Controller_Snapshot extends GitPHP_ControllerBase
{

	/**
	 * Stores the archive object
	 *
	 * @var GitPHP_Archive
	 */
	private $archive = null;

	/**
	 * Snapshot cache directory
	 *
	 * @var string
	 */
	private $cacheDir = null;

	/**
	 * Snapshot cached file path
	 *
	 * @var string
	 */
	private $cachedFile;

	/**
	 * Initialize controller
	 */
	public function Initialize()
	{
		$this->InitializeConfig();

		$this->InitializeUserList();

		$this->InitializeGitExe();

		$this->InitializeProjectList();

		if (isset($this->params['project'])) {
			$project = $this->projectList->GetProject($this->params['project']);
			if (!$project) {
				throw new GitPHP_InvalidProjectParameterException($this->params['project']);
			}
			if ($this->userList && ($this->userList->GetCount() > 0)) {
				if (!$project->UserCanAccess((!empty($_SESSION['gitphpuser']) ? $_SESSION['gitphpuser'] : null))) {
					throw new GitPHP_UnauthorizedProjectException($this->params['project']);
				}
			}
			$this->project = $project->GetProject();
		}

		if (!$this->project) {
			throw new GitPHP_MissingProjectParameterException();
		}

		$this->preserveWhitespace = true;

		if (empty($this->params['format']))
			$this->params['format'] = $this->config->GetValue('compressformat');

		
		$this->cacheDir = GITPHP_CACHEDIR . 'snapshots/';

		if (file_exists($this->cacheDir)) {
			if (!is_dir($this->cacheDir)) {
				throw new Exception($this->cacheDir . ' exists but is not a directory');
			} else if (!is_writable($this->cacheDir)) {
				throw new Exception($this->cacheDir . ' is not writable');
			}
		} else {
			if (!mkdir($this->cacheDir, 0777,true))
				throw new Exception($this->cacheDir . ' could not be created');
			chmod($this->cacheDir, 0777);
		}
		
		$this->InitializeArchive();
	}

	/**
	 * Gets the template for this controller
	 *
	 * @return string template filename
	 */
	protected function GetTemplate()
	{
	}

	/**
	 * Gets the cache key for this controller
	 *
	 * @return string cache key
	 */
	protected function GetCacheKey()
	{
		return (isset($this->params['hash']) ? $this->params['hash'] : '') . '|' . (isset($this->params['file']) ? $this->params['file'] : '') . '|' . (isset($this->params['prefix']) ? $this->params['prefix'] : '') . '|' . $this->params['format'];
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
			return $this->resource->translate('snapshot');
		}
		return 'snapshot';
	}

	/**
	 * Loads headers for this template
	 */
	protected function LoadHeaders()
	{
		$mimetype = $this->archive->GetMimeType();
		
		if (!empty($mimetype))
			$this->headers[] = 'Content-Type: ' . $mimetype;

		$this->headers[] = 'Content-Disposition: attachment; filename=' . $this->archive->GetFilename();
		$cachedfile = $this->cacheDir . $this->CachedSnapshotKey() . $this->archive->GetFilename();
		if (is_readable($cachedfile)) {
			$size = filesize($cachedfile);
			if ($size !== false)
				$this->headers[] = 'Content-Length: ' . $size;
		}
		
	}

	/**
	 * Loads data for this template
	 */
	protected function LoadData()
	{
	}

	/**
	 * Render this controller
	 */
	public function Render()
	{
		$this->archive->Open();
		$file = GitPHP_Util::CleanPath($this->cacheDir . $this->CachedSnapshotKey() . $this->archive->GetFilename());
		if(file_exists($file)){
			$handle = @fopen($file, "r") or die("Couldn't get handle");
			if ($handle) {
				while (!feof($handle)) {
					echo fgets($handle, 4096);
					ob_flush();
					flush();
				}
				fclose($handle);
			}
		}

	}

	/**
	 * Initialize archive for reading
	 */
	private function InitializeArchive()
	{
		$strategy = null;
		if ($this->params['format'] == GITPHP_COMPRESS_TAR) {
			$strategy = new GitPHP_Archive_Tar();
		} else if ($this->params['format'] == GITPHP_COMPRESS_GZ) {
			$strategy = new GitPHP_Archive_Gzip($this->config->GetValue('compresslevel'));
			if (!$strategy->Valid())
				$strategy = new GitPHP_Archive_Tar();
		} else if ($this->params['format'] == GITPHP_COMPRESS_ZIP) {
			$strategy = new GitPHP_Archive_Zip($this->config->GetValue('compresslevel'));
			if (!$strategy->Valid())
				$strategy = new GitPHP_Archive_Tar();
		}
		$strategy->SetExe($this->exe);

		$this->archive = new GitPHP_Archive($this->GetProject(), null, $strategy);
		$this->archive->SetPath(isset($this->params['file']) ? $this->params['file'] : '');
		$this->archive->SetPrefix((isset($this->params['prefix']) ? $this->params['prefix'] : ''));


		if (!isset($this->params['hash']))
			$commit = $this->GetProject()->GetHeadCommit();
		else
			$commit = $this->GetProject()->GetCommit($this->params['hash']);

		$this->archive->SetObject($commit);
		$this->archive->SetCacheDir(GitPHP_Util::CleanPath($this->cacheDir . $this->CachedSnapshotKey()));
		$commit = null;
	}

	/**
	 * Gets the cached snapshot file name
	 *
	 * @return string cached file name
	 */
	private function CachedSnapshotKey()
	{
		$key = ($this->archive->GetObject() ? $this->archive->GetObject()->GetHash() : '') . '|' . (isset($this->params['file']) ? $this->params['file'] : '') . '|' . (isset($this->params['prefix']) ? $this->params['prefix'] : '');
		return sha1($key);
	}

}
