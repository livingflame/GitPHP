<?php

/**
 * git cat-file constant
 */
define('GIT_CAT_FILE','cat-file');

/**
 * git diff-tree constant
 */
define('GIT_DIFF_TREE','diff-tree');

/**
 * git ls-tree constant
 */
define('GIT_LS_TREE','ls-tree');

/**
 * git rev-list constant
 */
define('GIT_REV_LIST','rev-list');

/**
 * git rev-parse constant
 */
define('GIT_REV_PARSE','rev-parse');

/**
 * git show-ref constant
 */
define('GIT_SHOW_REF','show-ref');

/**
 * git archive constant
 */
define('GIT_ARCHIVE','archive');

/**
 * git grep constant
 */
define('GIT_GREP','grep');

/**
 * git blame constant
 */
define('GIT_BLAME','blame');

/**
 * git name-rev constant
 */
define('GIT_NAME_REV','name-rev');

/**
 * git for-each-ref constant
 */
define('GIT_FOR_EACH_REF','for-each-ref');

/**
 * Class to wrap git executable
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2010 Christopher Han
 * @package GitPHP
 * @subpackage Git
 */
class GitPHP_GitExe implements GitPHP_Observable_Interface
{

	/**
	 * The binary path
	 *
	 * @var string
	 */
	protected $binary;
	
	/**
	 * The binary version
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Whether the version has been read
	 *
	 * @var boolean
	 */
	protected $versionRead = false;

	/**
	 * Prefix to set terminal LANG charset.
	 */
	protected $charset = 'en_US.utf-8';
	/**
	 * Observers
	 *
	 * @var GitPHP_Observer_Interface[]
	 */
	protected $observers = array();

	/**
	 * Whether the exec function is allowed by the install
	 *
	 * @var null|boolean
	 */
	protected $execAllowed = null;

	/**
	 * Whether the shell_exec function is allowed by the install
	 *
	 * @var null|boolean
	 */
	protected $shellExecAllowed = null;

	/**
	 * Whether the popen function is allowed by the install
	 *
	 * @var null|boolean
	 */
	protected $popenAllowed = null;

	/**
	 * Constructor
	 *
	 * @param string $binary path to git binary
	 */
	public function __construct($binary = '')
	{
		if (empty($binary)) {
			$binary = GitPHP_GitExe::DefaultBinary();
		}
		$this->binary = escapeshellarg(GitPHP_Util::CleanPath($binary));
	}

	/**
	 * Executes a command
	 *
	 * @param string $projectPath path to project
	 * @param string $command the command to execute
	 * @param string[] $args arguments
	 * @return string result of command
	 */
	public function Execute($projectPath, $command, $args)
	{
		if ($this->execAllowed === null) {
			$this->execAllowed = GitPHP_Util::FunctionAllowed('exec');
			if (!$this->execAllowed) {
				throw new GitPHP_DisabledFunctionException('exec');
			}
		}

		$fullCommand = $this->CreateCommand($projectPath, $command, $args);
		file_put_contents( GITPHP_BASEDIR . 'logs.txt', $fullCommand . "\n", FILE_APPEND );
		$this->Log('Begin executing "' . $fullCommand . '"');
		$ret = exec($fullCommand,$output);
		$ret = null;
		$this->Log('Finish executing "' . $fullCommand . '"' .
			"\nwith result: " . implode('\n',$output));

		return $output;
	}

	/**
	 * Executes a command
	 *
	 * @param string $projectPath path to project
	 * @param string $command the command to execute
	 * @param string[] $args arguments
	 * @return string result of command
	 */
	public function ShellExecute($projectPath, $command, $args)
	{
		if ($this->shellExecAllowed === null) {
			$this->shellExecAllowed = GitPHP_Util::FunctionAllowed('shell_exec');
			if (!$this->shellExecAllowed) {
				throw new GitPHP_DisabledFunctionException('shell_exec');
			}
		}

		$fullCommand = $this->CreateCommand($projectPath, $command, $args);
		file_put_contents( GITPHP_BASEDIR . 'logs.txt', $fullCommand . "\n", FILE_APPEND );
		$this->Log('Begin executing "' . $fullCommand . '"');
		$ret = shell_exec($fullCommand);
		$this->Log('Finish executing "' . $fullCommand . '"' . "\nwith result: " . $ret);
		return $ret;
	}

	/**
	 * Opens a resource to a command
	 *
	 * @param string $projectPath path to project
	 * @param string $command the command to execute
	 * @param string[] $args arguments
	 * @param string $mode process open mode
	 * @return resource process handle
	 */
	public function Open($projectPath, $command, $args, $mode = 'r+')
	{
		$string = implode("\n",$this->Execute($projectPath, $command, $args));
		$stream = fopen('php://temp',$mode);
		fwrite($stream, $string);
		rewind($stream);
		return $stream;
	}

