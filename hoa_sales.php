<?php

/**
 * Plugin Name:       Woodgate HOA financials 1
 * Plugin URI:        http://frasermarlow.com/HOA
 * Description:       Woodgate.hoa Wordpress added shortcodes 
 * Version:           1.1
 * Author:            Fraser Marlow
 * Author URI:        http://frasermarlow.com
 * GitHub repo:       https://github.com/frasermarlow/woodgate_hoa
 */

if (!function_exists('get_get_values')) {
function get_get_values($get) {
	return htmlspecialchars($_GET[$get]);
	}
}
	
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

if (!function_exists('pre_me')) {
function pre_me($array){
		echo "<pre>";
		print_r($array);
		echo "</pre>";
	}
}

if (!function_exists('get_unit_list')) {
	function get_unit_list($units = FALSE){
			global $wpdb;
			if($units == false) {$filter = "";} else {$filter = " WHERE unit in (" . $units . ")"; }
			$sql = "SELECT DISTINCT unit FROM hoa_sales" . $filter . ";";
			$results = $wpdb->get_results($sql);
			foreach ($results as $units){
										$unit_list[] = $units->unit;
										}
			return $unit_list;
	}
}

if (!function_exists('calculate_appreciation')) {
function calculate_appreciation($units = 0 ){	
	global $wpdb;
	if(is_array($units) && isset($units[0]) && $units[0] != ""){ $unit_list = $units;} else { $unit_list = get_unit_list();} 

	foreach($unit_list as $unit){
			$unit = (int)$unit;
			$sql = "select * from hoa_sales WHERE unit = " . $unit . " ORDER BY transaction_date ASC";
			$results = $wpdb->get_results($sql);
			$i = 0;

		   foreach( $results as $transaction ) {
				   if($i>0){
				   $days_between_transactions = (strtotime($transaction->transaction_date) - strtotime($results[$i-1]->transaction_date))/(60*60*24) ;  //returns the number of days between transactions
				   $increase_in_value = ($transaction->sale_price - $results[$i-1]->sale_price);
				   $increase_as_percentage = 100*($transaction->sale_price - $results[$i-1]->sale_price)/$results[$i-1]->sale_price;
					if($days_between_transactions != 0) {   $annual_growth_rate = ((pow($transaction->sale_price/$results[$i-1]->sale_price,1/($days_between_transactions/365.25)))-1)*100;
					} else {$annual_growth_rate = 0;};
				   $results[$i]->days_between_transactions = $days_between_transactions;
				   $results[$i]->increase_in_value = $increase_in_value;
				   $results[$i]->increase_as_percentage = sprintf("%.2f%%", $increase_as_percentage);
				   $results[$i]->annual_growth_rate = sprintf("%.2f%%", $annual_growth_rate);				   
				   }
				   $i++;
				   }
		$full_results[$unit] = $results;	
	}	   
	return $full_results;
	}
}

if (!function_exists('include_unit_filter')) {
function include_unit_filter($units = "") {	
	$output .= "<form action='" . htmlentities($_SERVER['REQUEST_URI']) ."' method='post' name='unit_filter' target='_self'>";
	$output .= "<p>You can filter this view by entering one or more unit numbers, separated by a comma.<br/><label>Units:&nbsp;	</label>&nbsp;&nbsp;<input type='text' name='units'  value='". htmlspecialchars($units) ."'>&nbsp;&nbsp;<button type='submit' value='Submit'>&nbsp;filter</button></p>";
	$output .= "</form>";
	return $output;
	}
}

if (!function_exists('get_unit_filters')) {
function get_unit_filters() {
	if(isset($_POST['units']) && !empty($_POST['units']) ){
		$units_from_post = 	$_POST['units'];
		return $units_from_post;
		};
	return false;	
	}
}

/* SALES LIST FUNCTION ************************************************************/

