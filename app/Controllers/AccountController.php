<?php namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Entities\User;
use App\Entities\School;
use Myth\Auth\Entities\User as AuthUser;
use Myth\Auth\Authorization\GroupModel;
use App\Models\UserModel;
use App\Models\ClassModel;

/**
 * Account Page Controller
 *
 * @package ePathshala
 */
class AccountController extends BaseController
{

	public function create()
	{
		$data['config'] = $this->config;
		if (user_id() === '1')
		{
			$data['schools'] = model('SchoolModel')->findAll();
		}
		else
		{
			$schools = [];
			array_push($schools, new School(session('school')));
			$data['schools'] = $schools;
		}
		$fabricator   = new Fabricator(UserModel::class);
		$data['user'] = (ENVIRONMENT !== 'production') ? $fabricator->make() : new User();
		$data['user']->setSchoolId(session('school.id'));
		$groups          = new GroupModel();
		$data['roles']   = $groups->asArray()->findAll();
		$db              = db_connect();
		$data['classes'] = $db->table('classes')->select('id, class')->get()->getResultArray();
		$db->close();
		return view('Account/create-form', $data);
	}

	public function tryToCreateUser()
	{
		$userModel  = new UserModel();
		$groupModel = new GroupModel();
		$newUser    = new User($this->request->getPost());

		// Use mobile number as username to login
		$newUser->username = $this->request->getPost('mobile');

		$rules = [
			'email'         => 'required|valid_email|is_unique[users.email]',
			'full_name'     => 'required|alpha_space|min_length[6]',
			'mobile'        => 'required|numeric|exact_length[10]|is_unique[users.mobile,mobile,{mobile}]',
			'school_id'     => 'required|numeric',
			'password_hash' => 'if_exist',
			'password'      => 'if_exist',
		];

		if (! $this->validate($rules))
		{
			return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
		}

		// Check if the Group Exists
		$group = $groupModel->where('name', $this->request->getPost('type'))->findAll();
		if (! $group)
		{
			return redirect()->back()->withInput()->with('error', lang('app.account.groupError'));
		}
		$userModel->withGroup($this->request->getPost('type'));

		// Set the Default Password
		$newUser->setPassword(getenv('auth.defaultPassword'));

		// Generate Activation Hash if Activation Required otherwise activate User
		$this->config->requireActivation !== false ? $newUser->generateActivateHash() : $newUser->activate();

		// Save the User
		if (! $userModel->save($newUser))
		{
			return redirect()->back()->withInput()->with('errors', $userModel->errors());
		}
		elseif ($this->config->requireActivation !== false)
		{
			$activator = service('activator');
			$sent      = $activator->send($newUser);

			if (! $sent)
			{
				return redirect()->back()->withInput()->with('error', $activator->error() ?? lang('Auth.unknownError'));
			}

			// Success!
			return redirect()->back()->with('message', lang('Auth.activationSuccess'));
		}

		return redirect()->back()->with('message', lang('app.account.createSuccess'));
	}

	public function profile()
	{
		$data['config']  = $this->config;
		$data['schools'] = model('SchoolModel')->findAll();
		$db              = db_connect();
		$data['classes'] = $db->table('classes')->select('id, class')->get()->getResultArray();
		$db->close();
		$data['user'] = model('UserModel')->find(user_id());

		// Display message if Update is Required
		model('UserModel')->find(user_id())->isProfileUpdateRequired();

		return view('Account/profile-form', $data);
	}

	public function tryToUpdateProfile()
	{
		$data['post']   = $this->request->getPost();
		$data['config'] = $this->config;

		$newUserModel = model('UserModel');
		$newUser      = user()->fill($this->request->getPost());

		$rules = [
			'full_name'   => 'required|alpha_space|min_length[6]',
			'mobile'      => 'required|numeric|exact_length[10]|is_unique[users.mobile,mobile,{mobile}]',
			'school_id'   => 'required|numeric',
			'class_id'    => 'permit_empty|numeric',
			'description' => 'permit_empty|string',
			'photo'       => 'if_exist|uploaded[photo]|is_image[photo]',
		];

		if (! $this->validate($rules))
		{
			return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
		}

		if (! $newUserModel->save($newUser))
		{
			return redirect()->back()->withInput()->with('errors', $newUserModel->errors());
		}
		else if ($this->request->getPost('class_id'))
		{
			$newClassModel = new ClassModel();
			// Users Restricted to One Class Only
			$newClassModel->removeUserFromAllClasses(user_id());
			$newClassModel->addUserToClass(user_id(), $this->request->getPost('class_id'));
		}

		//Update session so that routes can be defined
		model('UserModel')->find(user_id())->isProfileUpdateRequired();

		return redirect()->route('profile')->with('message', lang('app.profile.saveSuccess'));
	}

