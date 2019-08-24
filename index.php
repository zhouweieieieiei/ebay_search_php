<!DOCTYPE html>
<html>
<head>
	<title>HW6</title>
	<style type="text/css">
		#input{
			border:3px solid;
			border-color: #D3D3D3;
			width:40%;
			margin:auto;
			margin-top: 3vh;
			margin-bottom: 4vh;
			padding: 1vh;
			padding-top:0;
			background-color: #F8F8F8;
		}
		#header{
			text-align:center;
			font-family: serif;
			font-style: italic;
			font-size: 5vh;
		}
		#no_records{
			display:none;
			width:50%;
			margin:auto;
			margin-top: 40px;
			padding:5px;
			text-align: center;
			background-color: #F0F0F0;
			border:3px solid;
			border-color: #D3D3D3;
			font-family: serif;
			font-size: 3vh;
		}

		#no_seller_message_content{
			width:70%;
			margin:auto;
			margin-top: 40px;
			padding:5px;
			text-align: center;
			background-color: #E0E0E0;
			font-family: serif;
			font-weight: bold;
			font-size: 2vh;
		}

		#no_similar_item_content{
			margin:auto;
			margin: 5px 0px 5px 5px;
			text-align: center;
			font-family: serif;
			font-weight: bold;
			font-size: 2vh;
			border:1px solid;
			border-right: 0px;
			border-color: #D3D3D3;
		}

		#result_form{
			display:none;
		}

		#buttons{
			margin-top: 10px;
			margin-bottom: 20px;
			text-align: center;
		}

		a:hover{
			color: grey;
		}
		.down_arrow,.up_arrow{
			margin: auto;
			width: 50px;
			height: 20px;
		}

		input:required:invalid {
			box-shadow: none;
		}

		.bold_text{
			font-family: serif;
			font-size: 18px;
			font-weight: bold;
		}

		.light_text{
			font-family: serif;
			font-size: 18px;
		}
		.table{
			border: #F8F8F8;
		}

		#item_detail_logo{
			font-family: serif;
			font-size: 4vh;
			font-weight: bold;
		}

	</style>
	<?php
		$keyword = "";
		$category = "";
		$new = "";
		$used = "";
		$unspecified = "";
		$pickup = "";
		$shipping = "";
		$enable_nearby_search = "";
		$distance = "";
		$search_url = "";
		$current_zipcode = "";
		$search_res = "";
		$event_contents = "";
		$detail_itemId = "";
		$detail_url = "";
		$detail_info = "";
		$similar_url = "";
		$similar_info = "";
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			$i = 0;
			$current_zipcode = $_POST['current_zipcode'];
			$search_url = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=WeiZhou-571HW6We-PRD-416de56b6-31d67bf4&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&paginationInput.entriesPerPage=20";
			$keyword = $_POST['keyword'];
			$keyword = urlencode($keyword);
			//$keyword = str_replace(" ", "_", $keyword);
			$search_url = $search_url."&keywords=".$keyword;
			$category = $_POST['category'];
			if($category == "Art") $search_url = $search_url."&categoryId=550";
			if($category == "Baby") $search_url = $search_url."&categoryId=2984";
			if($category == "Books") $search_url = $search_url."&categoryId=267";
			if($category == "Clothing, Shoes & Accessories") $search_url = $search_url."&categoryId=11450";
			if($category == "Computers/Tablets & Networking") $search_url = $search_url."&categoryId=58058";
			if($category == "Health & Beauty") $search_url = $search_url."&categoryId=26395";
			if($category == "Music") $search_url = $search_url."&categoryId=11233";
			if($category == "Video Games & Consoles") $search_url = $search_url."&categoryId=1249";

			if(isset($_POST['new'])||isset($_POST['used'])||isset($_POST['unspecified'])){
				$search_url = $search_url."&itemFilter(".$i.").name=Condition";
				$j = 0;
				if(isset($_POST['new']) && $_POST['new'] == 'yes') {
					$new = "new";
					$search_url = $search_url."&itemFilter(".$i.").value(".$j.")=New";
					$j = $j + 1;
				}
				if(isset($_POST['used']) && $_POST['used'] == 'yes') {
					$used = "used"; 
					$search_url = $search_url."&itemFilter(".$i.").value(".$j.")=Used";
					$j = $j + 1;
				}
				if(isset($_POST['unspecified']) && $_POST['unspecified'] == 'yes') {
					$unspecified = "unspecified"; 
					$search_url = $search_url."&itemFilter(".$i.").value(".$j.")=Unspecified";
					$j = $j + 1;
				}
				$i = $i + 1;
			}

			if(isset($_POST['local_pickup']) && $_POST['local_pickup'] == 'yes'){
				$search_url = $search_url."&itemFilter(".$i.").name=LocalPickupOnly&itemFilter(".$i.").value=true";
				$pickup = "pickup";
				$i = $i + 1;
			}
			if(isset($_POST['free_shipping']) && $_POST['free_shipping'] == 'yes'){
				$search_url = $search_url."&itemFilter(".$i.").name=FreeShippingOnly&itemFilter(".$i.").value=true";
				$shipping = "shipping";
				$i = $i + 1;
			}
			$search_url = $search_url."&itemFilter(".$i.").name=HideDuplicateItems&itemFilter(".$i.").value=true";
			$i = $i + 1;
			if(isset($_POST['enable_nearby_search']) && $_POST['enable_nearby_search'] == 'yes') {
				$enable_nearby_search = "enable_nearby_search";
			}
			if(isset($_POST['distance'])) {
				if($_POST['distance'] == "") $distance = "10";
				else $distance = $_POST['distance'];
				$search_url = $search_url."&itemFilter(".$i.").name=MaxDistance&itemFilter(".$i.").value=".$distance;
				$i = $i + 1;
			}
			if(isset($_POST['zipcode'])) {
				$zipcode = $_POST['zipcode'];	
			}
			if(isset($_POST['pos'])){
				if($_POST['pos'] == 'zip') {
					$pos = "zip";
					$search_url = $search_url."&buyerPostalCode=".$zipcode;
				}
				else {
					$pos = "here";
					$search_url = $search_url."&buyerPostalCode=".$current_zipcode;
				}
			}
			//connect ebay
			$curl = curl_init();
			curl_setopt ($curl, CURLOPT_URL, $search_url);
    		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
    		$search_res = curl_exec($curl);
    		curl_close($curl);

    		$detail_itemId = $_POST['detail_itemId'];
    		if(isset($_POST['choose_detail']) && $_POST['choose_detail'] == 'yes'){
    			$detail_url = "http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid=WeiZhou-571HW6We-PRD-416de56b6-31d67bf4&siteid=0&version=967&ItemID=".$detail_itemId."&IncludeSelector=Description,Details,ItemSpecifics";
    			$curl = curl_init();
				curl_setopt ($curl, CURLOPT_URL, $detail_url);
    			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
    			$detail_info = curl_exec($curl);
    			$detail_info = str_replace("<", "< ", $detail_info);
    			$detail_info = str_replace(">", " >", $detail_info);

    			$similar_url = "http://svcs.ebay.com/MerchandisingService?OPERATION-NAME=getSimilarItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.1.0&CONSUMER-ID=WeiZhou-571HW6We-PRD-416de56b6-31d67bf4&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&itemId=".$detail_itemId."&maxResults=8";
    			curl_setopt ($curl, CURLOPT_URL, $similar_url);
    			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, true);
    			$similar_info = curl_exec($curl);
    			curl_close($curl);
    		}
    		
		}
	?>