if (!function_exists('hoa_sales_data')) {	
function hoa_sales_data() {
	global $wpdb;
	$sort_by = $order_by = "";
	setlocale(LC_MONETARY, 'en_US');
	$sort = get_get_values('sort');
	$order = get_get_values('order');
	$filter = get_unit_filters();
	$table_id = 'sales_table';
	if(isset($filter) && $filter != ""){$unit_filter = $filter;} else { $unit_filter = get_get_values('unit');}// if no GET then check for cgi 'unit'
	
	// ADD: explode $filter and check that unit numbers are within range.
	
	if($sort == 1) { $sort_by = " ORDER BY unit";}
	if($sort == 2) { $sort_by = " ORDER BY type";}
	if($sort == 3) { $sort_by = " ORDER BY transaction_date";}
	if($sort == 4) { $sort_by = " ORDER BY sale_price";}
	if($sort == 5) { $sort_by = " ORDER BY realtor";}
	if($order == 1) { $order_by = " ASC";}
	if($order == 2) { $order_by = " DESC";}

	if(isset($unit_filter) && $unit_filter != "") {
		$get_units = " WHERE unit in (" . $unit_filter . ")";
		$unit_cgi = "&unit=".$unit_filter;
		}
	$sql = "select * from hoa_sales" . $get_units . $sort_by . $order_by;
	$results = $wpdb->get_results($sql);
	$full_results = calculate_appreciation();
	if ($order == 1 ) { $flip_sort_order = 2;} else { $flip_sort_order = 1;}
	
	$output .= include_unit_filter($unit_filter);
	
	$output .= include_test_chart($unit_filter);
	$output .= "<hr/>";		
	$output .= '<table class="hoa" id="' . $table_id . '">
  <tr><th><a href="' . get_permalink() . '?sort=1&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Unit</a></th>
    <th><a href="' . get_permalink() . '?sort=3&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Transaction Date</a></th>
    <th><a href="' . get_permalink() . '?sort=4&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Price</a></th>
    <th style="font-size: 0.7em;"><a href="' . get_permalink() . '?sort=5&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Realtor</a></th>
	<th style="font-size: 0.7em;">Annual price change</th>
	<th style="font-size: 0.7em;">Years between transactions</th>
	<th style="font-size: 0.7em;">Change in value</th>
	<th style="font-size: 0.7em;">Increase in value</th>
	<th style="font-size: 0.7em;"><a href="' . get_permalink() . '?sort=2&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Type</a></th></tr>';

	foreach( $results as $user_data) {
	$price = money_format('%.0n',$user_data->sale_price);

	/* supplement array with transaction data calculations */

	foreach($full_results[$user_data->unit] as $transaction_data) {
		if($user_data->transaction_date == $transaction_data->transaction_date)
		{
			$user_data->annual_growth_rate = $transaction_data->annual_growth_rate;
			if($transaction_data->days_between_transactions!=NULL) {
				$user_data->years_between_transactions = number_format((int)$transaction_data->days_between_transactions/365,2);
				}
			$user_data->increase_in_value = $transaction_data->increase_in_value;
			$user_data->increase_as_percentage = $transaction_data->increase_as_percentage;
		}
		};

	$output .= "<tr>
    <td>$user_data->unit</td>
    <td>$user_data->transaction_date</td>
    <td>$price</td>
	<td style=\"font-size: 0.7em;\">$user_data->realtor</td>
	<td style=\"font-size: 0.7em;\">$user_data->annual_growth_rate</td>
	<td style=\"font-size: 0.7em;\">$user_data->years_between_transactions</td>
	<td style=\"font-size: 0.7em;\">$user_data->increase_in_value</td>
	<td style=\"font-size: 0.7em;\">$user_data->increase_as_percentage</td>
	<td style=\"font-size: 0.7em;\">$user_data->type</td>
  </tr>";
	}
	$output .= '</table>';
	
	return $output;
	}
}

/* ADD SALE FUNCTION ************************************************************/