	public function index()
	{
		$userModel = new UserModel();
		$order     = $this->request->getGet('order') === 'asc' ? 'desc' : 'asc';
		$sort      = $this->request->getGet('sort');

		if (user_id() === '1')
		{
			$_SESSION['heads'] = [
				'id'         => 'ID# | Role | Class',
				'school_id'  => 'School',
				'mobile'     => 'Mobile',
				'full_name'  => 'Name',
				'email'      => 'E-Mail ID',
				'username'   => 'User ID',
				'active'     => 'Active',
				'created_at' => 'Created',
				'updated_at' => 'Updated',
			];

			$sort  = array_key_exists($sort, $_SESSION['heads']) ? $sort : 'id';
			$users = $userModel->asArray()
			->orderBy($sort, $order)
			->paginate();
		}
		else
		{
			$_SESSION['heads'] = [
				'id'        => 'ID# | Role | Class',
				'mobile'    => 'Mobile',
				'full_name' => 'Name',
				'email'     => 'E-Mail ID',
			];

			$sort  = array_key_exists($sort, $_SESSION['heads']) ? $sort : 'id';
			$users = $userModel->asArray()
				->where('school_id', session('school.id'))
				->orderBy('full_name')
				->paginate();
		}

		$rows     = [];
		$callback = function ($key) {
			return in_array($key, array_keys($_SESSION['heads']));
		};

		foreach ($users as $user)
		{
			array_push($rows, array_filter($user, $callback, ARRAY_FILTER_USE_KEY));
		}

		$updateArray = function (&$value, $key) {
			switch(true){

				case ($key === 'id'):
					$currentUser = model('UserModel')->find($value);
					$value       = trim('<a href="' . base_url(route_to('update-user', $value)) . '">' . $value . '</a>'
													. ' | ' . join(',', $currentUser->getRoles())
													. ' | ' . $currentUser->getClassName(), ' | ');
				break;

				case ($key === 'email'):
					$value = '<a title="' . lang('app.account.resendActivation') . '" '
								. 'href="' . base_url(route_to('resend-activate-account')) . '?login=' . $value . '">'
								. $value . '</a>';
				break;

				case ($key === 'school_id'):
					$value = $value . ' | ' . model('SchoolModel')->find($value)->school;
				break;

			}
		};

		array_walk_recursive($rows, $updateArray);

		$data = [
			'heads'  => $_SESSION['heads'],
			'rows'   => $rows,
			'pager'  => $userModel->pager,
			'order'  => $order,
			'config' => $this->config,
		];
		unset($_SESSION['heads']);

		return view('Account/users-form', $data, ['cache' => env('app.cacheTimeout', 300)]);
	}

	public function update(int $id)
	{
		$data['config'] = $this->config;
		if (user_id() === '1')
		{
			$data['schools'] = model('SchoolModel')->findAll();
		}
		else
		{
			$schools = [];
			array_push($schools, new School(session('school')));
			$data['schools'] = $schools;
		}
		$db              = db_connect();
		$data['classes'] = $db->table('classes')->select('id, class')->get()->getResultArray();
		$db->close();
		$groups        = new GroupModel();
		$data['roles'] = $groups->asArray()->findAll();
		$data['user']  = model('UserModel')->find($id);
		$data['title'] = lang('app.account.updateTitle');
		return view('Account/update-form', $data);
	}

	public function tryToUpdate(int $id)
	{
		$data['post']   = $this->request->getPost();
		$data['config'] = $this->config;

		$newUserModel = new UserModel();
		$newUser      = $newUserModel->find($id)->fill($this->request->getPost());
		if (user_id() !== '1')
		{
			$newUser->setSchoolId(session('school.id'));
		}

		$rules = [
			'full_name'   => 'required|alpha_space|min_length[6]',
			'mobile'      => 'required|numeric|exact_length[10]|is_unique[users.mobile,mobile,{mobile}]',
			'school_id'   => 'required|numeric',
			'class_id'    => 'permit_empty|numeric',
			'description' => 'permit_empty|string',
			'photo'       => 'if_exist|uploaded[photo]|is_image[photo]',
		];

		if (! $this->validate($rules))
		{
			return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
		}

		if (! $newUserModel->save($newUser))
		{
			return redirect()->back()->withInput()->with('errors', $newUserModel->errors());
		}
		else if ($this->request->getPost('class_id'))
		{
			$newClassModel = new ClassModel();
			// Users Restricted to One Class Only
			$newClassModel->removeUserFromAllClasses(user_id());
			$newClassModel->addUserToClass(user_id(), $this->request->getPost('class_id'));
		}

		return redirect()->to(base_url(route_to('update-user', $id)))->with('message', lang('app.account.saveSuccess'));
	}

	/**
	 * Load school and class in session storage when login successful
	 * so that we can use that throughout the session for managing access rights
	 *
	 * @param AuthUser $user Currently logged in user
	 *
	 * @return void
	 */
	public static function onLoginEvent(AuthUser $user)
	{
		$currentUser = model('UserModel')->find($user->id);

		$currentUser->isProfileUpdateRequired();

		session()->set(
			'school', [
				'class_id' => $currentUser->getClassId(),
				'class'    => $currentUser->getClassName(),
				'id'       => $currentUser->getSchoolId(),
				'school'   => $currentUser->getSchoolName(),
			]);
	}
	public static function onLogoutEvent($user)
	{
		return redirect('login')->to(base_url(route_to('login')))->with('message', lang('app.account.logout'));
	}
}
