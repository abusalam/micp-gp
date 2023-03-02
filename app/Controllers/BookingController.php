<?php

namespace App\Controllers;

use CodeIgniter\Test\Fabricator;
use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Entities\Booking;
use GuzzleHttp\Client;
use App\PDF;

class BookingController extends BaseController
{
  public function index()
  {
    helper('inflector');
    $assignmentModel = model('AssignmentModel');
    if (in_groups('teachers'))
    {
      $assignments = $assignmentModel->asArray()
          ->select('id,topic_id,title,marks,status')
          ->where('user_id', user_id())
          ->orderBy('updated_at', 'DESC')
          ->paginate();
    }
    else
    {
      // To Display Assignments it is ensured that Assignment and Topic both
      // should be created by the teacher of that school only and
      // the assignment status must be sent
      $assignments = $assignmentModel->findAssignmentsToSolve(user_id());
    }
    //dd($assignments);
    // Define the Table Heading
    $_SESSION['heads'] = [
      'id'       => 'ID# | Files',
      'topic_id' => 'Topic',
      'title'    => 'Assignment',
      'marks'    => 'Marks',
      'status'   => 'Status',
    ];

    $rows = [];

    $callback = function (&$value, $key) {
      return in_array($key, array_keys($_SESSION['heads']));
    };

    // Double Check for Access Rights and Locked Status
    foreach ($assignments as $assignment)
    {
      $found = model('AssignmentModel')->find($assignment['id']);
      if ($found->isAllowed())
      {
        if (! in_groups('teachers'))
        {
          if ($found->isLocked())
          {
            array_push($rows, array_filter($assignment, $callback, ARRAY_FILTER_USE_BOTH));
          }
        }
        else
        {
          array_push($rows, array_filter($assignment, $callback, ARRAY_FILTER_USE_BOTH));
        }
      }
    }

    $updateArray = function (&$value, $key) {
      switch(true){

        case ($key === 'topic_id'):
          $value = model('TopicModel')->find($value)->getTitle();
        break;

        case ($key === 'id'):
          $files = model('AssignmentModel')->getFiles($value);
          // From Route: assignment/list => assignment/#id
          $value = $value . ' | ' . '<a href="' . base_url(route_to('view-assignment-files', $value)) . '">' . counted(count($files ?? []), 'Page') . '</a>';
        break;
      }
    };

    array_walk_recursive($rows, $updateArray);

    $data = [
      'heads' => $_SESSION['heads'],
      'rows'  => $rows,
      'pager' => $assignmentModel->pager,
    ];

    unset($_SESSION['heads']);

    $data['config'] = $this->config;

    return view('Booking/list-form', $data);
  }
  public function getDailyReport()
  {
    helper('inflector');
    $bookingModel = model('BookingModel');

    $bookings = $bookingModel->asArray()
      ->select('`id`,`vehicle_no`,`license_no`,`driver_name`,`driver_mobile`,`crew_name`,`crew_mobile`')
      ->where('issued_on', date('Y-m-d', time()))
      ->orderBy('vehicle_no', 'ASC')
      ->findAll();

    //dd($bookings);
    // Define the Table Heading
    $_SESSION['heads'] = [
      'id'            => 'ID#',
      'vehicle_no'    => 'Truck#',
      'license_no'    => 'DL#',
      'driver_name'   => 'Driver',
      'driver_mobile' => 'Mobile',
      'crew_name'     => 'Crew',
      'crew_mobile'   => 'Mobile',
    ];
    $parser    = \Config\Services::parser();

    $dailyReportTitle = $parser->setData(['date' => date('d/m/Y', time())])
            ->renderString(lang('app.booking.dailyReportTitle'));
    $data = [
      'title' => $dailyReportTitle,
      'heads' => $_SESSION['heads'],
      'rows'  => $bookings,
      'pager' => $bookingModel->pager,
    ];

    unset($_SESSION['heads']);

    $data['config'] = $this->config;

    return view('Booking/daily-report', $data);
  }

