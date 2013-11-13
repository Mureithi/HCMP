<?php
ob_start();
include_once 'auto_sms.php';
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stock extends auto_sms {
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form','url','file'));
		$this->load->library('mpdf');
    }

    public function index() {
        
        $news=$_POST['kemsa'];
        
        //receiver details
        $name=$_POST['r_name'];
        $id_no=$_POST['r_pin'];
        $phone=$_POST['r_phone'];
        $facility_r=$facility=$_POST['facility_a'];
        $district_r=$this -> session -> userdata('district1');
        $order=$_POST['ord_no'];
        //echo $order.'<br>';
        $level=$this -> session -> userdata('full_name');
        $batch_no=$this->input->post('batch_no');
        $facii=$facility=$_POST['facility_a'];
        //echo $name;
        
        
        //print_r($news);
        $code=count($news);
        $facility=$this->input->post('facility_a');
        
        $receipts=$this->input->post('ordered');
        $kemsa=$this->input->post('kemsa');
        $facility=$this->input->post('facility_a');
        $batch=$this->input->post('batch');
        //echo $batch;
        $expiry=$this->input->post('expiry');
        $manufacture=$this->input->post('manufacture');
        
        for($i=0;$i<$code;$i++){
            
        $pass=new Facility_Stock();
            
        
        
        $pass->batch_no=$batch[$i];
        $pass->expiry_date=$expiry[$i];
        $pass->manufacture=$manufacture[$i];
        $pass->quantity=$receipts[$i];
        $pass->kemsa_code=$kemsa[$i];
        $pass->balance=$receipts[$i];
        $pass->facility_code=$facility;
        $pass->save();

        }
        //echo $facility;
        $u=Facility_Stock::getAll1($facility);
        $n=$u->toArray();

        $p=count($n);
        $date= time();
                    foreach($n as $arr){
                        //echo $arr;
                        $code=$arr['kemsa_code'];
                        $facility=$facility;
                        $bal=$arr['SUM'];
                        

        $transact=new Facility_Transaction_table();
        $transact->Facility_Code=$facility;
        $transact->Kemsa_Code=$code;
        $transact->Opening_Balance=$bal;
        $transact->Cycle_Date=$date;
        $transact->save();
        
                    }
        
        $receive=new Delivery_Details();
        $receive->name=$name;
        $receive->id_no=$id_no;
        $receive->phone=$phone;
        $receive->facility=$facii;
        $receive->district=$district_r;
        $receive->order_number=$order;
        $receive->user_level=$level;
        $receive->delivery_no=$batch_no;
        $receive->save();
        
        $status=1;
        $state=Doctrine::getTable('kemsa_order')->findOneBykemsa_order_no($order);
        $state->update_flag =$status;
        $state->save();
        
        $facility_c=$this -> session -> userdata('news');
        $data['title'] = "Stock";
        $data['content_view'] = "facility_home_v"; //facility/facility_reports/stock_level_v
        $data['banner_text'] = "Stock Level";
        $data['link'] = "order_management";
        $data['stock_count']=Facility_Stock::count_facility_stock($facility_c);
        $data['quick_link'] = "stock_level";
        $this -> load -> view("template", $data);
                    
                    
    }
