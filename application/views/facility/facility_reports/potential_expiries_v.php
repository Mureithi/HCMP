<style>
table.data-table1 {
	border: 1px solid #000033;
	margin: 10px auto;
	border-spacing: 0px;
	}
	
table.data-table1 th {
	border: none;
	color:#036;
	text-align:center;
	font-size: 13.5px;
	border: 1px solid #000033;
	border-top: none;
	max-width: 600px;
	}
table.data-table1 td, table th {
	padding: 4px;
	}
table.data-table1 td {
	border: none;
	border-left: 1px solid #000033;
	border-right: 1px solid #000033;
	height: 30px;
	width: 130px;
	font-size: 12.5px;
	margin: 0px;
	border-bottom: 1px solid #000033;
	}
.col5{
	background:#C9C299;
	}
	.try{
		float: right;
		margin-bottom: 1px auto;
	}
	.whole_report{
	      
    position: relative;
  width: 88%;
  background: #FFFAF0;
  -moz-border-radius: 4px;
  border-radius: 4px;
  padding: 2em 1.5em;
  color: rgba(0,0,0, .8);
  
  line-height: 1.5;
  margin: 20px auto;
  -webkit-box-shadow: 0px 0px 10px rgba(0,0,0,.8);
   -moz-box-shadow: 0px 0px 10px rgba(0,0,0,.8);
   box-shadow: 0px 0px 10px rgba(0,0,0,.8);	
	}
	
</style>
<script>
$(document).ready(function(){
	 //default call
	 //////
    $("#3months").click(function(){
      var url = "<?php echo base_url().'stock_expiry_management/get_expiries/'.$facility_code?>";
      var id  = $(this).attr("id");
      //alert (id);
        $.ajax({
          type: "POST",
          data: {'id':  $(this).attr("id"),},
          url: url,
          beforeSend: function() {
            $(".reportDisplay").html("");
          },
          success: function(msg) {
            $(".reportDisplay").html(msg);
            
             }
         });
    });
    $("#6months").click(function(){
      var url = "<?php echo base_url().'stock_expiry_management/get_expiries/'.$facility_code?>";
      var id  = $(this).attr("id");
      //alert (id);
        $.ajax({
          type: "POST",
          data: {'id':  $(this).attr("id"),},
          url: url,
          beforeSend: function() {
            $(".reportDisplay").html("");
          },
          success: function(msg) {
            $(".reportDisplay").html(msg);
            
             }
         });
    });
    $("#12months").click(function(){
      var url = "<?php echo base_url().'stock_expiry_management/get_expiries/'.$facility_code?>";
      var id  = $(this).attr("id");
      //alert (id);
        $.ajax({
          type: "POST",
          data: {'id':  $(this).attr("id"),},
          url: url,
          beforeSend: function() {
            $(".reportDisplay").html("");
          },
          success: function(msg) {
            $(".reportDisplay").html(msg);
            
             }
         });
    });
        });  
</script>

<fieldset>

		<legend></legend>
	<?php
	
	echo ($this -> session -> userdata('user_type_id')==10)? 
	"<h2 class='awesome blue'>Commodities Expiring Between ".date('d M Y')." and ".date('d M Y', strtotime('+6 months')).":</h2>" :'<h2>Commodities Expiring in the Next:</h2>  
	<button id="3months" class="awesome blue" style="margin-left:1%; margin-top: 0.5em;";> Next 3 Months</button> 
	<button id="6months" class="awesome blue" style="margin-left:1%; margin-top: 0.5em;";> Next 6 Months</button>
	<button id="12months" class="awesome blue" style="margin-left:1%; margin-top: 0.5em;";> Next 12 Months</button>' ; 
	
	?>	
		
	
	
	
</fieldset>
<div class="whole_report">
	<!--<div class="try">
<button class="button">Download PDF</button>
</div>-->
<div>
	<img src="<?php echo base_url().'Images/coat_of_arms.png'?>" style="position:absolute;  width:90px; width:90px; top:0px; left:0px; margin-bottom:-100px;margin-right:-100px;"></img>
       
       <span style="margin-left:100px;  font-family: arial,helvetica,clean,sans-serif;display: block; font-weight: bold; font-size: 15px;">
     Ministry of Health</span><br>
       <span style=" font-size: 12px;  margin-left:100px;">Health Commodities Management Platform</span><span style="text-align:center;" >
       	<h2 style="text-align:center; font-size: 20px;"><?php echo $facility_data['facility_name'].' MFL '.$facility_data['facility_code']?> Potential Expiries</h2>
       
       
      
       	<hr/> 
        
        	
</div>


<table class="data-table1">
<thead>
	<tr>
		<th><strong>Kemsa Code</strong></th>
		<th><strong>Description</strong></th>
		<th>Batch No Affected</th>
		<th>Manufacturer</th>
		<th><strong>Expiry Date</strong></th>
		<th><strong>Unit size</strong></th>
		<th>Stock Expired (Packs)</th>
		<th><strong>Unit Cost</strong></th>
	    <th>Total Cost(KSH)</th>
	</tr>
	</thead>
	<tbody>
		
		<?php  $total=0;
				foreach ($report as $drug ) { ?>
					
					<?php foreach($drug->Code as $d){ 
								$name=$d->Drug_Name;
								$code=$d->Kemsa_Code;
					            $unitS=$d->Unit_Size; 
								$unitC=$d->Unit_Cost;
								$calc=$drug->balance;
								$actual_units=$d->total_units;
						      
								$calc=round($calc/$actual_units);
								$total_expired=$calc*$unitC;
								$total=round($total+$total_expired,1);
								$thedate=$drug->expiry_date;
								$formatme = new DateTime($thedate);
								 $myvalue= $formatme->format('d M Y');
								?>
				
						<tr>
							
							<td><?php echo $code;?> </td>
							<td><?php echo $name;?></td>
							<td><?php echo $drug->batch_no;?> </td>
							<td><?php echo $drug->manufacture;?> </td>
							<td><?php echo $myvalue;?></td>
							<td> <?php echo $unitS;?> </td>
							<td> <?php echo $calc;?> </td>
							<td> <?php echo $unitC;?> </td>
							<td><?php echo number_format($total_expired, 2, '.', ',');?> </td>
							
						</tr>
					<?php }
							?>		
		
		
		<?php }
					?>	
					<tr><td colspan="7" ></td><td><b>TOTAL (KSH) </b></td><td><b><?php echo number_format($total, 2, '.', ','); ?></b></td></tr>
					</tbody>
	 
</table>



<?php 

if ($option1=' ') { ?>
	<input type="hidden"  id="interval" name="interval" value="12_months" />
	<?php
} else { ?>
	<input type="hidden"  id="interval" name="interval" value="<?php echo $option ?>" />
	<?php
}

?>



		</div>