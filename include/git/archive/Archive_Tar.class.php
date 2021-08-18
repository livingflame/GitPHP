<?php
/**
 * Tar archive creation class
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Git\Archive
 */
class GitPHP_Archive_Tar implements GitPHP_ArchiveStrategy_Interface
{
	/**
	 * Executable
	 *
	 * @var GitPHP_GitExe
	 */
	protected $exe;

	/**
	 * Process handle
	 *
	 * @var resource
	 */
	protected $handle;

	/**
	 * Set executable for this archive
	 *
	 * @param GitPHP_GitExe $exe git exe
	 */
	public function SetExe($exe)
	{
		$this->exe = $exe;
	}

	/**
	 * Open a descriptor for this archive
	 *
	 * @param GitPHP_Archive $archive archive
	 * @return boolean true on success
	 */
	public function Open($archive)
	{
		if (!$archive)
			return false;

		if ($this->handle) {
			return true;
		}

		$hash = $archive->GetObject()->GetHash();
		$file = $archive->GetCacheDir() . $archive->GetFilename();
		if(!is_dir(dirname($file))){
			mkdir(dirname($file), 0777, true);
		}

		$args = array();
		$args[] = '--format=tar';
		$args[] = '--output=' . escapeshellarg($file);
		$args[] = $hash;

		return $this->exe->ShellExecute($archive->GetProject()->GetPath(), GIT_ARCHIVE, $args);
	}


	/**
	 * Gets the file extension for this format
	 *
	 * @return string extension
	 */
	public function Extension()
	{
		return 'tar';
	}

	/**
	 * Gets the mime type for this format
	 *
	 * @return string mime type
	 */
	public function MimeType()
	{
		return 'application/x-tar';
	}

	/**
	 * Gets whether this archiver is valid
	 *
	 * @return boolean true if valid
	 */
	public function Valid()
	{
		return true;
	}
}