	/**
	 * Creates a command
	 *
	 * @param string $projectPath path to project
	 * @param string $command the command to execute
	 * @param array $args arguments
	 * @return string result of command
	 */
	protected function CreateCommand($projectPath, $command, $args)
	{
		$gitDir = '';
		if (!empty($projectPath)) {
			$gitDir = '--git-dir=' . escapeshellarg(GitPHP_Util::CleanPath($projectPath));
		}

		$command = $this->binary . ' ' . $gitDir . ' ' . $command . ' ' . implode(' ', $args);

		if (!empty($this->charset) && !GitPHP_Util::IsWindows()) {
			$command = 'LANG='.$this->charset . ' ' . $command;
		}

		return $command;
	}
	/**
	 * Gets the binary for this executable
	 *
	 * @return string binary
	 */
	public function GetBinary()
	{
		return $this->binary;
	}

	/**
	 * Gets the version of the git binary
	 *
	 * @return string version
	 */
	public function GetVersion()
	{
		if (!$this->versionRead)
			$this->ReadVersion();

		return $this->version;
	}

	/**
	 * Reads the git version
	 */
	protected function ReadVersion()
	{
		if ($this->shellExecAllowed === null) {
			$this->shellExecAllowed = GitPHP_Util::FunctionAllowed('shell_exec');
			if (!$this->shellExecAllowed) {
				throw new GitPHP_DisabledFunctionException('shell_exec');
			}
		}

		$this->versionRead = true;

		$this->version = '';

		$versionCommand = $this->binary . ' --version';
		$ret = trim(shell_exec($versionCommand));
		if (preg_match('/^git version ([0-9\.]+)$/i', $ret, $regs)) {
			$this->version = $regs[1];
		}
	}

	/**
	 * Tests if this version of git can skip through the revision list
	 *
	 * @return boolean true if we can skip
	 */
	public function CanSkip()
	{
		$version = $this->GetVersion();
		if (!empty($version)) {
			$splitver = explode('.', $version);

			/* Skip only appears in git >= 1.5.0 */
			if (($splitver[0] < 1) || (($splitver[0] == 1) && ($splitver[1] < 5))) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Tests if this version of git can show the size of a blob when listing a tree
	 *
	 * @return true if we can show sizes
	 */
	public function CanShowSizeInTree()
	{
		$version = $this->GetVersion();
		if (!empty($version)) {
			$splitver = explode('.', $version);

			/*
			 * ls-tree -l only appears in git 1.5.3
			 * (technically 1.5.3-rc0 but i'm not getting that fancy)
			 */
			if (($splitver[0] < 1) || (($splitver[0] == 1) && ($splitver[1] < 5)) || (($splitver[0] == 1) && ($splitver[1] == 5) && ($splitver[2] < 3))) {
				return false;
			}
		}

		return true;

	}

	/**
	 * Tests if this version of git has the regexp tuning option to ignore regexp case
	 *
	 * @return true if we can ignore regexp case
	 */
	public function CanIgnoreRegexpCase()
	{
		$version = $this->GetVersion();
		if (!empty($version)) {
			$splitver = explode('.', $version);

			/*
			 * regexp-ignore-case only appears in git 1.5.3
			 */
			if (($splitver[0] < 1) || (($splitver[0] == 1) && ($splitver[1] < 5)) || (($splitver[0] == 1) && ($splitver[1] == 5) && ($splitver[2] < 3))) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Tests if this executable is valid
	 *
	 * @return boolean true if valid
	 */
	public function Valid()
	{
		if ($this->execAllowed === null) {
			$this->execAllowed = GitPHP_Util::FunctionAllowed('exec');
			if (!$this->execAllowed) {
				throw new GitPHP_DisabledFunctionException('exec');
			}
		}

		if (empty($this->binary))
			return false;

		$code = 0;
		$out = exec($this->binary . ' --version', $tmp, $code);

		return $code == 0;
	}

	/**
	 * Add a new observer
	 *
	 * @param GitPHP_Observer_Interface $observer observer
	 */
	public function AddObserver($observer)
	{
		if (!$observer)
			return;

		if (array_search($observer, $this->observers) !== false)
			return;

		$this->observers[] = $observer;
	}

	/**
	 * Remove an observer
	 *
	 * @param GitPHP_Observer_Interface $observer observer
	 */
	public function RemoveObserver($observer)
	{
		if (!$observer)
			return;

		$key = array_search($observer, $this->observers);

		if ($key === false)
			return;

		unset($this->observers[$key]);
	}

	/**
	 * Log an execution
	 *
	 * @param string $message message
	 */
	private function Log($message)
	{
		if (empty($message))
			return;

		foreach ($this->observers as $observer) {
			$observer->ObjectChanged($this, GitPHP_Observer_Interface::LoggableChange, array($message));
		}
	}

	/**
	 * Gets the default binary for the platform
	 *
	 * @return string binary
	 */
	public static function DefaultBinary()
	{
		if (GitPHP_Util::IsWindows()) {
			// windows

			if (GitPHP_Util::Is64Bit()) {
				// match x86_64 and x64 (64 bit)
				// C:\Program Files (x86)\Git\bin\git.exe
				return 'C:\\Progra~2\\Git\\bin\\git.exe';
			} else {
				// 32 bit
				// C:\Program Files\Git\bin\git.exe
				return 'C:\\Progra~1\\Git\\bin\\git.exe';
			}
		} else {
			// *nix, just use PATH
			return 'git';
		}
	}

}