  public function createBooking()
  {
    $data['config']   = $this->config;

    $data['booking'] = (ENVIRONMENT !== 'production') ?
                          (new Fabricator(BookingModel::class))->make():
                          new Booking();

    return view('Booking/create-form', $data);
  }

  public function tryToCreateBooking()
  {
    $bookingModel = new BookingModel();
    $newBooking   = new Booking();
    $newBooking->fill($this->request->getPost());
    session()->set('post_data', $this->request->getPost());
    $newBooking->setPassValidity();

    if (! $bookingModel->save($newBooking))
    {
      return redirect()->back()->withInput()->with('errors', $bookingModel->errors());
    }

    $data   = [
      'id'    => $bookingModel->getInsertID(),
    ];
    
    $data['booking']  = $newBooking;
    $data['config'] = $this->config;
    return view('Booking/status-form', $data);
  }

  
  public function printGatePass(int $id)
  {
    $bookingOrder = model('BookingModel')->find($id);
    if (! $bookingOrder)
    {
      return redirect()->to(base_url(route_to('create-booking')))
          ->with('error', lang('app.assignment.notFound'));
    }
    $pdf = new PDF();		
    for ($i=0;$i<=1;$i++)
    {
      $pdf->SetFont('Arial','B',20);
      $pdf->Cell(0,10,'GATE PASS',0,1,'C');
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(0,5,'MAHADIPUR  IMMIGRATION CHECKPOST',0,1,'C');
      $pdf->SetFont('Arial','',10);
      $pdf->Cell(0,5,'MAHADIPUR,ENGLISHBAZAR ,MALDA. (WB)',0,1,'C');
  
      $pdf->SetFont('Courier','',11);
      $pdf->Cell(60,5,'',0,0);
      $pdf->Cell(70,5,'Gate Pass No: ' . $bookingOrder->getPassNo(),1,1,'C');
      $pdf->SetFont('Times','B',11);
      $pdf->Cell(0,10,'For Driver and Crew of Export/ Import Vehicle',0,1,'C');
      //$pdf->Cell(0,8,'|',0,1,'C');
      $pdf->Cell(95,5,'Issued on: ' . $bookingOrder->getIssueDate(),0,0);
      $pdf->Cell(0,5,'Valid till: ' . $bookingOrder->getValidTill(),0,1);
      //$pdf->Cell(140,10,'Journey Schedule: ' . $bookingOrder->getBookedSlot(),1);
      //$pdf->Cell(0,10,'Rs. ' . $bookingOrder->getAmount(),1,1,'C');
      
      $pdf->SetFont('Courier','',11);
      $AddrY = $pdf->GetY();
      $AddrX = $pdf->GetX();
      $pdf->Cell(0,36,'',0,1);
      $lineHeight=5;
      $pdf->Cell(95,$lineHeight,'1. Truck No: ' . $bookingOrder->getVehicleNo(),'T',0);
      $pdf->Cell(0,$lineHeight,'1. Name of Crew: ' . $bookingOrder->getCrewName(),'T',1);
      
      $pdf->Cell(95,$lineHeight,'2. Name of Driver: ' . $bookingOrder->getDriverName(),'T',0);
      $pdf->Cell(0,$lineHeight,'2. Crew ID Card Type: ' . $bookingOrder->getCrewIdType(),'T',1);
      
      $pdf->Cell(95,$lineHeight,'3. D/L No: ' . $bookingOrder->getLicenseNo(),'T',0);
      $pdf->Cell(0,$lineHeight,'3. Crew ID Card No: ' . $bookingOrder->getCrewID(),'T',1);
      
      // For Multicell for right column
      $MultiCellAddrY = $pdf->GetY();
      $pdf->Cell(95,0,'' . $bookingOrder->getDOB(),0,0);
      $pdf->MultiCell(0,$lineHeight,'4. Address of Crew: ' . $bookingOrder->getDriverName(),'T',1);

      // Pull up to overwrite DOB Cell on left column
      $pdf->SetY($MultiCellAddrY);
      $pdf->MultiCell(95,$lineHeight,'4. Address of Driver: ' . $bookingOrder->getDriverAddress(),'T',0);
      
      $pdf->SetY($AddrY);
      $pdf->Cell(95,70,'',1,0);
      $pdf->Cell(0,70,'',1,1);
  
      $pdf->Cell(95,10,'Signature:',1,0);
      $pdf->Cell(0,10,'Signature:',1,1);
      $pdf->Ln(2);
      $pdf->SetFont('Arial','B',16);
      $pdf->Cell(10,5,'',0,0);
      $pdf->Cell(5,5, chr(187),0,0);
      $pdf->SetFont('Arial','',10);
      $parser    = \Config\Services::parser();
      $pdf->MultiCell(180,5, $parser->setData(['validity' => getenv('PASS_VALIDITY')])
      ->renderString(lang('app.booking.createHelp')),0,1);
      $EndAddrY = $pdf->GetY();
      $EndAddrX = $pdf->GetX();
  
      $pdf->SetXY($AddrX+33,$AddrY+3);
      //$pdf->Cell(30,30,'Photograph',1,0,'C');
      // $pdf->Image('data://text/plain;base64,' . $bookingOrder->getBase64ImageData('driver_photo'), null,null,30,30,'png');
      $pdf->Image($bookingOrder->getImageData('driver_photo'), null,null,30,30,'data');
      $pdf->SetXY($AddrX+128,$AddrY+3);
      //$pdf->Cell(30,30,'Photograph',1,1,'C');
      // $pdf->Image('data://text/plain;base64,' . $bookingOrder->getBase64ImageData('crew_photo'), null,null,30,30,'png');
      if($bookingOrder->getCrewMobile())
      {
        $pdf->Image($bookingOrder->getImageData('crew_photo'), null,null,30,30,'data');
      } else {
        $pdf->Cell(30,30,'',1,1,'C');
      }
      $pdf->SetXY($EndAddrX,$EndAddrY);
      if(!$i){
        $pdf->Cell(0,10,'',0,1,'C');
      }
    }
    

    

    $this->response->setHeader('Content-Type', 'application/pdf');
    $pdf->Output();
  }

