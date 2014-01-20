<table   class="table table-hover table-bordered table-update" id="example" width="100%" >
					<thead>
					<tr>
						<th style="text-align:center; font-size: 14px"><b>Service Point</b></th>
						<th style="text-align:center; font-size: 14px"><b>Description</b></th>
						<th style="text-align:center; font-size: 14px"><b>KEMSA Code</b></th>
						<th style="text-align:center; font-size: 14px"><b>Unit Size</b></th>
						<th style="text-align:center; font-size: 14px"><b>Batch No</b></th>
						<th style="text-align:center; font-size: 14px"><b>Expiry Date</b></th>
						<th style="text-align:center; font-size: 14px"><b>Available Stock</b></th>
						<th style="text-align:center; font-size: 14px"><b>Issued Quantity</b></th>
					    <th style="text-align:center; font-size: 14px"><b>Issue Date </b></th>
						<th style="text-align:center; font-size: 14px"><b>Remove</b></th> 				    
					</tr>
					</thead>
					<tbody>
						<tr row_id='0'>
						<td>
						<select  name="Servicepoint[0]" class="service_point">
						<option>-Select-</option>
						<option value="CCC">CCC</option>
						<option value="Pharmacy">Pharmacy</option>
						<option value="Lab">Lab</option>
						<option value="Maternity">Maternity</option>
						<option value="Injection Room">Injection Room</option>
						<option value="Dressing room">Dressing room</option>
						<option value="TB Clinic">TB Clinic</option>
						<option value="MCH">MCH</option>
						<option value="Diabetic Clinic">Diabetic Clinic</option>
					</select>
						</td>
						<td>
	<select class="desc" name="desc">
    <option title="0">-Select Commodity -</option>
		<?php 
		foreach ($drugs as $drugs) :			
			foreach ($drugs->Code as $d):
			$drugname=$d->Drug_Name;
			$code=$d->id;
			$unit=$d->Unit_Size;
			$kemsa_code=$d->Kemsa_Code;
			
			 echo "<option title='$code^$unit^$kemsa_code' value='$code'> $drugname</option>";?> 
		<?php endforeach;endforeach;?>
	</select>
						</td>
						<td>
						<input type="hidden" id="0" name="commodity_id[0]" value="" class="commodity_id"/>
						<input type="hidden" name="commodity_balance" value="" class="commodity_balance"/>	
						<input type="text" class="input-small kemsa_code" readonly="readonly" name="kemsa_code[]"/></td>
			            <td><input  type="text" class="input-small unit_size" readonly="readonly"  /></td>
						<td><select class=" input-small batchNo" name="batchNo[]"></select></td>
						<td><input class='input-small exp_date'  name='expiry_date[]' readonly="readonly" type='text' /></td>
						<td><input class='input-small AvStck' type="text" name="AvStck[]" readonly="readonly" value=""/></td>
						<td><input class='input-small Qtyissued' type="text" name="Qtyissued[0]" value="" /></td>
						<td><input class='input-small my_date' type="text" name="date_issue[0]"  value="" /></td>
						<td><a class="add label label-success">Add Row</a><a class="remove label label-important" style="display:none;">Remove Row</span></td>
			</tr>
		            </tbody>					
