<?php
/**
 * Lists all projects in an .repo manifest.xml file
 *
 * @author Tanguy Pruvot
 * @package GitPHP
 * @subpackage Git\ProjectList
 */
class GitPHP_ProjectListManifest extends GitPHP_ProjectListBase
{
	/**
	 * Stores whether the file has been read
	 */
	protected $fileRead = false;

	/**
	 * Stores the manifest remotes
	 */
	protected $remotes=array();

	/**
	 * Store the manifest default branch/remote
	 */
	protected $default=array();

	/**
	 * .repo/local_manifests folder files
	 */
	protected $local_manifests=array();

	/**
	 * Removed projects from main manifest
	 */
	protected $removes=array();

	/**
	 * Added projects in local manifest
	 */
	protected $local_projects=array();

	/**
	 * constructor
	 *
	 * @param string $projectRoot project root
	 * @param string $projectFile file to read
	 * @throws Exception if parameter is not a readable file
	 */
	public function __construct($projectRoot, $projectFile)
	{
		if (!(is_string($projectFile) && is_file($projectFile))) {
			throw new GitPHP_InvalidFileException($projectFile);
		}

		$this->projectConfig = $projectFile;

		parent::__construct($projectRoot);
	}

	/**
	 * Populates the internal list of projects
	 *
	 * @throws Exception if file cannot be read
	 */
	protected function PopulateProjects()
	{
		if (!$this->fileRead)
			$this->ReadFile();
	}

