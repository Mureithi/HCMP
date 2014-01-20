<script>
	$(document).ready(function() {
		<?php $data_response=count(json_decode($category_data));  if($data_response>0): ?>
	   $('#graph_div').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Commodity stock level for <?php $header=""; echo $month." ".$year." ".$county;?>',
                x: -20 //center
            },
            credits: { enabled:false},
            xAxis: {
                categories: <?php echo $category_data; ?>
            },
            yAxis: {
                title: {
                    text: 'stock level in <?php echo $consumption_option; ?>'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '<?php echo $consumption_option; ?>'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
            
            <?php  echo "{ name: 'level', data:".$series_data."}"?>
          ]
        });
        <?php else: ?>
		 var loading_icon="<?php echo base_url().'Images/no-record-found.png'; 
		 $header="<br><div align='center' class='label label-info '>Commodity stock level for  $month $year $county</div>" ?>";
		 $("#graph_div").html("<img style='margin-left:20%;' src="+loading_icon+">")
		  <?php endif; ?>
 });
	
</script>
<div  class='label label-info'>Below is the stock level in the county</div><br>
<div class="label label-info">Select filter Options</div>

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
<option value="packs">Packs</option>
<option value="units">Units</option>
<option value="ksh">KSH</option>
</select>
<a id="filter_stock_level" href="#"><span class="label label-success">Filter</span></a>
<?php echo $header; ?>
<div id="graph_div"  style="height:100%; width: 100%; margin: 0 auto; float: left"></div>