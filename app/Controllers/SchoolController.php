<?php namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Models\SchoolModel;
use App\Entities\School;

/**
 * School Controller
 *
 * @package ePathshala
 */
class SchoolController extends BaseController
{
	public function index()
	{
		if (user_id() !== '1')
		{
			return redirect()->to(base_url())->with('message', lang('app.school.unAuthorized'));
		}

		$schoolModel = new SchoolModel();

		$_SESSION['heads'] = [
			'id'         => 'ID#',
			'udise'      => 'U-DISE',
			'school'     => 'School',
			'created_at' => 'Created',
			'updated_at' => 'Updated',
		];

		$order   = $this->request->getGet('order') === 'asc' ? 'desc' : 'asc';
		$sort    = $this->request->getGet('sort');
		$sort    = array_key_exists($sort, $_SESSION['heads']) ? $sort : 'id';
		$schools = $schoolModel->asArray()
								->orderBy($sort, $order)
								->paginate();

		$rows     = [];
		$callback = function ($key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		foreach ($schools as $school)
		{
			array_push($rows, array_filter($school, $callback, ARRAY_FILTER_USE_KEY));
		}

		$updateArray = function (&$value, $key) {
			switch(true){

				case ($key === 'id'):
					$value = '<a href="' . base_url(route_to('update-school', $value)) . '">' . $value . '</a>';
				break;
			}
		};

		array_walk_recursive($rows, $updateArray);

		$data = [
			'heads'  => $_SESSION['heads'],
			'rows'   => $rows,
			'pager'  => $schoolModel->pager,
			'order'  => $order,
			'config' => $this->config,
		];
		unset($_SESSION['heads']);

		$data['title'] = lang('app.school.listTitle');
		return view('School/list-form', $data, ['cache' => env('app.cacheTimeout', 300)]);
	}

	public function create()
	{
		if (user_id() !== '1')
		{
			return redirect()->to(base_url())->with('message', lang('app.school.unAuthorized'));
		}

		$data['config'] = $this->config;
		$data['school'] = (ENVIRONMENT !== 'production') ? (new Fabricator(SchoolModel::class))->make() : new School();

		return view('School/create-form', $data);
	}

	public function tryToCreate()
	{
		if (user_id() !== '1')
		{
			return redirect()->to(base_url())->with('message', lang('app.school.unAuthorized'));
		}

		$schoolModel = new SchoolModel();
		$newSchool   = new School();
		$newSchool->fill($this->request->getPost());

		// Try and Catch Database Exception
		try
		{
			if (! $schoolModel->insert($newSchool))
			{
				return redirect()->back()->withInput()->with('errors', $schoolModel->errors());
			}
		}
		catch (\mysqli_sql_exception $e)
		{
			return redirect()->back()->withInput()->with('error', $e->getMessage());
		}

		$parser = \Config\Services::parser();
		$school = [
			'id'     => $schoolModel->getInsertID(),
			'school' => $newSchool->school,
		];
		return redirect('schools')->with(
			'message',
			$parser->setData($school)->renderString(lang('app.school.createSuccess')));
	}

	public function update($id)
	{
		if (user_id() !== '1')
		{
			return redirect()->to(base_url())->with('message', lang('app.school.unAuthorized'));
		}
		$schoolModel = new SchoolModel();
		$newSchool   = $schoolModel->find($id);
		if ($newSchool)
		{
			$newSchool->fill($this->request->getPost());
		}
		else
		{
			return redirect()->back()->withInput()->with('errors', lang('app.school.notFound'));
		}
		$data['config'] = $this->config;
		$data['id']     = $id;
		$data['school'] = $newSchool;
		$data['title']  = lang('app.school.updateTitle');
		return view('School/update-form', $data);
	}

	public function tryToUpdate($id)
	{
		if (user_id() !== '1')
		{
			return redirect()->to(base_url())->with('message', lang('app.school.unAuthorized'));
		}

		$schoolModel = new SchoolModel();
		$newSchool   = $schoolModel->find($id);
		if ($newSchool)
		{
			$newSchool->fill($this->request->getPost());
		}
		else
		{
			return redirect()->back()->withInput()->with('errors', lang('app.school.notFound'));
		}

		if ($newSchool->hasChanged())
		{
			// Try and Catch Database Exception
			try
			{
				if (! $schoolModel->update($id, $newSchool))
				{
					return redirect()->back()->withInput()->with('errors', $schoolModel->errors());
				}
			}
			catch (\mysqli_sql_exception $e)
			{
				return redirect()->back()->withInput()->with('error', $e->getMessage());
			}
		}

		$parser = \Config\Services::parser();
		$school = [
			'id'     => $id,
			'school' => $newSchool->school,
		];
		return redirect('schools')->with(
			'message',
			$parser->setData($school)->renderString(lang('app.school.updateSuccess')));
	}
}