if (!function_exists('hoa_add_sale')) {
function hoa_add_sale(){
	
	if($_POST['submit']) {
		global $wpdb;
		$table_name ='hoa_sales';
		$is_duplicate = FALSE;
		
		$sql = "select * from " . $table_name;
		$existing_entries = $wpdb->get_results($sql);

			$unit = (int)$_POST['unit'];
			$transaction_date = $_POST['transaction_date'];
			$sale_price = (int)$_POST['sale_price'];
			$realtor = $_POST['realtor'];
			$type = $_POST['type'];
		
		// define $is_duplicate as true if we find a match of $_POST in the $existing_entries
		foreach($existing_entries as $key => $sale){
			if($sale->unit == $unit && $sale->transaction_date == $transaction_date ){ $is_duplicate = TRUE; }
			}
		
		if($is_duplicate == FALSE){	
				$success = $wpdb->insert($table_name, array("unit" => $unit,"transaction_date"=>$transaction_date,"sale_price" =>$sale_price,"realtor" => $realtor , "type" => $type ));
				if($success) { $result_message = ' Inserted successfully'; } else { $result_message = 'insert failed'; }
			} else {
				$result_message = 'This is a duplicate entry for unit ' . $unit . ' on ' . $transaction_date;
			}
		
		echo "<h3>" . $result_message . "</h3>";
		
		}
	
	?>
	<form action ="<?php echo $_SERVER['REQUEST_URI']; ?>" method ="post">
	
	<table>
		<tr>
			<td><label for name="unit"> Unit:</label></td>
			<td><input type = "text" name = "unit" id = "unit" placeholder = "Enter Unit number"></td>
		</tr>
		<tr>
			<td><label for name="transaction_date"> Date of sale:</label></td>
			<td><input type = "date" name = "transaction_date" id = "transaction_date" placeholder = "Enter City"></td>
		</tr>
		<tr>
			<td>
				<label for name="sale_price"> Price:</label></td><td><input type = "number" name = "sale_price" id = "sale_price"  placeholder = "Enter Sale Price">
			</td>
		</tr>
		<tr>
			<td>
				<label for name="realtor"> Realtor:</label></td><td><input type = "text" name = "realtor" id = "realtor"  placeholder = "Realtor (if available)">
			</td>
		</tr>
		<tr>
			<td><label for name="type"> Transaction type:</label></td>
			<td>
				<input type = "radio" name = "type" id = "type1"  value="SALE">&nbsp;SALE&nbsp;&nbsp;
				<input type = "radio" name = "type" id = "type2"  value="LISTED">&nbsp;LISTED&nbsp;&nbsp;
				<input type = "radio" name = "type" id = "type3"  value="OFF RECORD">&nbsp;OFF RECORD&nbsp;&nbsp;
				<input type = "radio" name = "type" id = "type4"  value="FORECLOSURE">&nbsp;FORECLOSURE</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input type = "submit" name = "submit" value = "Insert"> &nbsp;<input type="reset" name="reset" style="float:right;"></td>
		</tr>
	</table>
	</form>
	<?php					
	}
}

/* DELETE SALE FUNCTION ************************************************************/

if (!function_exists('hoa_delete_sale')) {
function hoa_delete_sale(){
		global $wpdb;
		$table_name ='hoa_sales';
		
		if($id = (int)$_POST['sale_id']){ $delete_action = $wpdb->get_results('DELETE FROM ' . $table_name . ' WHERE id=' . $id . " LIMIT 1;");} else {$delete_action = "This delete operation for id ". $id. " failed."; }
		if(is_array($delete_action)){ echo "Deletion was successful."; } else { echo $delete_action; }
		
		$sql = "select * from " . $table_name;
		$sales = $wpdb->get_results($sql);
		usort($sales,"sort_by_unit");
		
		echo "\n<form action =" . $_SERVER['REQUEST_URI']. " method =\"post\">";
		foreach($sales as $k => $sale){
		?>
		<input type="radio" name="sale_id" id = "sale_id1" value="<?= (int)$sale->id; ?>">&nbsp;&nbsp;Unit <?= $sale->unit; ?> sold on <?= $sale->transaction_date; ?> for $<?= $sale->sale_price; ?>.<br />
		<?php					
		}
		?><br /><input type = "submit" name = "submit" value = "Delete this transaction">&nbsp;<input type="reset" name="reset"></form><?php
	}
}

/* UNIT LIST FUNCTION ************************************************************/

