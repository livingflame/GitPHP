<?php
/**
 * File mime type strategy using Fileinfo
 *
 * @author Christopher Han <xiphux@gmail.com>
 * @copyright Copyright (c) 2012 Christopher Han
 * @package GitPHP
 * @subpackage Git\FileMimeType
 */
class GitPHP_FileMimeType_Fileinfo implements GitPHP_FileMimeTypeStrategy_Interface
{


	/**
	 * Gets the mime type for a blob
	 *
	 * @param GitPHP_Blob $blob blob
	 * @return string mime type
	 */
	public function GetMime($blob)
	{
		if (!$blob)
			return false;

		$data = $blob->GetData();
		if (empty($data))
			return false;

		$mime = '';

		$file_info = new finfo(FILEINFO_MIME);
		$mime = $file_info->buffer($data);  // e.g. gives
		if ($mime && strpos($mime, '/')) {
			if (strpos($mime, ';')) {
				$mime = strtok($mime, ';');
			}
		}
		return $mime;
	}

	/**
	 * Gets whether this mimetype strategy is valid
	 *
	 * @return bool true if valid
	 */
	public function Valid()
	{
		return class_exists('finfo');
	}

}
