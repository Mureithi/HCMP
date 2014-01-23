<style>
 
.leftpanel{
        width: 15%;
    	height:auto;
    	float: left;
    	margin-right: 0%;
}
.rightpanel{
    width: 84%;
    min-height:100%;
    float: left;
    -webkit-box-shadow: 2px 2px 6px #888;
	box-shadow: 2px 2px 6px 2px #888; 
    margin-left:1.5em;
    margin-bottom:1em;
}
</style>
<div class="page_lay_out">
<div class="leftpanel">
<div class="dropdown">
	
              <ul class="dropdown-menu" 
              role="menu" aria-labelledby="dropdownMenu" 
              style="display: block; position: static; margin-bottom: 5px; width: 100%; height:50%; ">
                <li><a tabindex="-1" href="#" id="consumption"><h3>Consumption</h3></a></li>                
                <li><a tabindex="-1" href="#" id="stock_level"><h3>Stock Levels</h3></a></li>              
                <li><a tabindex="-1" href="#" id='expiries'><h3>Expiries</h3></a></li>
                <li><a tabindex="-1" href="#" id="system_usage"><h3>System Up take</h3></a></li>
              </ul>
            </div>
</div>

<div class="rightpanel" style="overflow: scroll; "></div>

</div>
<script type="text/javascript">
	$(document).ready(function() {

		$('#system_usage').focus();
		var url = "<?php echo base_url().'report_management/get_county_facility_mapping_data/'?>";	
		ajax_request_special(url,'.rightpanel','','system_usage');	
		$("#system_usage").click(function(){	
		var url = "<?php echo base_url().'report_management/get_county_facility_mapping_data/'?>";	
		ajax_request_special(url,'.rightpanel','','system_usage');	
	    });
	    
	    $("#consumption").click(function(){
		var url = "<?php echo base_url().'report_management/get_county_consumption_level_new/'?>";	
		ajax_request_special(url,'.rightpanel','','consumption');	
	    });
	    
	     $("#stock_level").click(function(){
		var url = "<?php echo base_url().'report_management/get_county_stock_level_new/'?>";	
		ajax_request_special(url,'.rightpanel','','stock_level');	
	    });
	    
	     $("#expiries").click(function(){
		var url = "<?php echo base_url().'report_management/get_county_cost_of_expiries_new/'?>";	
		ajax_request_special(url,'.rightpanel','','expiries');	
	    });
	    
	    $("#filter_system_usage").live( "click", function() {
        var url = "<?php echo base_url().'report_management/get_county_facility_mapping_data/'?>"+$("#year_filter").val()+"/"+$("#month_filter").val();	
		ajax_request_special(url,'.rightpanel','','system_usage');
          });
		
	    $("#filter_consumption").live( "click", function() {
        var url = "<?php echo base_url().'report_management/get_county_consumption_level_new/'?>"+
        $("#year_filter").val()+"/"+$("#month_filter").val()+"/"+$("#commodity_filter").val()+"/null/"+$("#district_filter").val()+"/"+$("#plot_value_filter").val();	
		ajax_request_special(url,'.rightpanel','','consumption');
		
          });
          
         $("#filter_stock_level").live( "click", function() {
        var url = "<?php echo base_url().'report_management/get_county_stock_level_new/'?>"+
        $("#commodity_filter").val()+"/null/"+$("#district_filter").val()+"/"+$("#plot_value_filter").val();	
		ajax_request_special(url,'.rightpanel','','stock_level');
		
          });
          
           $("#filter_expiries").live( "click", function() {
        var url = "<?php echo base_url().'report_management/get_county_cost_of_expiries_new/'?>"+
         $("#year_filter").val()+"/"+$("#district_filter").val()+"/"+$("#commodity_filter").val()+"/"+$("#plot_value_filter").val();	
		ajax_request_special(url,'.rightpanel','','expiries');
		
          });
		
	function ajax_request_special(url,checker,date,option){
	var url =url;
	var checker=checker;
	
	var loading_icon="<?php echo base_url().'Images/loader.gif' ?>";
	 $.ajax({
          type: "POST",
          url: url,
          beforeSend: function() {
          	
          	if(checker==".rightpanel"){
          	 $(".rightpanel").html("<img style='margin-left:20%;' src="+loading_icon+">");	
          	}else{
          	 $('.rightpanel').html("");	
          	}

          },
          success: function(msg) {
          	if(checker==".rightpanel"){	
          		
          $(".rightpanel").html(""); 	
          $(".rightpanel").html(msg); 
          
          }
          else{
        
          }

        if(option=='system_usage'){
        	$("#temp").prepend('<div class="label label-info">Select filter Options</div>'+
'<select name="year" id="year_filter">'+
'<option value="2014">2014</option>'+
'<option value="2013">2013</option></select>'+
'<select name="month" id="month_filter">'+
'<option value="01">Jan</option>'+
'<option value="02">Feb</option>'+
'<option value="03">Mar</option>'+
'<option value="04">Apr</option>'+
'<option value="05">May</option>'+
'<option value="06">Jun</option>'+
'<option value="07">Jul</option>'+
'<option value="08">Aug</option>'+
'<option value="09">Sep</option>'+
'<option value="10">Oct</option>'+
'<option value="11">Nov</option>'+
'<option value="12">Dec</option>'+
'</select>'+
'<a id="filter_system_usage" href="#"><span class="label label-success">Filter</span></a>');
        }
          }
        }); 
}
		
	});
</script>