</table>
<script>
/***************clone the row here*********************/
		$(".add").click(function() {
			
			var last_row = $('#example tr:last');
            var cloned_object = last_row.clone(true);
           
             //find the last row
            var table_row = cloned_object.attr("row_id");
			var next_table_row = parseInt(table_row) + 1;
			//reset the values of current element 
			cloned_object.attr("row_id", next_table_row);
			cloned_object.find(".service_point").attr('name','Servicepoint['+next_table_row+']'); 
			cloned_object.find(".commodity_id").attr('name','commodity_id['+next_table_row+']'); 
			cloned_object.find(".commodity_id").attr('id',next_table_row); 
			cloned_object.find(".Qtyissued").attr('name','Qtyissued['+next_table_row+']'); 	
			cloned_object.find(".my_date").attr('name','date_issue['+next_table_row+']'); 				
            cloned_object.find("input").attr('value',"");            
            cloned_object.find(".batchNo").html("");            
			cloned_object.find(".remove").show();
			cloned_object.insertAfter('#example tr:last');
	
			refreshDatePickers();

		});
		
		/// remove the row
		$('.remove').live('click',function(){
			var row_id=$(this).closest("tr").find(".row_id").val();
			
	     $(this).parent().parent().remove();
    
      });
      
      ///when changing the commodity
      		$(".desc").live('change',function(){
      		var locator=$('option:selected', this);
			var data =$('option:selected', this).attr('title'); 
	       	var data_array=data.split("^");
	       	
	        locator.closest("tr").find(".unit_size").val(data_array[1]);
	     	locator.closest("tr").find(".kemsa_code").val(data_array[2]);
	     	
	     	locator.closest("tr").find(".AvStck").val("");
	     	locator.closest("tr").find(".exp_date").val("");
	     	locator.closest("tr").find(".Qtyissued").val("");
	     	locator.closest("tr").find(".my_date").val("");	     	

			json_obj={"url":"<?php echo site_url("order_management/getBatch");?>",}
			var baseUrl=json_obj.url;
			var id=data_array[0];
            var dropdown="<option title=''>--select Batch--</option>";
            var new_date='';
            var bal='';
            var commodity_stock_row_id='';
            var total=0;
			$.ajax({
			  type: "POST",
			  url: baseUrl,
			  data: "desc="+id,
			  success: function(msg){

			  		var values=msg.split("_");
			  		
			  		var txtbox;
			  		for (var i=0; i < values.length-1; i++) {
			  			var id_value=values[i].split("*")
			  			if(i==0){
			  				dropdown+="<option selected='selected' title="+id_value[2]+"^"+id_value[3]+"^"+id_value[5]+">";
			  				 new_date=$.datepicker.formatDate('d M yy', new Date(id_value[2]));
			  				 bal=id_value[3];
			  				 commodity_stock_row_id=id_value[5];
			  			}else{
			  				dropdown+="<option title="+id_value[2]+"^"+id_value[3]+"^"+id_value[5]+">";
			  			}
			  			
						dropdown+=id_value[1];						
						dropdown+="</option>";					
					};	
					
			  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			       if(textStatus == 'timeout') {}
			   }
			}).done(function( msg ) {
				
				$("input[name^=commodity_id]").each(function(index, value) {
                  
                  if($(this).val()==commodity_stock_row_id){
                  var element_id=$(this).attr('id');
                   total=parseInt($("input[name='Qtyissued["+element_id+"]']").val())+total;
                  }
                 
		        });
		        
		        var remaining_items=bal-total;
		        
				locator.closest("tr").find(".batchNo").html(dropdown);
				locator.closest("tr").find(".exp_date").val(new_date);
				locator.closest("tr").find(".AvStck").val(remaining_items);	
				locator.closest("tr").find(".commodity_id").val(commodity_stock_row_id);		
								
			});


		});
		
		/////batch no
		$('.batchNo').live('change',function(){
		    var locator=$('option:selected', this);
			var data =$('option:selected', this).attr('title'); 
	       	var data_array=data.split("^");	
	       	var new_date='';
	       	var val='';
	       	var total=0;
	       	var commodity_stock_row_id;
	       	var remaining_items=0;
	       	
	       	if(data_array[0]!=''){
	       	new_date=$.datepicker.formatDate('d M yy', new Date(data_array[0]));	
	       	val=data_array[1];
	       	commodity_stock_row_id=data_array[2];
	       	
	       	$("input[name^=commodity_id]").each(function(index, value) {
                  
                  if($(this).val()==commodity_stock_row_id){
                  var element_id=$(this).attr('id');
                   total=parseInt($("input[name='Qtyissued["+element_id+"]']").val())+total;
                  }
                 
		        });
       	
	        remaining_items=val-total;
	        locator.closest("tr").find(".exp_date").val(new_date);
			locator.closest("tr").find(".AvStck").val(remaining_items);	
	       	}
	       	else{
	       	locator.closest("tr").find(".exp_date").val();
			locator.closest("tr").find(".AvStck").val();		
	       	}
	                
	        
    
      });
		
		//	-- Datepicker		
		json_obj = {
				"url" : "<?php echo base_url().'Images/calendar.gif';?>",
				};
	    var baseUrl=json_obj.url;
	
     $( ".my_date" ).datepicker({
			showOn: "both",
			dateFormat: 'd M yy', 
			buttonImage: baseUrl,
			buttonImageOnly: true,
			changeMonth: true,
			changeYear: true,
			maxDate: new Date()
		});

	function refreshDatePickers() {
		
		var counter = 0;
		$('.my_date').each(function() {
		var this_id = $(this).attr("id"); // current inputs id
        var new_id = counter +1; // a new id
        $(this).attr("id", new_id); // change to new id
        $(this).removeClass('hasDatepicker'); // remove hasDatepicker class
        $(this).datepicker({ 
                    maxDate: new Date(),
        	        dateFormat: 'd M yy', 
        	        buttonImage: baseUrl,
					changeMonth: true,
			        changeYear: true
				});; // re-init datepicker
				counter++;
		});
		
		
  }
</script>