<?php 
session_start(); 
$color="navbar-light orange darken-4";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="SHORTCUT ICON" href="images/fe.png" type="image/x-icon" />
    <link rel="ICON" href="images/fe.png" type="image/ico" />
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mdb.min.css" rel="stylesheet">
    
    <link href="css/style.css" rel="stylesheet">
    
    <title>Foodereum - Sanction Items</title>
  </head>

  <!-- Segregating the roles based on navbar -->
  <?php
    if( $_SESSION['role']==0 ){
  ?>

  <body class="violetgradient">
    <?php include 'navbar.php'; ?>
    <center>
        <div class="customalert">
            <div class="alertcontent"><i><b>Sanction Products</b></i>
                <div id="alertText"> &nbsp </div>
                <img id="qrious">
                <div id="bottomText" style="margin-top: 10px; margin-bottom: 15px;"> &nbsp </div>
                <a href="mailto:foodereum@googlegroups.com"> <button id="mail" class="formbtn"> Mail QR Code </button> </a>
               <button id="closebutton" class="formbtn"> OK </button>
               <br><a href="manage.php"> <button id="notif" class="formbtn"> Send Notification </button> </a>
            </div>
        </div>
    </center>


    <div class="bgrolesadd">
      <center>
        <div class="mycardstyle">
            <div class="yellowarea">
                <h5> Add Food Items </h5>
                <form id="form1" autocomplete="off">
                    <div class="formitem">
                        <input type="text" size="15" class="forminput" id="prodname" placeholder="Item Name" required>
                        <input type="number" min="0" max="2000" class="qty" placeholder="Quantity" id="quantity" required>                       
                        <select id="qunit" name="qunit1" class="quy" required>
                          <option value="kg">kg</option>
                          <option value="ml">ml</option>
                          <option value="g">g</option>
                          <option value="l">l</option>
                       </select>
                    
                         <div id="my-content" class="content">
                         
                         </div>
                        <input type="hidden" class="forminput" id="user" value=<?php echo $_SESSION['username']; ?> required>
                    </div>
                    <button class="formbtn" id="mansub" type="submit">Register Items</button>
                </form>
            </div>
        </div>


      </center>
      <?php
        }else{
            include 'redirection.php';
            redirect('index.php');
        }
    ?>
    <div class='box'>
      <div class='wave -one'></div>
      <div class='wave -two'></div>
      <div class='wave -three'></div>
    </div>
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!-- Material Design Bootstrap-->
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/mdb.min.js"></script>

    <!-- Web3.js -->
    <script src="web3.min.js"></script>

    <!-- QR Code Library-->
    <script src="./dist/qrious.js"></script>

    <!-- QR Code Reader -->
	  <script src="https://rawgit.com/sitepoint-editors/jsqrcode/master/src/qr_packed.js"></script>

    <script src="app.js"></script>

    <!-- Web3 Injection -->
    <script>
      //  Initialize Web3 using rpc server id in Ganache
      if (typeof web3 !== 'undefined') {
        web3 = new Web3(web3.currentProvider);
        web3 = new Web3(new Web3.providers.HttpProvider('HTTP://127.0.0.1:7545'));
      } else {
        web3 = new Web3(new Web3.providers.HttpProvider('HTTP://127.0.0.1:7545'));
      }

      // Set the Contract
    var contract = new web3.eth.Contract(contractAbi, contractAddress);

    $("#closebutton").on("click", function(){
        $(".customalert").hide("fast","linear");
    });


    $('#form1').on('submit', function(event) {
        event.preventDefault(); // to prevent page reload when form is submitted
        prodname = $('#prodname').val();
        quantity = $('#quantity').val();
        qunit = $('#qunit').val();
        username = $('#user').val(); 
        prodname = "<b>"+prodname+"<br>Quantity: "+quantity+" " +qunit+"<br>Registered By: "+username+"</b>";
        console.log(prodname+quantity);
        var today = new Date();
        var thisdate = "<b>"+today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate()+"</b>";
        
        web3.eth.getAccounts().then(async function(accounts) {
          var receipt = await contract.methods.newItem(prodname, thisdate).send({ from: accounts[0], gas: 1000000 })
          .then(receipt => {
              var msg="<h5 style='color: #53D769'><b>Item Added Successfully</b></h5><p><b>Product ID: </b>"+receipt.events.Added.returnValues[0]+"</p>";
              qr.value = receipt.events.Added.returnValues[0];
              $bottom="<p style='color: #53D769'> <b>You may download the QR Code Now </b></p>"
              $("#alertText").html(msg);
              $("#qrious").show();

              $("#bottomText").html($bottom);
              $("#closebutton").hide();
              $("#notif").show();
              $(".customalert").show("fast","linear");
          });
        });
        $("#prodname").val('');
        
    });

    $('#form2').on('submit', function(event) {
        event.preventDefault(); // to prevent page reload when form is submitted
        prodid = $('#prodid').val();
        prodlocation = $('#prodlocation').val();
        console.log(prodid);
        console.log(prodlocation);
        var today = new Date();
        var thisdate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
        var info = "<br><br><b>Date: "+thisdate+"<br>Location: "+prodlocation+"</b>";//the date and the current location is stored here
        web3.eth.getAccounts().then(async function(accounts) {
          var receipt = await contract.methods.addState(prodid, info).send({ from: accounts[0], gas: 1000000 })
          .then(receipt => {
              var msg="Item has been UPDATED ";
              $("#alertText").html(msg);
              $("#qrious").hide();
              $("#bottomText").hide();
              $(".customalert").show("fast","linear");
          });
        });
        $("#prodid").val('');
        $("#prodlocation").val('');
      });


    function isInputNumber(evt){
      var ch = String.fromCharCode(evt.which);
      if(!(/[0-9]/.test(ch))){
          evt.preventDefault();
      }
    }

    (function() {
        var qr = window.qr = new QRious({
            element: document.getElementById('qrious'),
            size: 200,
            value: '0'
        });

        
    })();

    function openQRCamera(node) {
		var reader = new FileReader();
		reader.onload = function() {
			node.value = "";
			qrcode.callback = function(res) {
			if(res instanceof Error) {
				alert("There was no QR code found. Please double-check that the QR code is within the camera's frame before proceeding.");
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
      $("#notif").hide();
      $("#mail").hide();
      $("#closebutton").show();
      $(".customalert").show("fast","linear");
    }

  $("#aboutbtn").on("click", function(){
      showAlert("Allows user to Add/Sanction food products, on submitting a QR code and its associated Product ID will be generated which can be downloaded for future use, Once the food products are sanctioned, users must be alerted of the details. Send a Notification by clicking the button below!");
  });

    </script>
  </body>
</html>