if (!function_exists('hoa_units_list')) {
function hoa_units_list() {

	global $wpdb;

	setlocale(LC_MONETARY, 'en_US');
	$sort_by = $order_by = $output = $get_units = "";
	$sort = 0;
	$sort = get_get_values('sort');
	$order = get_get_values('order');
	$get_unit = get_get_values('unit');
	$show_taxes = get_get_values('show');  // if there is a get value of show=y then taxes are shown
	$table_id = 'units_table';
	$filter = get_unit_filters();  // check if there is a GET for unit filters
	if($filter != false && $filter != ""){
		$get_units = " WHERE unit in (" . $filter . ")";
		$unit_cgi = "&unit=".$filter;
		} elseif ($get_unit != "") {
		$get_units = " WHERE unit in (" . $get_unit . ")";
		$unit_cgi = "&unit=". $get_unit;
		$filter = $get_unit;
		} // if no GET then check for cgi version
		else {$get_units = "";}

	if($sort == 0) { $sort_by = " ORDER BY unit";}	
	if($sort == 1) { $sort_by = " ORDER BY unit";}
	if($sort == 2) { $sort_by = " ORDER BY sqft";}
	if($sort == 3) { $sort_by = " ORDER BY beds";}
	if($sort == 4) { $sort_by = " ORDER BY baths";}
	if($sort == 5) { $sort_by = " ORDER BY tax_assessment";}
	if($sort == 6) { $sort_by = " ORDER BY taxes";}
	if($sort == 7) { $sort_by = " ORDER BY taxes_per_sqft";}
	if($order == 1) { $order_by = " ASC";}
	if($order == 2) { $order_by = " DESC";}

	$sql = "select * from hoa_units" . $get_units . $sort_by . $order_by;
	$results = $wpdb->get_results($sql);

	if ($order == 1 ) { $flip_sort_order = 2;} else { $flip_sort_order = 1;}

	/*calculate average taxes per sqrft */

	foreach( $results as $unit) {
		$sum = $sum + $unit->taxes;
		$total_sqrft = $total_sqrft + $unit->sqft; 
		$average_tax_per_sqft = $sum/$total_sqrft;
	}

	$output .= include_unit_filter($filter);

	$output .= '<table id="' . $table_id . '">
	<tr><th><a href="' . get_permalink() . '?sort=1&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Unit</a></th>
    <th><a href="' . get_permalink() . '?sort=2&order=' . $flip_sort_order . $unit_cgi . '#' . $table_id . '">Square feet</a></th>
    <th><a href="' . get_permalink() . '?sort=3&order=' . $flip_sort_order .  $unit_cgi . '#' . $table_id . '"># Beds</a></th>
    <th><a href="' . get_permalink() . '?sort=4&order=' . $flip_sort_order .  $unit_cgi . '#' . $table_id . '"># Baths</a></th>';
	
	if ($show_taxes == 'y') {
    $output .= '<th><a href="' . get_permalink() . '?sort=5&order=' . $flip_sort_order .  $unit_cgi . '#' . $table_id . '">Assessed value</a></th>
	<th><a href="' . get_permalink() . '?sort=6&order=' . $flip_sort_order .  $unit_cgi . '#' . $table_id . '">Taxes</a></th>
	<th><a href="' . get_permalink() . '?sort=7&order=' . $flip_sort_order .  $unit_cgi . '#' . $table_id . '">Tax/sqrft</a></th>';
		}
	
	$output .= '</tr>';

	foreach( $results as $user_data) {
			if($user_data->sqft == 0) { continue;}
			$tax_assessment = money_format('%.0n',$user_data->tax_assessment);
			$taxes = money_format('%.0n',$user_data->taxes);
			$tax_sqft = money_format('%.2n',$user_data->taxes_per_sqft);
			if($user_data->taxes/$user_data->sqft>($average_tax_per_sqft*1.01)){$tax_flag = 1;} else {$tax_flag = 0;}

			/*return list of units */

			$output .= "<tr>
				<td>$user_data->unit</td>
				<td>$user_data->sqft</td>
				<td>$user_data->beds</td>
				<td>$user_data->baths</td>";
				
				if ($show_taxes == 'y'){
				$output .= "<td>$tax_assessment</td>
				<td>$taxes</td>";
				$output .= ($tax_flag == 1 ? "<td><strong>$tax_sqft</strong></td>" : "<td>$tax_sqft</td>");
				}
		
			$output .= "</tr>";
		}

	$output .= '</table>';
	$output .= 'The average taxes per square foot is ' . money_format('%.2n',$average_tax_per_sqft);
	$output .= '<hr/>';
	
	return $output;
	}
}

if (!function_exists('hoa_scripts')) {
function hoa_scripts() {
	wp_enqueue_script('chart.js', '//cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.bundle.min.js', array(), '8.2.0', false);
	}
}

if (!function_exists('sort_by_date')) {
function sort_by_date($a,$b){
	if ($a->transaction_date==$b->transaction_date) return 0;
	return ($a->transaction_date<$b->transaction_date)?-1:1;
	}
}

if (!function_exists('sort_by_unit')) {
function sort_by_unit($a,$b){
	if ($a->unit==$b->unit) return 0;
	return ($a->unit<$b->unit)?-1:1;
	}
}

