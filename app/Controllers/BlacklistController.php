<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Test\Fabricator;
use App\Models\BlacklistModel;
use App\Entities\Blacklist;
use App\PDF;

class BlacklistController extends BaseController
{
  public function index()
  {
    helper('inflector');
    $blacklistModel = model('BlacklistModel');

    $blacklists = $blacklistModel->asArray()
      ->select('id,blacklist_no,created_at,reason')
      ->where('status','enabled')
      ->orderBy('id', 'ASC')
      ->findAll();

    //dd($blacklists);
    // Define the Table Heading
    $_SESSION['heads'] = [
      'id'           => 'SN#',
      'blacklist_no' => lang('app.blacklist.blacklist'),
      'created_at'   => 'Date',
      'reason'       => lang('app.blacklist.reasonTitle'),
    ];

    $data = [
      'title' => lang('app.blacklist.blacklistTitle'),
      'heads' => $_SESSION['heads'],
      'rows'  => $blacklists,
      'pager' => $blacklistModel->pager,
    ];

    unset($_SESSION['heads']);

    $this->config->viewLayout=getenv('auth.reportLayout');
    $data['config'] = $this->config;

    return view('Blacklist/blacklist-report', $data);
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
  public function enable($id)
  {
    $blacklistModel = new BlacklistModel();
    
    $blacklist = $blacklistModel->find($id);
    $blacklist->enable();
    if (! $blacklistModel->save($blacklist))
		{
			return redirect()->back()->withInput()->with('errors', $blacklistModel->errors());
		}
		$parser = \Config\Services::parser();
		$data   = [
			'blacklist_no' => $blacklist->getBlacklistNo(),
		];
		return redirect()->to(base_url(route_to('create-blacklist')))
							->with(
								'message',
								$parser->setData($data)
								->renderString(lang('app.blacklist.addSuccess'))
							);
  }
  public function disable($id)
  {
    $blacklistModel = new BlacklistModel();
    
    $blacklist = $blacklistModel->find($id);
    $blacklist->disable();
    if (! $blacklistModel->save($blacklist))
		{
			return redirect()->back()->withInput()->with('errors', $blacklistModel->errors());
		}
		$parser = \Config\Services::parser();
		$data   = [
			'blacklist_no' => $blacklist->getBlacklistNo(),
		];
		return redirect()->to(base_url(route_to('create-blacklist')))
							->with(
								'message',
								$parser->setData($data)
								->renderString(lang('app.blacklist.removeSuccess'))
							);
  }


  public function blacklist()
  {
    $blacklist = model('BlacklistModel')->asArray()
                      ->select('blacklist_no,reason,created_at')
                      ->where("status", 'enabled')
                      ->findAll();
    if (! $blacklist)
    {
      return redirect()->to(base_url(route_to('list-answers')))->with('error', lang('app.answer.notFound'));
    }

    $pdf = new PDF();	
    // Header
    $header['col_names'] = array('S/N','Vehicle/DL No', 'Date', 'Reason');
    $header['col_widths'] = array(10, 35, 30, 0);
    
    $pdf->SetFont('Arial','B',20);
    $pdf->Cell(0,10,'LIST OF BLACKLISTED VEHICLE OR DRIVER',0,1,'C');

    $pdf->FancyTable($header, $blacklist);

    $this->response->setHeader('Content-Type', 'application/pdf');
    $pdf->Output();
  }
}