</head>
<body onload="geolocation()">
	<script type="text/javascript">
		var content1 = "";
		function geolocation(){
			var xmlhttp= new XMLHttpRequest();
			xmlhttp.open("GET","http://ip-api.com/json",false);
			xmlhttp.send();
			if(xmlhttp.status==404){
				alert("Unable to get the location");
			}
			else{
				var geoinfo=JSON.parse(xmlhttp.responseText);
				current_zipcode=geoinfo['zip'];
				document.getElementById("current_zipcode").value = current_zipcode;
				document.getElementById("Submit").removeAttribute('disabled');
			}
		}

		function ENS(obj){//when the enable nearby search checkbox chosed
			if(obj.checked){
				document.getElementById("distance").value = "";
				document.getElementById("zipcode").value = "";
				document.getElementById("distance").disabled = false;
				document.getElementById("here").disabled = false;
				document.getElementById("zip").disabled = false;
				document.getElementById("miles_text").style.color = "black";
				document.getElementById("here_text").style.color = "black";
			}
			else{
				document.getElementById("distance").disabled = true;
				document.getElementById("here").disabled = true;
				document.getElementById("zip").disabled = true;
				document.getElementById("zipcode").disabled = true;
				document.getElementById("distance").value = "";
				document.getElementById("zipcode").value = "";
				document.getElementById("miles_text").style.color = "grey";
				document.getElementById("here_text").style.color = "grey";
			}
		}

		function Select_Here(obj){
			if(obj.checked){
				document.getElementById("zipcode").disabled = true;
				document.getElementById("zipcode").value = "";
			}
		}

		function Select_Zip(obj){//when zipcode chosed
			if(obj.checked){
				document.getElementById("zipcode").disabled = false;
			}
			else{
				document.getElementById("zipcode").disabled = true;
				document.getElementById("zipcode").value = "";
			}
		}

		function SMDB(){//seller message down button
			document.getElementById("seller_message_down_button").style.display="none";
			document.getElementById("seller_message_up_button").style.display="block";
			document.getElementById("seller_message").srcdoc = content1;
			document.getElementById("similar_items_down_button").style.display="block";
			document.getElementById("similar_items_up_button").style.display="none";
			document.getElementById("similar_items").style.display="none";
			document.getElementById("no_seller_message").style.display="block";
			//document.getElementById("seller_message_container").style.display="block";
			//document.getElementById("seller_message_container").style.height = document.getElementById("seller_message_container").contentDocument.documentElement.scrollHeight + 'px';
		}

		function SMUB(){
			document.getElementById("seller_message_down_button").style.display="block";
			document.getElementById("seller_message_up_button").style.display="none";
			document.getElementById("seller_message").srcdoc = "";
			document.getElementById("no_seller_message").style.display="none";
			//document.getElementById("seller_message_container").style.display="none";
		}

		function SIDB(){//seller message down button
			document.getElementById("similar_items_down_button").style.display="none";
			document.getElementById("similar_items_up_button").style.display="block";
			document.getElementById("similar_items").style.display="block";
			document.getElementById("seller_message_down_button").style.display="block";
			document.getElementById("seller_message_up_button").style.display="none";
			document.getElementById("seller_message").srcdoc = "";
			//document.getElementById("similar_items_container").style.display="block";
			//document.getElementById("similar_items_container").style.height = document.getElementById("similar_items_container").contentDocument.documentElement.scrollHeight + 'px';
		}

		function SIUB(){
			document.getElementById("similar_items_down_button").style.display="block";
			document.getElementById("similar_items_up_button").style.display="none";
			document.getElementById("similar_items").style.display="none";
			//document.getElementById("similar_items_container").style.display="none";
		}

		function clearForm(){
			document.getElementById("distance").value = "";
			document.getElementById("keyword").value = "";
			document.getElementById("res_info").innerHTML = "";
			document.getElementById("detail_info").innerHTML = "";
			document.getElementById("similar_info").innerHTML = "";
			document.getElementById("detail_itemId").value = "";
			document.getElementById("category").options[0].selected = true;
			document.getElementById("new").checked = false;
			document.getElementById("used").checked = false;
			document.getElementById("unspecified").checked = false;
			document.getElementById("local_pickup").checked = false;
			document.getElementById("free_shipping").checked = false;
			document.getElementById("enable_nearby_search").checked = false;
			document.getElementById("distance").value = "10";
			document.getElementById("distance").disabled = true;
			document.getElementById("here").checked = true;
			document.getElementById("here").disabled = true;
			document.getElementById("zip").checked = false;
			document.getElementById("zip").disabled = true;
			document.getElementById("zipcode").value = "zipcode";
			document.getElementById("zipcode").disabled = true;
			document.getElementById("result_form").style.display="none";
			document.getElementById("detail_form").style.display="none";
			document.getElementById("here_text").style.color = "grey";
			document.getElementById("miles_text").style.color = "grey";
			document.getElementById("seller_message_container").style.display="none";
			document.getElementById("seller_message_down_button").style.display="none";
			document.getElementById("seller_message_up_button").style.display="none";
			document.getElementById("similar_items_down_button").style.display="none";
			document.getElementById("similar_items_up_button").style.display="none";
			document.getElementById("no_records").style.display="none";
			document.getElementById("similar_items").style.display="none";
			document.getElementById("no_seller_message").style.display="none";
		}
	</script>
	<div id = "input">
		<div id = "header">
			Product Search
		</div>
		<hr style="color: #D3D3D3;">
		<form id = "inputform" style="margin-top: 12px;" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
			<span class="bold_text" style="margin-left: 10px;">Keyword</span>
			<input id = "keyword" name = "keyword" type = "text" size = 20 value="<?php if(isset($_POST['keyword'])){echo $_POST['keyword'];}?>" required="required" oninvalid="setCustomValidity('Please fill out this field.')" oninput="setCustomValidity('')"><br>
			<div style="margin-top: 10px;  margin-left: 10px;">
				<span class="bold_text">Category</span>
				<select id = "category" name = "category" style="width:45vh;">
					<option value = "All Categories" selected>All Categories</option>
					<option value = "Art" <?php echo $category == 'Art' ? 'selected' : '' ?>>Art</option>
					<option value = "Baby" <?php echo $category == 'Baby' ? 'selected' : '' ?>>Baby</option>
					<option value = "Books" <?php echo $category == 'Books' ? 'selected' : '' ?>>Books</option>
					<option value = "Clothing, Shoes & Accessories" <?php echo $category == 'Clothing, Shoes & Accessories' ? 'selected' : '' ?>>Clothing, Shoes & Accessories</option>
					<option value = "Computers/Tablets & Networking" <?php echo $category == 'Computers/Tablets & Networking' ? 'selected' : '' ?>>Computers/Tablets & Networking</option>
					<option value = "Health & Beauty" <?php echo $category == 'Health & Beauty' ? 'selected' : '' ?>>Health & Beauty</option>
					<option value = "Music" <?php echo $category == 'Music' ? 'selected' : '' ?>>Music</option>
					<option value = "Video Games & Consoles" <?php echo $category == 'Video Games & Consoles' ? 'selected' : '' ?>>Video Games & Consoles</option>
				</select><br>
			</div>
			<div style="margin-top: 10px; margin-left: 10px;">
				<span class="bold_text">Condition</span>
				<input id = "new" type = "checkbox" name = "new" style="margin-left: 30px;" value = "yes" <?php echo $new == "new" ? 'checked' : '' ?>>
				<span class="light_text">New</span>
				<input id = "used" type = "checkbox" name = "used" style="margin-left: 30px;" value = "yes" <?php echo $used == "used" ? 'checked' : '' ?>>
				<span class="light_text">Used</span>
				<input id = "unspecified" type = "checkbox" name = "unspecified" style="margin-left: 30px;" value = "yes" <?php echo $unspecified == "unspecified" ? 'checked' : '' ?>>
				<span class="light_text">Unspecified</span>
			</div>
			<div style="margin-top: 10px;  margin-left: 10px;">
				<span class="bold_text">Shipping Options</span>
				<input id = "local_pickup" type = "checkbox" name = "local_pickup" style="margin-left: 65px;" value = "yes" <?php echo $pickup == "pickup" ? 'checked' : '' ?>>
				<span class="light_text">Local Pickup</span>
				<input id = "free_shipping" type = "checkbox" name = "free_shipping" style="margin-left: 55px;" value = "yes" <?php echo $shipping == "shipping" ? 'checked' : '' ?>>
				<span class="light_text">Free Shipping</span>
			</div>			
			<div style="margin-top: 10px;  margin-left: 10px;">
				<input id = "enable_nearby_search" type = "checkbox" name = "enable_nearby_search" value = "yes" onclick = "ENS(this)" <?php echo $enable_nearby_search == "enable_nearby_search" ? 'checked' : '' ?>>
				<span class="bold_text">Enable Nearby Search</span>
				<input id = "distance" name = "distance" type = "text" size = 5 placeholder = "10" style="margin-left: 7vh;" <?php echo $enable_nearby_search == "enable_nearby_search" ? '' : 'disabled' ?> value = "<?php echo isset($_POST['distance'])?$distance:'';?>"> 
					<span id = "miles_text" style = <?php echo $enable_nearby_search == "enable_nearby_search" ? "color:black;" : "color:grey;" ?> class="bold_text">miles from</span>
				<div style="display:inline-block; vertical-align: top;">
				<input id = "here" type = "radio" name = "pos" value = "here" onclick="Select_Here(this)" <?php echo $enable_nearby_search == "enable_nearby_search" ? '' : 'disabled' ?> checked>
				<span id = "here_text" class="light_text" style = <?php echo $enable_nearby_search == "enable_nearby_search" ? "color:black" : "color:grey" ?>>Here</span>
				<br>
				<input id = "zip" type = "radio" name = "pos" value = "zip" onclick = "Select_Zip(this)" <?php echo $enable_nearby_search == "enable_nearby_search" ? '' : 'disabled' ?> <?php echo $pos == "zip" ? 'checked' : '' ?>>
				<input id = "zipcode" name = "zipcode" type = "text" size = 20 placeholder = "zipcode" <?php echo $pos == "zip"&& $enable_nearby_search == "enable_nearby_search"? '' : 'disabled' ?> value = "<?php echo $zipcode;?>" required="required" oninvalid="setCustomValidity('Please fill out this field.')" oninput="setCustomValidity('')"><br>
				</div>
			</div>	
			<div id = "buttons">
				<input id="Submit" type="submit" name="Search" value="Search" disabled>
				<input onclick="clearForm()" type="button"  name="Clear" value="Clear">
			</div>	
			<input id = "current_zipcode" type="text" name = "current_zipcode" style="display: none;">
			<input id = "detail_itemId" type="text" name = "detail_itemId" style="display: none;" value="<?php echo $detail_itemId ?>">
			<input id = "choose_detail" type = "checkbox" name = "choose_detail" value = "yes" style="display: none;">
		</form>	
	</div>
	<div id = "res_info" style="display: none;"><?php echo $search_res;?></div>
	<div id = "detail_info" style="display: none;"><?php echo $detail_info;?></div>
	<div id = "similar_info" style="display: none;"><?php echo $similar_info;?></div>
	<center>
		<div id = "no_records"></div>
		<div id = "result_form" style="margin-left: 0; margin-right: 0;"></div>
		<div id = "detail_form"></div>
	</center>
	<div id = "seller_message_down_button" style="text-align: center; display: none; margin-top: 30px;" onclick="SMDB()">
		<div style="color: #B3B3B3; margin-bottom: 10px; font-family: serif; font-size: 20px;">click to show seller message</div>
		<img class="down_arrow" src = "http://csci571.com/hw/hw6/images/arrow_down.png" />
	</div>
	<div id = "seller_message_up_button" style="text-align: center; display: none" onclick="SMUB()">
		<div style="color: #B3B3B3; margin-bottom: 10px; font-family: serif; font-size: 20px;">click to hide seller message</div>
		<img class="up_arrow" src = "http://csci571.com/hw/hw6/images/arrow_up.png"/>
	</div>
	<center>
		<div id = "no_seller_message" style="display: none;">
		</div>
	</center>
	<center><div id = "seller_message_container">
		<iframe id = "seller_message" onload="this.height = '0px';this.height = this.contentDocument.documentElement.scrollHeight + 'px';" width="70%" frameborder="0"></iframe>
	</div></center>
	
	<div id = "similar_items_down_button" style="text-align: center; display: none" onclick="SIDB()">
		<div style="color: #B3B3B3; margin-bottom: 10px; font-family: serif; font-size: 20px;">click to show similar items</div>
		<img class="down_arrow" src = "http://csci571.com/hw/hw6/images/arrow_down.png"/>
	</div>

	<div id = "similar_items_up_button" style="text-align: center; display: none" onclick="SIUB()">
		<div style="color: #B3B3B3; margin-bottom: 10px; font-family: serif; font-size: 20px;">click to hide similar items</div>
		<img class="up_arrow" src = "http://csci571.com/hw/hw6/images/arrow_up.png"/>
	</div>
	<center>
		<div id = "similar_items" style="display: none; overflow:scroll; width: 60%; border: 2px solid; border-color: #D3D3D3; margin-bottom: 30px;"></div>
	</center>
	<script type="text/javascript">
		function apply_detail_info(itemId){
			console.log(itemId);
			document.getElementById("detail_itemId").value = itemId;
			document.getElementById("choose_detail").checked = true;
			document.getElementById("inputform").submit();
			document.getElementById("choose_detail").checked = false;
		}

		var res_str = document.getElementById("res_info").innerText;
		var detail_str = document.getElementById("detail_info").innerText;
		var similar_str = document.getElementById("similar_info").innerText;
		//console.log(res_str);
		//console.log(detail_str);
		if(res_str!=""&&detail_str == ""){
			document.getElementById("seller_message").style.display="none";
			document.getElementById("similar_items").style.display="none";
			document.getElementById("seller_message_down_button").style.display="none";
			document.getElementById("seller_message_up_button").style.display="none";
			document.getElementById("similar_items_down_button").style.display="none";
			document.getElementById("similar_items_up_button").style.display="none";

			var res_json = JSON.parse(res_str);
			console.log(res_json);
			if(res_json.findItemsAdvancedResponse[0].ack[0] == "Failure"){
				var errormessage = res_json.findItemsAdvancedResponse[0].errorMessage[0].error[0].message[0];
				console.log(errormessage);
				if(errormessage.indexOf("Invalid postal code") != -1) {
					document.getElementById("no_records").innerText = "Zipcode is invalid";				
				}
				else document.getElementById("no_records").innerText = errormessage;
				document.getElementById("no_records").style.display="block";
			}
			else{
				var res_data = res_json.findItemsAdvancedResponse[0].searchResult[0];
				var num = parseInt(res_data['@count']);
				//console.log(num);
				if(num == 0){
					document.getElementById("no_records").innerText = "No Records has been found";
					document.getElementById("no_records").style.display="block";
				}
				else{
					var content = "";
					content+="<table width = 88% border = 1px cellspacing = 0 style = \"border: #F8F8F8; font-family: serif; font-size: 2vh;\">";
					content+="<tr>";
					content+="<th>Index</th><th width = 7%>Photo</th><th>Name</th><th>Price</th><th>Zip code</th><th>Condition</th><th>Shipping Option</th>"
					content+="</tr>";
					for(var i = 0; i<num; ++i){
						var item = res_data.item[i];
						content+="<tr>";
						content+="<td>";
						content+=(i+1).toString();
						content+="</td>";

						content+="<td width = 7% style = \"font-size: 0;\">";
						if(item.hasOwnProperty("galleryURL")){
							content+="<img src=\"";
							content+=item.galleryURL[0];
							content+="\" width = 100%/>";
						}
						else content+="N/A";
						content+="</td>";

						content+="<td>";
						if(item.hasOwnProperty("title")){
							content+="<a onclick = \'apply_detail_info("+item.itemId[0]+")\'>";
							content+=item.title[0];
							content+="</a>";
						}
						else content+="N/A";
						content+="</td>";

						content+="<td>";
						if(item.hasOwnProperty("sellingStatus")&&item.sellingStatus[0].hasOwnProperty("currentPrice")&&item.sellingStatus[0].currentPrice[0].hasOwnProperty("__value__")){
							content+="$"+item.sellingStatus[0].currentPrice[0]["__value__"];
						}
						else content+="N/A";
						content+="</td>";

						content+="<td>";
						if(item.hasOwnProperty("postalCode")){
							content+=item.postalCode[0];
						}
						else content+="N/A";
						content+="</td>";

						content+="<td>";
						if(item.hasOwnProperty("condition")&&item.condition[0].hasOwnProperty("conditionDisplayName")){
							content+=item.condition[0].conditionDisplayName[0];
						}
						else content+="N/A";
						content+="</td>";

						content+="<td>";
						if(item.hasOwnProperty("shippingInfo")&&item.shippingInfo[0].hasOwnProperty("shippingServiceCost")&&item.shippingInfo[0].shippingServiceCost[0].hasOwnProperty("__value__")){
							var ship_cost = parseInt(item.shippingInfo[0].shippingServiceCost[0]["__value__"]);
							if(ship_cost == 0) content+="Free Shipping";
							else content+="$"+item.shippingInfo[0].shippingServiceCost[0]["__value__"];
						}
						else content+="N/A";
						content+="</td>";

						content+="</tr>";
					}
					content+="</table>";
					document.getElementById("result_form").innerHTML = content;
					document.getElementById("result_form").style.display="block";
				}
			}
		}
		else 
			if(detail_str!=""){
			var detail_json = JSON.parse(detail_str);
			console.log(detail_json);
			if(detail_json.Ack == "Failure"){
				document.getElementById("no_records").innerText = "Invalid or non-existent item ID";
				document.getElementById("no_records").style.display="block";
			}
			else{
				var item = detail_json.Item;
				var content = "";
				content+="<div id = \"item_detail_logo\">Item Details<div>"
				content+="<table border = 1px cellspacing = 0 style = \"border: #F8F8F8; font-family: serif; font-size: 2vh;\">";
				if(item.hasOwnProperty("GalleryURL")){
					content+="<tr>";
					content+="<td height = 250px>";
					content+="Photo";
					content+="</td>";
					content+="<td height = 250px padding-bottom = 0 style = \"font-size: 0\">";
					content+="<img src=\"";
					content+=item.GalleryURL;
					content+="\"/ height = 100%>";
					content+="</td>";
					content+="</tr>";
				}
				
				if(item.hasOwnProperty("Title")){
					content+="<tr>";
					content+="<td>";
					content+="Title";
					content+="</td>";
					content+="<td>";
					content+=item.Title;
					content+="</td>";
					content+="</tr>";
				}

				if(item.hasOwnProperty("Subtitle")){
					content+="<tr>";
					content+="<td>";
					content+="Subtitle";
					content+="</td>";
					content+="<td>";
					content+=item.Subtitle;
					content+="</td>";
					content+="</tr>";
				}

				if(item.hasOwnProperty("CurrentPrice")&&item.CurrentPrice.hasOwnProperty("Value")){
					content+="<tr>";
					content+="<td>";
					content+="Price";
					content+="</td>";
					content+="<td>";
					content+=item.CurrentPrice.Value;
					content+=item.CurrentPrice.CurrencyID;
					content+="</td>";
					content+="</tr>";
				}

				if(item.hasOwnProperty("Location")){
					content+="<tr>";
					content+="<td>";
					content+="Location";
					content+="</td>";
					content+="<td>";
					content+=item.Location;
					content+="</td>";
					content+="</tr>";
				}

				if(item.hasOwnProperty("Seller")&&item.Seller.hasOwnProperty("UserID")){
					content+="<tr>";
					content+="<td>";
					content+="Seller";
					content+="</td>";
					content+="<td>";
					content+=item.Seller.UserID;
					content+="</td>";
					content+="</tr>";
				}

				if(item.hasOwnProperty("ReturnPolicy")&&item.ReturnPolicy.hasOwnProperty("InternationalReturnsAccepted")&&item.ReturnPolicy.InternationalReturnsAccepted == "ReturnsAccepted"){
					content+="<tr>";
					content+="<td>";
					content+="Return Policy (US)";
					content+="</td>";
					content+="<td>";
					content+="Returns Accepted within ";
					content+=item.ReturnPolicy.InternationalReturnsWithin;
					content+="</td>";
					content+="</tr>";
				}

				if(item.hasOwnProperty("ItemSpecifics")&&item.ItemSpecifics.hasOwnProperty("NameValueList")){
					for(var i = 0; i<item.ItemSpecifics.NameValueList.length; ++i){
						content+="<tr>";
						content+="<td>";
						content+=item.ItemSpecifics.NameValueList[i].Name;
						content+="</td>";
						content+="<td>";
						content+=item.ItemSpecifics.NameValueList[i].Value[0];
						content+="</td>";
						content+="</tr>";
					}
				}

				content+="</table>";
				if(item.hasOwnProperty("Description")&&item.Description!=""){
					var seller_message = item.Description;
					var reg1 = new RegExp( /< / , "g" );
					var reg2 = new RegExp( / >/ , "g" );
					seller_message = seller_message.replace(reg1, "<");
					seller_message = seller_message.replace(reg2, ">");
				}
				else{
					seller_message = "";
					document.getElementById("no_seller_message").innerHTML = "<div id = \"no_seller_message_content\">No Seller Message found.</div>";
				}

				var similar_items = "";
				if(similar_info!=""){
					var similar_json = JSON.parse(similar_str);
					console.log(similar_json);
					var similar_num = similar_json.getSimilarItemsResponse.itemRecommendations.item.length;
					if(similar_num == 0){
						similar_items = "<div id = \"no_similar_item_content\">No Similar Item found.</div>";
					}
					else{
						similar_items+="<table style=\"text-align: center; font-family:serif;\">";
						similar_items+="<tr>";
						for(var i = 0; i<similar_num; ++i){
							similar_items+="<td style = \"padding: 10px 60px 10px 60px;\" style = \"font-size: 0;\">";
							similar_items+="<a onclick = \'parent.apply_detail_info("+similar_json.getSimilarItemsResponse.itemRecommendations.item[i].itemId+")\'>";
							similar_items+="<img style = \"width:20vh;\" src=\"";
							similar_items+=similar_json.getSimilarItemsResponse.itemRecommendations.item[i].imageURL;
							similar_items+="\"/>";
							similar_items+="</a>";
							similar_items+="</td>";
						}
						similar_items+="</tr>";

						similar_items+="<tr>";
						for(var i = 0; i<similar_num; ++i){
							similar_items+="<td>";
							similar_items+=similar_json.getSimilarItemsResponse.itemRecommendations.item[i].title;
							similar_items+="</td>";
						}
						similar_items+="</tr>";

						similar_items+="<tr>";
						for(var i = 0; i<similar_num; ++i){
							similar_items+="<td style = \"font-weight: bold; padding-top: 10px; padding-bottom: 10px;\">";
							similar_items+="$";
							similar_items+=similar_json.getSimilarItemsResponse.itemRecommendations.item[i].buyItNowPrice["__value__"];
							similar_items+="</td>";
						}
						similar_items+="</tr>";
						similar_items+="</table>";
					}
					document.getElementById("similar_items").innerHTML = similar_items;
					document.getElementById("similar_items").style.display="none";
				}
				content1 = seller_message;
				document.getElementById("seller_message_down_button").style.display = "block";
				document.getElementById("similar_items_down_button").style.display = "block";
				document.getElementById("detail_form").innerHTML = content;
				document.getElementById("detail_form").style.display="block";
				//document.getElementById("seller_message").srcdoc = seller_message;
				//document.getElementById("seller_message_down_button").click();
				//document.getElementById("seller_message_up_button").click();
				//document.getElementById("seller_message").style.display="none";
			}
		}
	</script>
</body>
</html>