if (!function_exists('include_test_chart')) {
function include_test_chart($units = 0) {
		$dataset = array();
		$transaction_dates = array();
		$point_styles = array('circle','rect','rectRounded','rectRot','star','triangle');
		end($point_styles); $last_point_style = key($point_styles);
		
		if($units == 0){ $get_units = explode(",",get_get_values('unit'));} else {$get_units = explode(",",$units); }
		// echo "<pre>"; print_r($get_units); echo "</pre><br/>";
		$data = calculate_appreciation($get_units);
		// echo "<pre>"; print_r($data); echo "</pre><br/>";
		if(empty($data[array_key_first($data)])){ return NULL; }
		// usort($data,sort_by_date); 

		foreach($data as $unit){
			if(empty($unit)){ continue;}
			$unit_list[] .= $unit[0]->unit; // $unit_list works, returns ist of unit numbers
			foreach ($unit as $transaction){				
				$transaction_dates[] = $transaction->transaction_date;
				$transaction_details[$transaction->unit][] = array($transaction->transaction_date,$transaction->sale_price);
			}
		}
		
		$transaction_dates = array_unique($transaction_dates);
		sort($transaction_dates);

		// iterate through each unit
		foreach($unit_list as $unit){
		// iterate through each sale date
				foreach($transaction_dates as $date){
				foreach($transaction_details[$unit] as $transaction){ 		// if this unit had a sale on that date, include the amount of the sale, otherwise include a blank value
					if($transaction[0] == $date) { $dataset[$unit] .= $transaction[1] . ","; $hit = 1; }
				}
				if($hit == 1) { $hit = 0;} else {$dataset[$unit] .= ",";}
			}
		}
	
		// Generate the javascript for Chart.js
		
		$output = "	
			<canvas id=\"myChart\" class=\"hoa_price_chart\"></canvas>
		<script>
		var ctx = document.getElementById('myChart').getContext('2d');
		var myChart = new Chart(ctx, {
			type: 'line',
			data: {
				labels: [";

				end($transaction_dates); $last_date = key($transaction_dates);
				foreach($transaction_dates as $k => $date){
				if($k == $last_date) { $output .= "'".$date."'"; } else { $output .= "'".$date."',"; }
				}

		$output .="],datasets: [";

		/*build a loop to iterate through each unit, and build a dataset for each */

	
		
		end($dataset); $last_one = (int)key($dataset); $count = count($dataset); $i = 0 ;
		foreach($dataset as $unit => $data){
			
				$red = (255/$count)*$i; $green = (155/$count)*$i; // for varying the color of datapoints
				
				$ps++; if($ps == $last_point_style){$ps = 0;} // iterate through all point styles
			
				$output .= "{label: '" . $unit . "',";
				$output .= "data: [" . $data . "],
						borderDash: [5, 5],
						backgroundColor: 'rgba(00, 00, 100, 0.005)',
						pointBackgroundColor:  'rgba( " . $red . ",". (256-$green) .",00, 1)',
						borderColor: 'rgba( " . $red . ",". (256-$green) .",00, 0.3)',
						borderWidth: 2,
						spanGaps: true,
						pointRadius: 4,
						pointStyle:'" . $point_styles[$ps] . "',
						lineTension: 0
					}";
				if((int)$unit !== $last_one) { $output .= ","; } else {$output .= "]},";}
			$i++;
			}
	
	$output .= "options: {
				responsive: true,
				aspectRatio: 1.5,
				hoverMode: 'index',
				points: {backgroundColor: 'rgba(00, 00, 00, 1)' },
				scales: {
				
					xAxes: [{
					
					type: 'time'
					
					}],
				
					yAxes: [{
					type : 'linear',
					labelString: 'Sale Price in $',
						ticks: {
							beginAtZero : true
						},
						
					ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return '$' + value;
                    }
                }
					}]
				}
			}
		});
		</script>
			";

		return $output;

		}
}

/* Register the shortcodes and stuff with Wordpress */

add_shortcode('hoa_sales_data', 'hoa_sales_data');
add_shortcode('hoa_units_list', 'hoa_units_list');
add_shortcode('hoa_add_sale', 'hoa_add_sale');
add_shortcode('hoa_delete_sale', 'hoa_delete_sale');
add_action('wp_enqueue_scripts', 'hoa_scripts');
/* That's all folks! */
?>
