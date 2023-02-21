<?php namespace App\Entities;

use CodeIgniter\Entity;
use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\Files\UploadedFile;
use App\Models\FileModel;

/**
 * Handles CRUD operation of Files
 *
 * @package ePathshala
 */
class File extends Entity
{
	/**
	 * Store the Assignment to which this file belongs to
	 * either directly or via answers
	 *
	 * @var object
	 */
	protected $assignment = null;

	/**
	 * Store Default File Upload Directory Path
	 *
	 * @var string
	 */
	protected $fileDir = WRITEPATH . 'uploads' . '/';

	/**
	 * Store the File
	 *
	 * @var File
	 */
	protected $fileData = null;

	/**
	 * Sets the fileDir from env value if found
	 * create the fileDir if does not exists
	 * Loads number helper
	 */
	public function __construct()
	{
		// Update fileDir with env value and DATE defaults to uploads/YYYYDDMM
		$this->fileDir = WRITEPATH . env('app.fileDirectory', 'uploads') . '/';

		// Create the directory if not exists
		if (! file_exists($this->fileDir))
		{
			mkdir($this->fileDir);
		}
		helper('number');
	}

	public function loadFile()
	{
		if ($this->id)
		{
			$this->fileData = \Config\Services::image()
					->withFile($this->fileDir . $this->file);
		}
		return $this;
	}

	public function getImageInfo()
	{
		return $this->fileData->getProperties(true) ?? null;
	}

	/**
	 * Populates the $assignment property if null
	 *
	 * @return Assignment
	 */
	public function getAssignment()
	{
		if (! $this->assignment)
		{
			$this->assignment = model('FileModel')->getAssignmentByFileID($this->id);
		}
		return $this->assignment;
	}

	/**
	 * Populates the $assignment property if null
	 *
	 * @return Answer
	 */
	public function getAnswer()
	{
		if (! $this->assignment)
		{
			$this->assignment = model('FileModel')->getAnswerByFileID($this->id);
		}
		return $this->assignment;
	}

	/**
	 * Set the currently logged in user as file owner
	 *
	 * @return object
	 */
	public function setCreatedBy()
	{
		$this->attributes['user_id'] = user_id();
		return $this;
	}

	/**
	 * Compress and Save Uploaded files and the redirect back
	 *
	 * @param UploadedFile $imageFile   The Uploaded Image File
	 * @param string       $attachClass The Class to which this file will get attached to
	 * @param integer      $attachID    The id of the Class to which this file will get attached to
	 *
	 * @return view
	 */
	public function save(UploadedFile $imageFile, string $attachClass, int $attachID)
	{
		if ($imageFile->isValid() && ! $imageFile->hasMoved() && $imageFile->getMimeType() === 'image/jpeg')
		{
			$fileModel  = new FileModel();
			$this->size = $imageFile->getSize();
			try
			{
				// At First Save the File to the default location WRITEPATH/uploads/YYYYMMDD
				$this->file = $imageFile->store();

				$newDir = pathinfo($this->fileDir . $this->file, PATHINFO_DIRNAME);

				if (! file_exists($newDir))
				{
					mkdir($newDir);
				}
				// Compress the file and place it in configured folder if not will update the existing
				// TODO: Don't compress very small images

				$info = \Config\Services::image()
						->withFile(WRITEPATH . 'uploads/' . $this->file)
						->getFile()
						->getProperties(true);

				// Target Height and Width of the Image
				$height = env('app.imageHeight', 842);
				$width  = env('app.imageWidth', 595);

				// Use the larger size as master
				if ($info['width'] > $info['height'])
				{
					$masterDim = 'width';
				}
				else
				{
					$masterDim = 'height';
				}

				$image = \Config\Services::image()
					->withFile(WRITEPATH . 'uploads/' . $this->file)
					// ->withResource()
					->reorient(true)
					->resize($width, $height, true, $masterDim);

				// Rotate the Image if it is landscape
				if ($info['width'] > $info['height'])
				{
					$image->rotate(90);
				}

				if (max($info['width'], $info['height']) > env('app.imageHeight', 842))
				{
					$quality = 100;
				}
				else
				{
					$quality = env('app.imageQuality', 90);
				}
				// Save the image
				$image->save($this->fileDir . $this->file, $quality);
			}
			catch (CodeIgniter\Images\Exceptions\ImageException $e)
			{
				return $e->getMessage();
			}

			//Must be Called beforeInsert to ensure that the id exists
			$fileModel->attachWith($attachID, $attachClass);

			if (! $fileModel->save($this->setCreatedBy()))
			{
				//dd($this);
				return $fileModel->errors();
			}

			$parser     = \Config\Services::parser();
			$this->size = number_to_size($this->size);
			return $this;
		}
		else
		{
			return lang('app.file.fileInvalidError');
		}
	}

