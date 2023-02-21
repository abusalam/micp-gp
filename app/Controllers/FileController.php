<?php namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Models\FileModel;
use App\Models\AssignmentModel;
use App\Entities\File;

/**
 * Home Page Controller
 *
 * @package ePathshala
 */
class FileController extends BaseController
{

	/**
	 * Lists all the files the user has uploaded
	 * Route: /assignment/files as get:list-file
	 * Uses view File/list-form.php
	 *
	 * @return view
	 */
	public function index()
	{
		helper('number');
		$fileModel = model('FileModel');
		$files     = $fileModel->asArray()->where('user_id', user_id())->paginate();

		$_SESSION['heads'] = [
			'id'         => 'ID#',
			'file'       => 'File',
			'size'       => 'Uploaded',
			'updated_at' => 'Updated On',
		];

		$rows = [];

		$callback = function (&$value, $key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		foreach ($files as $file)
		{
			array_push($rows, array_filter($file, $callback, ARRAY_FILTER_USE_BOTH));
		}

		$updateArray = function (&$value, $key) {
			if ($key === 'size')
			{
				$value = number_to_size($value);
			}
		};

		array_walk_recursive($rows, $updateArray);

		$data = [
			'heads' => $_SESSION['heads'],
			'rows'  => $rows,
			'pager' => $fileModel->pager,
		];

		unset($_SESSION['heads']);

		$data['config'] = $this->config;
		$data['title']  = lang('app.file.listTitle');

		return view('File/list-form', $data);
	}

	/**
	 * Send the File for Viewing
	 *
	 * Route: /file/#fileId as get:show-file
	 *
	 * @param integer $id File id for the file to show
	 *
	 * @return blob
	 */
	public function view(int $id = null)
	{
		$imageFile = model('FileModel')->find($id);
		if ($imageFile)
		{
			return $imageFile->view();
		}
	}

	/**
	 * Send the File to be deleted
	 *
	 * Route: /file/#fileId/delete as get:del-file
	 *
	 * @param integer $id         File id for the file to show
	 * @param string  $redirectTo Name route to which to be redirected when done
	 * @param integer $sourceID   Assignment/Answer ID
	 *
	 * @return blob
	 */
	public function delete(int $id, string $redirectTo, int $sourceID)
	{
		$imageFile = model('FileModel')->find($id);
		if ($imageFile)
		{
			$imageFile->delete();
		}
		return redirect()->to(base_url(route_to($redirectTo, $sourceID)));
	}

	/**
	 * Rotates the image File in place
	 *
	 * Route: /file/#fileId/delete as get:del-file
	 *
	 * @param integer $id         File id for the file to show
	 * @param float   $angle      Angle by which file is to be rotated
	 * @param string  $redirectTo Name route to which to be redirected when done
	 * @param integer $sourceID   Assignment/Answer ID
	 *
	 * @return blob
	 */
	public function rotate(int $id, float $angle, string $redirectTo, int $sourceID)
	{
		$imageFile = model('FileModel')->find($id);
		if ($imageFile)
		{
			$imageFile->rotate($angle);
		}
		return redirect()->to(base_url(route_to($redirectTo, $sourceID)));
	}
}