public function submit(){
	
		
	$facility=$this -> session -> userdata('news');
	$today = date("Y-m-d h:i:s");
	//products
	$kemsaCode=$_POST['kemsaCode'];
	$batchNo=$_POST['batchNo'];
	$Exp=$_POST['Exp'];
	$units=$_POST['qreceived'];
	
	//delivery details
	$order=$_POST['order'];
	$warehouse=$_POST['warehouse'];
	//$district=$_POST['district'];
	$date_deliver=date('y-m-d',strtotime($_POST['ddate']));
	$dnote=$_POST['dno'];
	$today=date('Y-m-d h:i');
	 //echo $order;
	
	//$comment=$_POST['comment'];
	//$rid=$_POST['rid'];
	$rphone=$_POST['rphone'];
	$rname=$_POST['rname'];
	$lsn=$_POST['lsn'];
	$date = date('Y-m-d h:i');
	$orderDate=date('y-m-d H:i:s');
	$dispdate=date('y-m-d',strtotime($_POST['dispdate']));
	//$dispby=$_POST['dispby'];
	$dispby="";
	$dispby .= $this -> session -> userdata('names');
	$dispby .=" ";
	$dispby .=$this -> session -> userdata('inames');
  


	$j=count($kemsaCode);
		
	for($i=1;$i<=$j;$i++){
	$pass=new Facility_Stock();
	$pass->facility_code=$facility;
	$pass->kemsa_code=$kemsaCode[$i];
	$pass->quantity=$units[$i];
	$pass->balance=$units[$i];
	$pass->batch_no=$batchNo[$i];
	$pass->expiry_date=date('y-m-d',strtotime($Exp[$i]));
	$pass->stock_date=$today;
	$pass->sheet_no=$lsn;
	$dates=date('y-m-d',strtotime($date));
	$pass->save();
	
	
	       $facility_stock=Facility_Stock::get_facility_drug_total($facility,$code)->toArray();	
			
			$mydata=array('facility_code'=>$facility,
			's11_No' => 'Delivery From KEMSA',
			'kemsa_code'=>$kemsaCode[$i],
			'batch_no'=>$batchNo[$i],
			'expiry_date'=>date('y-m-d',strtotime($Exp[$i])),
			'balanceAsof'=>$facility_stock[0]['balance'],
			'date_issued' => date('y-m-d'),
			'issued_to' => 'N/A',
			'issued_by' => $this -> session -> userdata('identity'));  
			 
			Facility_Issues::update_issues_table($mydata);  
	
	}
	//setting previous cycle's values to 0 then updating a fresh
	//echo 
		
		
	/******************************************option 1 the stocks exist*************************/
	 $get_delivered_items = Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("SELECT sum( fs.`balance` ) AS t_receipts, ft.kemsa_code, sum( fs.`balance` ) AS opening_bal
FROM facility_stock fs, facility_transaction_table ft
WHERE fs.facility_code = ft.facility_code
AND fs.balance >0
AND fs.kemsa_code = ft.kemsa_code
AND ft.facility_code = '$facility'
AND ft.availability = '1'
GROUP BY ft.kemsa_code");
	
	$get_o_b=Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("select sum( fs.`balance` ) as o_bal, fs.kemsa_code  from facility_stock fs where  fs.balance >0 and fs.stock_date < '$date' group by fs.kemsa_code ");
	  
	/******************* option 2 facility does not have the commidities*************************/
$get_pushed_items = Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("SELECT SUM( fs.balance ) AS o_bal, fs.kemsa_code
FROM facility_stock fs
WHERE fs.balance >0
AND fs.status =  '1'
AND fs.facility_code =  '$facility'
AND fs.kemsa_code NOT 
IN (SELECT kemsa_code
FROM facility_transaction_table
WHERE facility_code =  '$facility'
AND availability =  '1')
GROUP BY fs.kemsa_code");	  
	 //print_r($get_pushed_items);
	 
	  //echo $p;
	  $r = Doctrine_Manager::getInstance()->getCurrentConnection();	
	$r->execute("UPDATE `facility_issues` SET availability = 0 WHERE `facility_code`= '$facility'");
	  
		$q = Doctrine_Manager::getInstance()->getCurrentConnection();	
		$q->execute("UPDATE `facility_transaction_table` SET availability = 0 WHERE `facility_code`= '$facility'");  
	    $option_1_size=count( $get_delivered_items);
		          //  echo "iko hapa 0 . option_1_size $option_1_size";
					for($i=0;$i<$option_1_size;$i++){
						//echo "iko hapa 1";
						$t_rec=0;
						
						$facility=$facility;
										
						
						$id=$get_delivered_items[$i]['kemsa_code'];
							$get_o_b=Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("select sum( fs.`balance` ) as o_bal, fs.kemsa_code  from facility_stock fs where  fs.balance >0 and fs.stock_date < '$date' 
      and fs.kemsa_code='$id' group by fs.kemsa_code ");
						
						$o_bal=$get_o_b[0]['o_bal'];
						
						$facility=$facility;
						
						$get_closing_balance1 = Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("SELECT sum( fs.`balance` ) AS t_receipts, fs.kemsa_code
FROM facility_stock fs
WHERE fs.facility_code = '$facility'
AND fs.`status`='1'
AND fs.`balance`>0
AND fs.`stock_date`='$today'
AND fs.kemsa_code='$id'");

	$total=$get_closing_balance1[0]['t_receipts'];
	// $t_rec=$get_delivered_items[$i]['t_receipts'];
	// $f_total=$total+$t_rec-$t_rec;
	 $t_total=$o_bal+$total;
	 
						$transact1=new Facility_Transaction_table();
						$transact1->Facility_Code=$facility;
						$transact1->Opening_Balance=$o_bal;
						$transact1->Total_Receipts= $total;
						$transact1->Kemsa_Code=$id;
						$transact1->Comment="N/A";
						$transact1->Closing_Stock=$t_total;
						$transact1->date_t=$today;
						$transact1->availability=1;
						$transact1->save();
						
						
					}
					
	/*********************************option 2********************************/
 
	    
	    $option_2_size=count($get_pushed_items);
		//echo "option_2_size $option_2_size";
		
		/***********************************update the order details *****************************/
      $update_delivered_items = Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("SELECT sum( fs.`balance` ) AS t_receipts, fs.kemsa_code
FROM facility_stock fs
WHERE fs.facility_code = '$facility'
AND fs.`stock_date` = '$today'
AND fs.`balance`=fs.`quantity`
GROUP BY fs.kemsa_code");
		
					for($i=0;$i<$option_2_size;$i++){
						//echo "iko hapa 2";
						$o_bal=0;
						$id=$get_pushed_items[$i]['kemsa_code'];
					//	$o_bal=$get_pushed_items[$i]['o_bal'];						
						$facility=$facility;
						$get_closing_balance = Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("SELECT sum( fs.`balance` ) AS t_receipts, fs.kemsa_code
FROM facility_stock fs
WHERE fs.facility_code = '$facility'
AND fs.`status`='1'
AND fs.kemsa_code='$id'");
  
  $o_bal2=$get_closing_balance[0]['t_receipts']; 

			$transact=new Facility_Transaction_table();
						$transact->Facility_Code=$facility;
						$transact->Total_Receipts=$o_bal2;
						$transact->Opening_Balance=$o_bal; 
						$transact->Kemsa_Code=$id;
						$transact->Comment="N/A";
						$transact->Cycle_Date=$date;
						$transact->date_t=$today;
						$transact->Closing_Stock=$o_bal2;
						$transact->availability=1;
						$transact->save();
					}
	
	    
	    $option_3_size=count($update_delivered_items);
		
					for($i=0;$i<$option_3_size;$i++){
						//echo "iko hapa 3";
$r2 = Doctrine_Manager::getInstance()->getCurrentConnection();	
$r2->execute("UPDATE `orderdetails` 
SET quantityRecieved =".$update_delivered_items[$i]['t_receipts']." WHERE `orderNumber`= '$order' AND kemsa_code=".$update_delivered_items[$i]['kemsa_code']. "");
					}	
	
	/***************************8monitoring pushed items from kemsa**********************/
	
	$pushed_items_from_kemsa=Doctrine_Manager::getInstance()->getCurrentConnection()
      ->fetchAll("SELECT SUM( fs.balance ) AS o_bal, fs.kemsa_code, d.unit_cost
FROM facility_stock fs, drug d
WHERE fs.kemsa_code NOT 
IN (

SELECT kemsa_code
FROM orderdetails
WHERE  `orderNumber` =  '$order'
)
AND fs.balance >0
AND fs.kemsa_code=d.id
AND fs.status =  '1'
AND fs.`facility_code` =  '$facility'
GROUP BY fs.kemsa_code");


   $option_4_size=count($pushed_items_from_kemsa);
   
         for($i=0;$i<$option_4_size;$i++){
						
$r2 = Doctrine_Manager::getInstance()->getCurrentConnection();	
$r2->execute("insert into `orderdetails` 
SET price=".$pushed_items_from_kemsa[$i]['unit_cost']." , quantityRecieved =".$pushed_items_from_kemsa[$i]['o_bal']." ,orderNumber= '$order' , kemsa_code=".$pushed_items_from_kemsa[$i]['kemsa_code']. "");
					}
	
		$state=Doctrine::getTable('ordertbl')->findOneById($order);
		$state->deliverDate=$date_deliver;
		//$state->remarks=$comment;
		$state->reciever_name=$rname;
		$state->reciever_phone=$rphone;
		$state->deliverDate=$date_deliver;
		$state->dispatchby=$dispby;
		$state->dispatchDate=$dispdate;
		$state->warehouse=$warehouse;
		$state->status=0;
		$state->orderStatus='delivered';
		$state->save();
		
		   
		$this->session->set_flashdata('system_success_message', 'Stock details have been updated');
		$this->send_stock_update_sms();
		
$detail_list=Orderdetails::get_order($order);
$dates=Ordertbl::get_dates($order);
		
$table_body="";
$total_fill_rate=0;
$order_value =0;

$ts1 = strtotime(date($dates["orderDate"]));
$ts2 = strtotime(date($dates["deliverDate"]));

$seconds_diff = $ts2 - $ts1;

$date_diff= floor($seconds_diff/3600/24);

 $tester= count($detail_list);

      if($tester==0){
      	
      }
	  else{
	  	

      
		foreach($detail_list as $rows){
			//setting the values to display
			 $received=$rows->quantityRecieved;
			 $price=$rows->price;
			 $ordered=$rows->quantityOrdered;
			 $code=$rows->kemsa_code;
			 
			 $total=$price* $ordered;
			 
			 
			 
		     if($ordered==0){
				$ordered=1;
			}

		 foreach($rows->Code as $drug) {
		 	
			 $drug_name=$drug->Drug_Name;
			 $kemsa_code=$drug->Kemsa_Code;
			 $unit_size=$drug->Unit_Size;
			 $total_units=$drug->total_units;
			 
			foreach($drug->Category as $cat){
				
			$cat_name=$cat;		
			}	 
		}
		   $received=round($received/$total_units);
		    $fill_rate=round(($received/$ordered)*100);
	        $total_fill_rate=$total_fill_rate+$fill_rate;
			
		 switch ($fill_rate) {
		case $fill_rate==0:
		 $table_body .="<tr style=' background-color: #FBBBB9;'>";
		 $table_body .= "<td>$cat_name</td>";
	     $table_body .= '<td>'.$drug_name.'</td><td>'. $kemsa_code.'</td>'.'<td>'.$unit_size.'</td>';
		 $table_body .='<td>'. $price.'</td>';
		 $table_body .='<td>'.$ordered.'</td>';
		 $table_body .='<td>'.number_format($total, 2, '.', ',').'</td>';
		 $table_body .='<td>'.$received.'</td>';	
		 $table_body .='<td>'.$fill_rate .'% '.'</td>';
		 $table_body .='</tr>'; 
				 break;  	
				 
		 case $fill_rate<=60:
		 $table_body .="<tr style=' background-color: #FAF8CC;'>";
		 $table_body .= "<td>$cat_name</td>";
	     $table_body .= '<td>'.$drug_name.'</td><td>'. $kemsa_code.'</td>'.'<td>'.$unit_size.'</td>';
		 $table_body .='<td>'. $price.'</td>';
		 $table_body .='<td>'.$ordered.'</td>';
		 $table_body .='<td>'.number_format($total, 2, '.', ',').'</td>';
		 $table_body .='<td>'.$received.'</td>';	
		 $table_body .='<td>'.$fill_rate .'% '.'</td>';
		 $table_body .='</tr>'; 
				 break;  
				 
				 case $fill_rate==100.01 || $fill_rate>100.01:
		 $table_body .="<tr style=' background-color: #FBBBB9;'>";
		 $table_body .= "<td>$cat_name</td>";
	     $table_body .= '<td>'.$drug_name.'</td><td>'. $kemsa_code.'</td>'.'<td>'.$unit_size.'</td>';
		 $table_body .='<td>'. $price.'</td>';
		 $table_body .='<td>'.$ordered.'</td>';
		 $table_body .='<td>'.number_format($total, 2, '.', ',').'</td>';
		 $table_body .='<td>'.$received.'</td>';	
		 $table_body .='<td>'.$fill_rate .'% '.'</td>';
		 $table_body .='</tr>'; 
				 break;
				  
			 case $fill_rate==100:
		 $table_body .="<tr style=' background-color: #C3FDB8;'>";
		 $table_body .= "<td>$cat_name</td>";
	     $table_body .= '<td>'.$drug_name.'</td><td>'. $kemsa_code.'</td>'.'<td>'.$unit_size.'</td>';
		 $table_body .='<td>'. $price.'</td>';
		 $table_body .='<td>'.$ordered.'</td>';
		 $table_body .='<td>'.number_format($total, 2, '.', ',').'</td>';
		 $table_body .='<td>'.$received.'</td>';	
		 $table_body .='<td>'.$fill_rate .'% '.'</td>';
		 $table_body .='</tr>'; 
				 break;
				 
				 default :
		 $table_body .="<tr>";
		 $table_body .= "<td>$cat_name</td>";
	     $table_body .= '<td>'.$drug_name.'</td><td>'. $kemsa_code.'</td>'.'<td>'.$unit_size.'</td>';
		 $table_body .='<td>'. $price.'</td>';
		 $table_body .='<td>'.$ordered.'</td>';
		 $table_body .='<td>'.number_format($total, 2, '.', ',').'</td>';
		 $table_body .='<td>'.$received.'</td>';	
		 $table_body .='<td>'.$fill_rate .'% '.'</td>';
		 $table_body .='</tr>'; 
				 break;
			
		 }
		 
				  } 
	
	$order_value  = round(($total_fill_rate/count($detail_list)),0,PHP_ROUND_HALF_UP);
	}
	
	$message=<<<HTML_DATA
<table id="main1" width="100%">
	<thead>
		<tr>
		<th colspan='9'>
         <p style="letter-spacing: 1px;font-weight: bold;text-shadow: 0 1px rgba(0, 0, 0, 0.1);font-size: 14px;">
         Facility Order No $order| KEMSA Order No | Total Order FIll Rate $order_value %;| Order lead Time $date_diff; day(s)</p>
		</th>
		</tr>
		<tr>
		<th width="50px" style="background-color: #C3FDB8; "></th>
		<th>Full Delivery 100%</th>
		<th width="50px" style="background-color:#FFFFFF"></th>
		<th>Ok Delivery 60%-less than 100%</th>
		<th width="50px" style="background-color:#FAF8CC;"></th> 
		<th>Partial Delivery less than 60% </th>
		<th width="50px" style="background-color:#FBBBB9;"></th>
		<th>Problematic Delivery 0% or over 100%</th>
		<th></th>
		</tr>
		<tr>
		<th><strong>Category</strong></th>
		<th><strong>Description</strong></th>
		<th><strong>KEMSA&nbsp;Code</strong></th>
		<th><strong>Unit Size</strong></th>
		<th><strong>Unit Cost Ksh</strong></th>
		<th><strong>Quantity Ordered</strong></th>
		<th><strong>Total Cost</strong></th>
		<th><strong>Quantity Received</strong></th>
		<th><strong>Fill rate</strong></th>	
		</tr>
	</thead>
	<tbody>
	
		 $table_body;
	
	</tbody>
</table>
HTML_DATA;

        $ts1 = date('d M y',strtotime(date($dates["orderDate"])));
        $ts2 = date('d M y');
		
		$message_1='<br>The Order Made for '.$this -> session -> userdata('full_name').' on  '.$ts1.'  has been received at the facility on. '.$ts2.'
		<br>
		Order Fill Rate = '.$order_value.'%
		<br>
		Order Lead Time (from placement – receipt) = '.$date_diff.'% days
		<br>
		<br>
		<br>';		
		
		$subject='Order Report For '.$fac_name;
		
		$this->send_order_delivery_email($message_1.$message,$subject,null);
		redirect('order_management/new_order');

		//$this -> load -> view("template", $data);
	
}
 function getWorkingDays($startDate,$endDate,$holidays){
    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    if($startDate!=NULL && $endDate!=NULL){
    $days = ($endDate->getTimestamp() - $startDate->getTimestamp()) / 86400 + 1;
    $no_full_weeks = floor($days / 7);
    $no_remaining_days = fmod($days, 7);
    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N",$startDate->getTimestamp());
    $the_last_day_of_week = date("N",$endDate->getTimestamp());

    // echo              $the_last_day_of_week;

    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here

    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.

    if ($the_first_day_of_week <= $the_last_day_of_week){

        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;

        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;

    }
    else{

        if ($the_first_day_of_week <= 6) {

        //In the case when the interval falls in two weeks, there will be a Sunday for sure

            $no_remaining_days--;

        }

    }
    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder

//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it

   $workingDays = $no_full_weeks * 5;

    if ($no_remaining_days > 0 )

    {

      $workingDays += $no_remaining_days;

    }
    //We subtract the holidays

/*    foreach($holidays as $holiday){

        $time_stamp=strtotime($holiday);

        //If the holiday doesn't fall in weekend

        if (strtotime($startDate) <= $time_stamp && $time_stamp <= strtotime($endDate) && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)

            $workingDays--;

    }*/
    return round ($workingDays-1);
    }
return NULL;
}
    
}