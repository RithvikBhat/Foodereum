<!-- Track product -->
<?php 
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="SHORTCUT ICON" href="images/fe.png" type="image/x-icon" />
    <link rel="ICON" href="images/fe.png" type="image/ico" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mdbmin.css" rel="stylesheet">
    <link href="css/mdb.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <title>Foodereum - Track Products</title>

    <style>  
    
      .scrollbar{
          height: 350px;
          width: 40%;
          padding: 15px;
          background: #fff;
          overflow-y: scroll;
          box-shadow: 0 3px 6px rgba(0,0,0,0.20), 0 3px 6px rgba(0,0,0,0.23);
      }
        
        
      .scrollbar-primary::-webkit-scrollbar {
          width: 7px;
          background-color: #F5F5F5;
      }
        
      .scrollbar-primary::-webkit-scrollbar-thumb {
          border-radius: 10px;
          -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
          background-color: #ffee00;
      }
        
      .scrollbar-primary {
          scrollbar-color: #ffee00 #F5F5F5;
      }
  
    </style>
  </head>
  
  
  <?php
  if(isset($_SESSION['role'])){
  ?>
 <?php if (isset($_SESSION['role']) && $_SESSION['role']) { ?>
		<div <?php if($_SESSION['role']!='6') { ?> id="loggedIn" <?php } ?>></div>
    <?php } ?>
  <body class="violetgradient">
    <?php
    include "navbar.php"
    ?>
    <center>
      <div class="customalert">
          <div class="alertcontent"><i><b>Track Products</b></i>
              <div id="alertText"> &nbsp </div>
              <img id="qrious">
              <div id="bottomText" style="margin-top: 10px; margin-bottom: 15px;"> &nbsp </div>
              <button id="closebutton" class="formbtn"> OK </button>
          </div>
      </div>
    </center>

    <div>
      <center>
        <div class="centered">
          <form role="form" autocomplete="off">
            <h3 style="margin-top: 80px;">Track Product Here.</h3>
              <input type="text" id="searchText" class="searchBox" placeholder="Enter Product ID To Track" onkeypress="isInputNumber(event)" required>
              <label class=qrcode-text-btn style="width:4%;display:none;">
				        <input type=file accept="image/*" id="selectedFile" style="display:none" capture=environment onchange="openQRCamera(this);" tabindex=-1>
			        </label>
			        <button type="submit" id="searchButton" class="searchBtn"><i class="fa fa-search"></i></button>
          </form>
          <br>
		         <h6>OR </h6>
		      <button class="qrbutton" onclick="document.getElementById('selectedFile').click();">
		        <i class='fa fa-qrcode'></i> Scan QR
		      </button>
	
          <br>
          <p id="database" class="scrollbar scrollbar-primary">
            No Data to Display
          </p>
        </div>
      </center>
    </div>

    <div class='box'>
      <div class='wave -one'></div>
      <div class='wave -two'></div>
      <div class='wave -three'></div>
    </div>
  

  <?php }else{
    include 'redirection.php';
    redirect("index.php");
  } ?>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/mdb.min.js"></script>


    <script src="web3.min.js"></script>
    <script src="app.js"></script>

	<!-- QR Code Reader -->
	<script src="https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js"></script>

    <!-- Web3 Injection -->
    <script>
      web3 = new Web3(new Web3.providers.HttpProvider('HTTP://127.0.0.1:7545'));

      // Set the Contract
      var contract = new web3.eth.Contract(contractAbi, contractAddress);

      $(".scrollbar").hide();
      
      $('form').on('submit', function(event) {
        event.preventDefault(); // to prevent page reload when form is submitted

        greeting = $('input').val();
        console.log(greeting)

        contract.methods.searchProduct(greeting).call(function(err, result) {
          console.log(err, result)
          $(".scrollbar").show("fast","linear");
          $("#database").html(result);
        });

      });

      
    function isInputNumber(evt){
      var ch = String.fromCharCode(evt.which);
      if(!(/[0-9]/.test(ch))){
          evt.preventDefault();
      }
    }

    $("#closebutton").on("click", function(){
        $(".customalert").hide("fast","linear");
    });

	function openQRCamera(node) {
		var reader = new FileReader();
		reader.onload = function() {
			node.value = "";
			qrcode.callback = function(res) {
			if(res instanceof Error) {
				alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
			} else {
				node.parentNode.previousElementSibling.value = res;
				document.getElementById('searchButton').click();
			}
			};
			qrcode.decode(reader.result);
		};
		reader.readAsDataURL(node.files[0]);
	}
  
    function showAlert(message){
      $("#alertText").html(message);
      $("#qrious").hide();
      $("#bottomText").hide();
      $(".customalert").show("fast","linear");
    }

    $("#aboutbtn").on("click", function(){
        showAlert("Allow users to Track food products using QR codes or Product IDs.");
    });

    $(document).ready(function() {
	if($('#loggedIn')) {		
		getNotification();
		setInterval(function(){ getNotification(); }, 20000);
	}
});

function getNotification() {	
	if (!Notification) {
		$('body').append('<h4 style="color:red">*Browser does not support Web Notification</h4>');
		return;
	}
	if (Notification.permission !== "granted") {		
		Notification.requestPermission();
	} else {		
		$.ajax({
			url : "notification.php",
			type: "POST",
			success: function(response, textStatus, jqXHR) {
				var response = jQuery.parseJSON(response);
				if(response.result == true) {
					var notificationDetails = response.notif;
					for (var i = notificationDetails.length - 1; i >= 0; i--) {
						var notificationUrl = notificationDetails[i]['url'];
						var notificationObj = new Notification(notificationDetails[i]['title'], {
							icon: notificationDetails[i]['icon'],
							body: notificationDetails[i]['message'],
						});
						notificationObj.onclick = function () {
							window.open(notificationUrl); 
							notificationObj.close();     
						};
						setTimeout(function(){
							notificationObj.close();
						}, 10000);
					};
				} else {
				}
			},
			error: function(jqXHR, textStatus, errorThrown)	{}
		}); 
	}
};
    </script>
  </body>
</html>
