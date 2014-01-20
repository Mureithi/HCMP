		<script type="text/javascript">
$(function () {
	<?php $header="";   if($total>0): ?>
        $('#container').highcharts({
            chart: {
            },
         
            credits: { enabled:false},
            title: {
                text: 'Expiries for <?php echo $county." ".$year?>'
            },
            xAxis: {
                categories: <?php echo $category_data; ?>
            },
             yAxis: {
                title: {
                    text: '<?php echo $expiry_option;?>'
                   },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                    var s;
                    if (this.point.name) { // the pie chart
                        s = ''+
                            this.point.name +': '+ this.y +'<?php echo $expiry_option;?>';
                    } else {
                        s = ''+
                            this.x  +': '+ this.y;
                    }
                    return s;
                }
            },
        labels: {
                items: [{
                    html: 'Total',
                    style: {
                        left: '0px',
                        top: '0px',
                        color: 'black'
                      
                    }
                }]
            },
            series: [
            <?php  
                  foreach($series_data_monthly as $key=>$raw_data):
					 $temp_array=array();
					 echo "{ type: 'line', name: '$key', data:";
					 
					  foreach($raw_data as $key_data):
						$temp_array=array_merge($temp_array,array((int)$key_data));
						  endforeach;
					  echo json_encode($temp_array)."},";
					  
				   endforeach;
            
              ?>
              {	type: 'pie',
              name: 'Total',
              data : [
                <?php  
                $count=0;
                  foreach($series_data_monthly_total as $key=>$raw_data):
				
					 echo "{
					 	    name: '$key', 
					        y:$raw_data,
					        color: Highcharts.getOptions().colors[$count]
					        },";
		         $count++;
				 endforeach;
                    ?>
              ],
                center: [50, 60],
                size: 100,
                showInLegend: true,
                dataLabels: {
                enabled: true
                }

            }]
        });
        <?php else: ?>
		 var loading_icon="<?php echo base_url().'Images/no-record-found.png'; 
		 $header="<br><div align='center' class='label label-info '>Expiries for $county $year </div>" ?>";
		 $("#container").html("<img style='margin-left:20%;' src="+loading_icon+">")
		  <?php endif; ?>
    });
		</script>
<div  class='label label-info'>Below is the expiries in the county</div><br>
<div class="label label-info">Select filter Options</div>
<select name="year" id="year_filter">
<option value="2014">2014</option>
<option value="2013">2013</option>
</select>

<div class="label label-info">Commodity</div>
<select id="commodity_filter">
<option value="null">All</option>
<?php
foreach($c_data as $data):
		$commodity_name=$data['drug_name'];	
		$commodity_id=$data['id'];
		echo "<option value='$commodity_id'>$commodity_name</option>";
endforeach;
?>
</select>
<div class="label label-info">District</div>
<select id="district_filter">
<option value="null">All</option>
<?php
foreach($district_data as $district_):
		$district_id=$district_->id;
		$district_name=$district_->district;	
		echo "<option value='$district_id'>$district_name</option>";
endforeach;
?>
</select>
<div class="label label-info">Plot Value</div>
<select id="plot_value_filter">
<option value="ksh">KSH</option>
<option value="packs">Packs</option>
<option value="units">Units</option>
</select>
<a id="filter_expiries" href="#"><span class="label label-success">Filter</span></a>
<?php echo $header; ?>
		<div id="container" style="width: 100%; height: 100%; margin: 0 auto"></div>