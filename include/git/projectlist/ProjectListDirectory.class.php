<?php
/**
 * Lists all projects in a given directory
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Git\ProjectList
 */
class GitPHP_ProjectListDirectory extends GitPHP_ProjectListBase
{

	/**
	 * Whether to only list exported projects
	 *
	 * @var boolean
	 */
	protected $exportedOnly = false;
    
	/**
	 * Ignore working git repositories (project/.git)
	 */
	protected $bareOnly = true;

	/**
	 * Go inside android source .repo folders
	 */
	protected $repoSupport = false;

	/**
	 * Search in subfolders, maximum recursive level
	 */
	protected $sublevels = 0;

	/**
	 * Search in subfolders, current recursive level
	 */
	protected $curlevel = 0;

	/**
	 * Stores the config object
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param string $projectRoot project root
	 * @param object $config object
	 */
	public function __construct($config)
	{
		$this->config    = $config;
        parent::__construct($config->GetValue('projectroot'));
	}

	/**
	 * Gets whether this list only allows exported projects
	 *
	 * @return boolean
	 */
	public function GetExportedOnly()
	{
		return $this->exportedOnly;
	}
	
	/**
	 * Populates the internal list of projects
	 */
	protected function PopulateProjects()
	{
        $this->exportedOnly = $this->config->GetValue('exportedonly', true);
		$this->bareOnly    = $this->config->GetValue('bareonly', true);
		$this->sublevels   = $this->config->GetValue('subfolder_levels', 0);
		$this->repoSupport = $this->config->GetValue('reposupport', false);
        $this->curlevel = 0;
		$this->RecurseDir(GitPHP_Util::AddSlash($this->projectRoot));
	}

	/**
	 * Recursively searches for projects
	 *
	 * @param string $dir directory to recurse into
	 */
	private function RecurseDir($dir)
	{
		if (!GitPHP_Util::IsDir($dir))
			return;

		$this->Log(sprintf('Searching directory %1$s', $dir));

		if (!GitPHP_Util::IsWindows() && (fileperms($dir) & 0111) == 0) {
			$this->Log(sprintf('Folder %1$s is protected... mode %2$o', $dir, fileperms($dir)));
		}

		if ($dh = opendir($dir)) {
			$trimlen = strlen(GitPHP_Util::AddSlash($this->projectRoot)) + 1;
			while (($file = readdir($dh)) !== false) {
				$fullPath = $dir . '/' . $file;
				if (!GitPHP_Util::IsDir($fullPath) || $file == '.' || $file == '..')
					continue;
				elseif ($this->repoSupport && $file == '.repo')
					; // check subfolders
				elseif (substr($file,-4) != '.git') {
					// working copy repositories (git clone)
					if ( !$this->bareOnly && GitPHP_Util::IsDir($fullPath . '/.git') )
						$fullPath .= '/.git';
					elseif ($this->curlevel >= $this->sublevels or substr($file,0,1) == '.')
						continue;
				}

				// check +x access on .git folder
				if (!GitPHP_Util::IsWindows() && (fileperms($fullPath.'/.') & 0111) == 0) {
					$this->Log(sprintf('Folder %1$s is protected... mode %2$o',
						$fullPath, fileperms($fullPath)));
				}

				if (is_file($fullPath . '/HEAD') || is_file($fullPath . '/ORIG_HEAD')) {
					$projectPath = substr($fullPath, $trimlen);
					if (!isset($this->projects[$projectPath])) {
						$this->Log(sprintf('Found project %1$s', $projectPath));
						$project = $this->LoadProject($projectPath);
						if ($project) {

							$category = trim(substr($dir, $trimlen), '/');
							$project->SetCategory($category);

							$this->projects[$projectPath] = $project;
							unset($project);
						}
					}
				} elseif ($this->curlevel < $this->sublevels) {
					$this->curlevel++;
					$this->RecurseDir($fullPath);
					$this->curlevel--;
				} else {
					$this->Log(sprintf('Skipping %1$s', $fullPath));
				}
			}
			closedir($dh);
		}
	}

	/**
	 * Loads a project
	 *
	 * @param string $proj project
	 * @return GitPHP_Project project
	 */
	protected function LoadProject($proj)
	{
		try {

			$project = new GitPHP_Project($this->config, $proj);

			$category = trim(dirname($proj));
			if (!(empty($category) || (strpos($category, '.') === 0))) {
				$project->SetCategory($category);
			}

			if ($this->exportedOnly && !$project->GetDaemonEnabled()) {
				$this->Log(sprintf('Project %1$s not enabled for export', $project->GetPath()));
				return null;
			}

			$this->ApplyGlobalConfig($project);

			$this->ApplyGitConfig($project);

			if ($this->projectSettings && isset($this->projectSettings[$proj])) {
				$this->ApplyProjectSettings($project, $this->projectSettings[$proj]);
			}

			$this->InjectProjectDependencies($project);

			return $project;

		} catch (Exception $e) {
			$this->Log($e->getMessage());
		}

		return null;
	}

}