	/**
	 * Reads the file contents
	 */
	protected function ReadFile($refProject = null)
	{
		$use_errors = libxml_use_internal_errors(true);

		$xml = simplexml_load_file($this->projectConfig);

		libxml_clear_errors();
		libxml_use_internal_errors($use_errors);

		if (!$xml) {
			throw new GitPHP_ProjectListFileReadException($this->projectConfig);
		}

		//remotes list to associative array
		$remotes = array();
		foreach ($xml->remote as $k => $remote) {
			$remoteName = (string) $remote['name'];
			$remotes[$remoteName] = (array) $remote;
			$remotes[$remoteName] = $remotes[$remoteName]['@attributes'];
		}
		$this->remotes = $remotes;

		//projects
		$projects = array();
		foreach ($xml->project as $k => $node) {
			$project = (string) $node['name'];
			$projects[$project] = $node;
		}

		//default branch/tag (revision attribute)
		//        remote (remote attribute)
		$this->default = $xml->default;

		if (isset($this->default['revision'])) {
			// ignore refs/heads/ prefix, not compatible
			$this->default['revision'] = str_replace('refs/heads/','',$this->default['revision']);
		}

		$local_manifests[] = str_replace('/manifest.xml','/local_manifest.xml',$this->projectConfig);
		$local_manif_dir = str_replace('/manifest.xml','/local_manifests/.',$this->projectConfig);
		if (is_dir($local_manif_dir) && ($handle = opendir($local_manif_dir))) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != ".." && strtolower(substr($file, strrpos($file, '.') + 1)) == 'xml')
					$local_manifests[] = $local_manif_dir.'/'.$file;
			}
			closedir($handle);
		}
		foreach ($local_manifests as $local_manifest) {
			if (is_file($local_manifest)) {
				$this->local_manifests[] = $local_manifest;
				if ($this->IncludeLocalManifest($xml)) {

					foreach ($this->removes as $project => $b) {
						if (array_key_exists($project,$projects)) {
							$this->Log(sprintf('remove project %1$s',$project));
							$projects[$project] = NULL;
						}
					}

					foreach ($this->local_projects as $project => $node) {
						$this->Log(sprintf('add local project %1$s',$project));
						$projects[$project] = $node;
					}
				}
			}
		}

		GitPHP_Config::GetInstance()->SetValue('reposupport', true);

		$this->fileRead = true;

		foreach ($projects as $project) {

			//deleted projects
			if (empty($project)) continue;

			$repository = array();
			$repository['path'] = (string) $project['path'];

			$projPath = trim($repository['path']);
			if (empty($projPath)) continue;

			$repository['name'] = (string) $project['name'];
			$repository['revision'] = (empty($project['revision'])) ? (string) $this->default['revision'] : (string) $project['revision'];
			$repository['remote']   = (empty($project['remote'])) ?   (string) $this->default['remote']   : (string) $project['remote'];

			$projPath .= '.git';

			if (!strstr($this->projectRoot,'.repo'))
				$this->projectRoot .= '.repo/projects/';

			$fullPath = $this->projectRoot . $projPath;
			if (!is_file($fullPath . '/HEAD')) {
				$this->Log(sprintf('%1$s: %2$s is not a git project', __FUNCTION__, $projPath));
			} else {
				try {
					$projectPath = substr($fullPath, strlen($this->projectRoot));

					// Allow to apply manifest settings to a single project.
					$projObj = null;
					if (empty($refProject))
						$projObj = $this->LoadProject($projectPath);
					else {
						if ($refProject->GetProject() != $projectPath) continue;
						$projObj = $refProject;
					}

					if ($projObj) {
						$projObj->isAndroidRepo = true;

						$remoteName = $repository['remote'];
						$projObj->repoRemote = $remoteName;

						// project revision can be a tag
						if (strpos($repository['revision'],'/tags/') === false)
							$projObj->repoBranch = $repository['revision'];
						else
							$projObj->repoTag = str_replace('refs/tags/','',$repository['revision']);

						$projOwner = $repository['name'];
						if (!empty($projOwner)) {
							if (strpos($projOwner,'/') > 0)
								$projOwner = substr($projOwner,0,strpos($projOwner,'/'));
							$projObj->SetOwner($projOwner);
							$projObj->SetCategory($remoteName.' - '.$projOwner);
						}

						$projObj->SetDescription($remoteName.':'.$repository['name']);

						//remote url + project name
						$remoteUrl = @ $remotes[$remoteName]['fetch'];
						if (!empty($remoteUrl)) {
							$remoteUrl .= $repository['name'].'.git';
							$projObj->SetCloneUrl($remoteUrl);
						}

						$this->projects[$projPath] = $projObj;
						unset($projObj);
					}

				} catch (Exception $e) {
					$this->Log($e->getMessage());
				}
			}
		}
		$this->Log(sprintf('Found %1$d projects in manifest(s)', count($projects)));
	}

	/**
	 * Loads a project
	 *
	 * @param string $proj project
	 * @return GitPHP_Project project
	 */
	protected function LoadProject($proj)
	{
		$projectObj = new GitPHP_Project($this->projectRoot, $proj);

		$this->ApplyGlobalConfig($projectObj);

		$this->ApplyGitConfig($projectObj);

		// we need to read the xml file if not done... (direct tree link access)
		if (!$this->fileRead) {
			$this->ReadFile($projectObj);
		}

		$this->InjectProjectDependencies($projectObj);

		return $projectObj;
	}

	/**
	 * load the local_manifest.xml if present
	 *
	 * @returns true if done
	 */
	protected function IncludeLocalManifest($main_xml)
	{
		$use_errors = libxml_use_internal_errors(true);

		$xml = simplexml_load_file($this->local_manifest);

		libxml_clear_errors();
		libxml_use_internal_errors($use_errors);

		if (!$xml)
			return false;

		if ($xml->getName() !== 'manifest')
			return false;

		$this->Log(sprintf('Found a local_manifest.xml'));

		//remove-project tags
		$removes = array();
		foreach ($xml->{'remove-project'} as $k => $node) {
			$project = (string) $node['name'];
			$removes[$project] = true;
		}
		$this->removes = $removes;

		//local projects
		$projects = array();
		foreach ($xml->project as $k => $node) {
			$project = (string) $node['name'];
			$projects[$project] = $node;
		}
		$this->local_projects = $projects;

		$this->Log(sprintf('Found %1$d projects in local manifest', count($projects)));

		return true;
	}

	/**
	 * Tests if this file is a valid Manifest file
	 *
	 * @returns true if file is a Manifest
	 */
	public static function IsRepoManifest($file)
	{
		if (empty($file))
			return false;

		if (!(is_string($file) && is_file($file)))
			return false;

		$use_errors = libxml_use_internal_errors(true);

		$xml = simplexml_load_file($file);

		libxml_clear_errors();
		libxml_use_internal_errors($use_errors);

		if (!$xml)
			return false;

		if ($xml->getName() !== 'manifest')
			return false;

		return true;
	}

}
