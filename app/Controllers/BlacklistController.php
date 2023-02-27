<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Test\Fabricator;
use App\Models\BlacklistModel;
use App\Entities\Blacklist;
use FPDF;

class BlacklistController extends BaseController
{
  public function index()
  {
    helper('inflector');
    $blacklistModel = model('BlacklistModel');

    $blacklists = $blacklistModel->asArray()
      ->select('id,blacklist_no,reason')
      ->where('status','enabled')
      ->orderBy('updated_at', 'DESC')
      ->paginate();

    //dd($blacklists);
    // Define the Table Heading
    $_SESSION['heads'] = [
      'id'           => 'ID#',
      'blacklist_no' => lang('app.blacklist.blacklist'),
      'reason'       => lang('app.blacklist.reasonTitle'),
    ];

    $data = [
      'heads' => $_SESSION['heads'],
      'rows'  => $blacklists,
      'pager' => $blacklistModel->pager,
    ];

    unset($_SESSION['heads']);

    $data['config'] = $this->config;

    return view('Blacklist/list-form', $data);
  }

  public function showBlacklistForm()
  {
    $data['config'] = $this->config;
    $data['blacklist'] = (ENVIRONMENT !== 'production') ? (new Fabricator(BlacklistModel::class))->make():new Blacklist();
    return view('Blacklist/create-form', $data);
  }
  
  public function tryToBlacklist()
  {
    $blacklistModel = new BlacklistModel();
    $newBlacklist   = new Blacklist();
    $newBlacklist->fill($this->request->getPost());
    session()->set('post_data', $this->request->getPost());

    if (! $blacklistModel->save($newBlacklist))
    {
      return redirect()->back()->withInput()->with('errors', $blacklistModel->errors());
    }

    $data   = [
      'id'    => $blacklistModel->getInsertID(),
    ];

    $parser    = \Config\Services::parser();
		$savedBlacklist = $newBlacklist->toArray();
    if($this->request->getPost('id'))
    {
      return redirect()->to(base_url(route_to('create-blacklist')))
                    ->with('message', $parser->setData($savedBlacklist)
                    ->renderString(lang('app.blacklist.removeSuccess')));
    } else {
      return redirect()->to(base_url(route_to('create-blacklist')))
                    ->with('message', $parser->setData($savedBlacklist)
                    ->renderString(lang('app.blacklist.addSuccess')));
    }
  }

  public function check()
  {
    $blacklistModel = new BlacklistModel();
    
    $blacklists = $blacklistModel->select('id,blacklist_no,reason,status')
            ->where('blacklist_no', $this->request->getVar('blacklist_no'))
            ->first();

    //$data['csrf_token'] = $this->security->get_csrf_hash();
    $this->response->setHeader('Content-Type', 'application/json');
    echo json_encode($blacklists);
  }
}
