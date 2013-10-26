<SCRIPT LANGUAGE="Javascript" SRC="<?php echo base_url();?>Scripts/FusionCharts/FusionCharts.js"></SCRIPT>
<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>Scripts/jquery.dataTables.js"></script>
    <style type="text/css" title="currentStyle">
      
      @import "<?php echo base_url(); ?>DataTables-1.9.3 /media/css/jquery.dataTables.css";
    </style>
 
    <style>

      .warning2 {
  background: #FEFFC8 url('<?php echo base_url()?>Images/excel-icon.jpg') 20px 50% no-repeat;
  border: 1px solid #F1AA2D;
  }
    </style>

    <script type="text/javascript" charset="utf-8">
      
      $(document).ready(function() {
      	//ajax reuest for the graphs
        var url = "<?php echo base_url()."report_management/get_stock_status_ajax/ajax-request_county"?>";			
		var div="#chart3";
		
		ajax_request(url,div);
		
		var url = "<?php echo base_url()."report_management/get_stock_status_ajax/consumption"?>";			
		 div="#chart_1";
		
		ajax_request(url,div);
		
		var url = "<?php echo base_url().'report_management/get_costofexpiries_chart_ajax/county/'?>"
		var div="#chart2";
		
		ajax_request (url,div);
		
		
		
		$('#desc').change(function() {
         var div="#chart3";
		var url = "<?php echo base_url()."report_management/get_stock_status_ajax/ajax-request_county"?>";		
		url=url+"/"+$(this).val();
		
		if($(this).val() =='default') {
			return;	
		}else{
		ajax_request (url,div)
		}
		
		
		});
		
		
		function ajax_request (url,div){
	var url =url;
	var loading_icon="<?php echo base_url().'Images/loader.gif' ?>";
	 $.ajax({
          type: "POST",
          url: url,
          beforeSend: function() {
            $(div).html("");
            
             $(div).html("<img style='margin-top:-10%;' src="+loading_icon+">");
            
          },
          success: function(msg) {
          $(div).html("");
            $(div).html(msg);           
          }
        }); 
}
    
  
     
    var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/Bar2D.swf"?>","ChartId", "100%", "80%", "0", "1" );
    var url = '<?php echo base_url()."report_management/expired_commodities_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart1");

     var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/Line.swf"?>", "ChartId3", "100%", "70%", "0", "0");
    var url = '<?php echo base_url()."report_management/orders_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart4");

      var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/Line.swf"?>", "ChartId4", "100%", "80%", "0", "0");
    var url = '<?php echo base_url()."report_management/generate_costofordered_County_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart5");

     var chart = new FusionCharts("<?php echo base_url()."scripts/FusionWidgets/AngularGauge.swf"?>", "ChartId5", "100%", "80%", "0", "0");
    var url = '<?php echo base_url()."report_management/cummulative_fill_rate_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart6");


     var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/StackedColumn2D.swf"?>", "ChartId6", "100%", "80%", "0", "0");
    var url = '<?php echo base_url()."report_management/district_drawing_rights_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart7");

     var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/Pie3D.swf"?>", "ChartId7", "100%", "80%", "0", "0");
    var url = '<?php echo base_url()."report_management/orders_placed_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart8");

     var chart = new FusionCharts("<?php echo base_url()."scripts/FusionWidgets/HLinearGauge.swf"?>", "ChartId8", "100%", "20%", "0", "0");
    var url = '<?php echo base_url()."report_management/lead_time_chart_county"?>'; 
    chart.setDataURL(url);
    chart.render("chart9");
    
    var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/StackedColumn2D.swf"?>", "ChartId9", "100%", "80%", "0", "0");
    var url = '<?php echo base_url()."report_management/get_county_ordering_rate_chart"?>'; 
    chart.setDataURL(url);
    chart.render("chart10");
    
    var chart = new FusionCharts("<?php echo base_url()."scripts/FusionCharts/MSColumn2D.swf"?>", "ChartId10", "100%", "80%", "0", "0");
    var url = '<?php echo base_url()."report_management/get_county_drawing_rights_data"?>'; 
    chart.setDataURL(url);
    chart.render("chart11");


  });
  </script>
  <style>
  .dash_container {
		width: 100%;
		height:700px;
		padding-top:1%;
		
	}
	.third,.two-third,.half{
		vertical-align:top;
		display:inline-block;
		border:1px solid #ddd;
		height:100%;
	}
   .third{
   	width:33%;
   }
   .two-third{
   	width:66%;
   }
   .half{
   	width:49%;
   }
   .half.within{
   		height:90%;
   }
   .half.within:nth-child(even){
   		float:right;
   }
   div.half .within{
   	height:100%;
   }
   .div-row{
   	margin:auto;
   	height:30%;
   	width:95%;
   	margin-top:10px;
   }
   .div-row h3{
   	padding:5px;
   	margin-top:0;
   	background:#ddd;
   	font-size:1.5em;
   	margin-bottom:0;
   }
  
</style>
  
<div class="dash_container">
	<div class="div-row">
		<div class="third" style="overflow: auto;">
			<h3>Consumption for year</h3>
			<select  class="drop">
      <option value="default">Select</option>
      <option value="r_h">Reproductive Health</option>
      <option value="malaria">Malaria</option>
      <option value="all">All Commodities</option>
			
	</select>
			<div id="chart_1"></div>
			</div>
		<div class="third" style="overflow: auto;">
			<h3>Stock Status as at Today</h3>
			<select id="desc" name="desc" class="drop">
      <option value="default">Select</option>
      <option value="r_h">Reproductive Health</option>
      <option value="malaria">Malaria</option>
      <option value="all">All Commodities</option>
			
	</select>
	<div id="chart3"></div>
			</div>
		<div class="third" style="overflow: auto;"><h3>Notifications</h3>
			<?php echo $stats; ////// ?>
		</div>
	</div>
	<div class="div-row">		
		<div class="third"><h3>Expired Commodities - Trend</h3>
			<div id="chart2"></div>
		</div>
		
		<div class="third"><h3>Order Analysis</h3>
		<div class="half within"><h3>Cost</h3>
			<div id="chart5"></div>
		</div>
		
		<div class="half within"><h3>Fill Rate</h3></div>
		</div>
		<div class="third"><h3>Lead Time</h3>
			<div id="chart9" ></div>
		</div>
	</div>
	<div class="div-row">
		<div class="two-third"><h3>System coverage</h3>
			<?php echo $coverage_data;  ?>
		</div>
		<div class="third"><h3>User Access</h3>
			
		</div>
	</div>
	
	
 </div> 
 

  
  
            
 
 