	/**
	 * Check if the current file is allowed for the current user
	 * considering both the roles teachers and students
	 *
	 * This function must be called after proper initialization from it's model
	 *
	 * @return boolean
	 */
	public function isAllowed()
	{
		// Check if the user has created the file
		// For teachers this would allow all his assignments
		// For students this would allow all his answers
		if (! $this->attributes['user_id'] === user_id())
		{
			// Check if this file belongs to any of the assignments created by him
			// or any of the answers of those assignments
			// Check for file > answer_files > answer > assignment > created_by_user
			// matches current user
			return $this->getAssignment()->isAllowed();
		}
		return true;
	}

	/**
	 * Deletes a File
	 *
	 * Route: /assignment/file/#fileId/delete as get:delete-file
	 *
	 * @return boolean
	 */
	public function delete()
	{
		if ($this->isAllowed())
		{
			cache()->delete($this->id . '_file');
			cache()->delete($this->getAssignment()->id . '_assignment_files');
			cache()->delete($this->getAnswer()->id . '_answer_files');
			return model('FileModel')->delete($this->id);
		}
	}

	/**
	 * Rotates a File
	 *
	 * Route: /assignment/file/#fileId/rotate/#angle as get:rotate-file
	 *
	 * @param float $angle Angle by which file is to be rotated
	 *
	 * @return boolean
	 */
	public function rotate(float $angle = 90)
	{
		if ($this->isAllowed())
		{
			cache()->delete($this->id . '_file');
			$filePath = $this->fileDir . $this->file;
			// Check if file exists before actually reading the file
			if (file_exists($filePath))
			{
				$image = \Config\Services::image()
						->withFile($filePath)
						->rotate($angle)
						->save($filePath);
				return true;
			}
			session()->set('error', lang('app.file.notFound'));
			return false;
		}
		session()->set('error', lang('app.file.unAuthorized'));
		return false;
	}

	/**
	 * Send the File for Viewing
	 *
	 * Route: /assignment/file/#fileId as get:show-file
	 * Uses view File/file-form.php
	 *
	 * @return blob
	 */
	public function view()
	{
		if (! $found = cache($this->id . '_file'))
		{
			$filePath = $this->fileDir . $this->file;
			// Check if file exists before actually reading the file
			if (file_exists($filePath))
			{
				if ($this->isAllowed())
				{
					$image = \Config\Services::image()
						->withFile($filePath)
						->text(date('d/m/Y g:i a'), [
							'color'        => '#2471A3',
							'opacity'      => 0.2,
							'withShadow'   => true,
							'shadowColor'  => '#FFFFFF',
							'shadowOffset' => '1',
							'hAlign'       => 'left',
							'vAlign'       => 'bottom',
							'fontSize'     => 20,
						])
						->text('Copyright ' . date('Y') . ' ePathshala', [
							'color'        => '#2471A3',
							'opacity'      => 0.2,
							'withShadow'   => true,
							'shadowColor'  => '#FFFFFF',
							'shadowOffset' => '1',
							'hAlign'       => 'center',
							'vAlign'       => 'bottom',
							'fontSize'     => 20,
						])
						->text('File #' . $this->id, [
							'color'        => '#2471A3',
							'opacity'      => 0.2,
							'withShadow'   => true,
							'shadowColor'  => '#FFFFFF',
							'shadowOffset' => '1',
							'hAlign'       => 'right',
							'vAlign'       => 'bottom',
							'fontSize'     => 20,
						])
						->save($filePath . '-cache');
				}
				$found = file_get_contents($filePath . '-cache');
				unlink($filePath . '-cache');
				cache()->save($this->id . '_file', $found, env('app.cacheTimeout', 300));
			}
			else
			{
				return redirect()->back()->with('message', lang('app.file.notFound') . ' ' . $filePath);
			}
		}
		return $found;
	}
}