  public function getBookingSlot()
  {
    $data['config'] = $this->config;
    return view('Booking/check-form', $data);
  }

  public function getDriverDetails()
  {
    $bookingModel = new BookingModel();
    
    $bookings = $bookingModel->select('driver_name,driver_mobile,driver_address')
            ->where('license_no', $this->request->getVar('license_no'))
            ->first();

    //$data['csrf_token'] = $this->security->get_csrf_hash();
    $this->response->setHeader('Content-Type', 'application/json');
    echo json_encode($bookings);
  }

  public function getCrewDetails()
  {
    $bookingModel = new BookingModel();
    
    $bookings = $bookingModel->select('crew_name,crew_id_type,crew_id_no,crew_address')
            ->where('crew_mobile', $this->request->getVar('crew_mobile'))
            ->first();

    //$data['csrf_token'] = $this->security->get_csrf_hash();
    $this->response->setHeader('Content-Type', 'application/json');
    echo json_encode($bookings);
  }

  public function showTicketSearch()
  {
    $data['config'] = $this->config;
    return view('Booking/search-form', $data);
  }

  public function getBookingsByRef()
  {
    $bookingModel = new BookingModel();
    
    $booking = $bookingModel->where('ticket', $this->request->getPost('ticket'))
    ->where('status', 'SUCCESS')
    ->first();
    $data['booking'] = $booking;
    //$data['csrf_token'] = $this->security->get_csrf_hash();
    //$this->response->setHeader('Content-Type', 'application/json');
    //echo json_encode($data);
    $data['config'] = $this->config;
    
    return view('Booking/search-form', $data);
  }

}
