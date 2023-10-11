<?php
session_start();

if (!isset($_SESSION['logged-in'])) {
    die(header('Location: index'));
}
$_SESSION['telegram_id'] = '395210768';
?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>DMME</title>
      <link rel="icon" href="images/favicons/favicon.ico">
      <link rel="apple-touch-icon" href="images/favicons/apple-touch-icon.png">
      <link rel="apple-touch-icon" sizes="72x72" href="images/favicons/apple-touch-icon-72x72.png">
      <link rel="apple-touch-icon" sizes="114x114" href="images/favicons/apple-touch-icon-114x114.png">
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
      <link href="css/custom.css" rel="stylesheet">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

      <script src= 
"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.3/moment.min.js"> 
    </script> 
        <style>

article{
	background: #ddd;
	width: 100%;
	position: relative;
	padding: 20px;
	box-sizing: border-box;
	border-radius: 10px;
}

article .line{
	width: 100%;
	height: 20px;
	background: #bbb;
	margin: 20px 0;
	border-radius: 5px;
}

article .shimmer{
	position: absolute;
	top: 0;
	left: 0;
	width: 50%;
	height: 100%;

	background: linear-gradient(100deg,
	rgba(255,255,255,0) 20%,
	rgba(255,255,255,0.5) 50%,
	rgba(255,255,255,0) 80%);

	animation: shimmer 2s infinite linear;
}

@keyframes shimmer{
	from {
		transform: translateX(-200%);
	}
	to{
		transform: translateX(200%);
	}
}

        </style>
   </head>
   <body>
      <main>
        <div class="header p-3 mb-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <img src="images/logo.png" width="54">
                    </div>
                    <div class="col-lg-2">
                        <a href="logout.php" class="btn btn-sm btn-secondary">Logout</a>
                    </div>
                </div>

            </div>
        </div>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-3 mb-4 mb-lg-4">
                <div class="p-3 bg-body rounded shadow-sm">
                    <h6 class="border-bottom pb-2 mb-3">Filter</h6>
                    <label>From Date</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" placeholder="Select Date" id="from_date">
                    </div>
                    <label>To Date</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" placeholder="Select Date" id="to_date">
                    </div>
                      <select class="form-select" aria-label="Default select example" id="transaction_type">
                        <option value="">Type of Transaction</option>
                        <option value="">All</option>
                        <option value="receive">Receive</option>
                        <option value="send">Send</option>
                        <option value="withdraw">Withdrawal</option>
                        <option value="deposit">Deposit</option>
                      </select>

                      <button class="btn btn-warning form-control" style="margin-top:20px;" id="Apply_filter">Apply Filter</button>
                </div>
            </div>
            <div class="col-lg-9">
                <h3 class="mb-4">Transaction History</h3>
                <div class="container" id="preloader">
                    <article>
                        <div class="line"></div>
                        <div class="line"></div>
                        <div class="shimmer"></div>
                    </article>
                </div>
                <div id="all_transaction"></div>
                <input type="hidden" id="page" value="0">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12" style="text-align:right"><button id="previous">Previous</button><button id="next">Next</button></div>
                <div id="all_transaction"></div>
               
            </div>
         </div>
      </div>
   </main>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        
        <script>
            // Function to parse dd-mm-yyyy date format and convert it to a Date object
function parseDate(dateString) {
    var parts = dateString.split("-");
    return new Date(parts[2], parts[1] - 1, parts[0]); // months are 0-based
}

// Function to check if a date is between two other dates
function isDateBetween(startDate, endDate, targetDate) {
    return  startDate <= targetDate && targetDate <= endDate;
}
            function convert(str) {
  var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
  return [ day,mnth, date.getFullYear()].join("-");
}


function converttime(str) {
        var date = new Date(str),
        hours  = ("0" + date.getHours()).slice(-2);
        minutes = ("0" + date.getMinutes()).slice(-2);
        seconds = ("0" + date.getSeconds()).slice(-2);
  return [ hours,minutes, seconds].join(":");
}
            $(document).ready(function(){

                console.log('session - '+<?php print_r($_SESSION['telegram_id']); ?>);
                var page = $('#page').val();
                if(parseInt(page) == parseInt(0)){
                    $('#previous').hide();
                }else{
                    $('#previous').show();
                }
                $('#preloader').show();

                $.ajax({
                    url: "api/getTransactions.php",
                    type: "POST",
                    data:{
                        user_id:<?php print_r($_SESSION['telegram_id']); ?>,
                        page:page
                    },
                    dataType: 'json',
                    success: function(data){
                        $('#preloader').hide();
                        var html='';
                        var transactions = data.data.transactions;
                        //console.log(transactions);
                        
                        for(var i=0;i<transactions.length;i++){ 
                            let milliseconds = transactions[i]['timestamp'];
                            
                            let date = new Date(milliseconds);
                            // console.log("Milliseconds = " + date.toString());

                            var final_date =convert(date.toString());

                            var final_time =converttime(date.toString());

                            html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                <div class="row">\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">From</strong>\
                        '+transactions[i]['from']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">To</strong>\
                        '+transactions[i]['to']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">Description</strong>\
                        '+transactions[i]['description']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">Amount (BTC)</strong>\
                        '+(transactions[i]['amount']/97589538).toFixed(10)+'\
                    </div>\
                </div>';
                if(transactions[i]['kind'] == 'send')
                html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';
                else{
                    html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                   
                }
               html+='</div>'
                        }

                        $('#all_transaction').html(html);
                        // for(var i=0;i<data.length;i++){
                        //     html+='<tr>\
                        //         <td>'+(parseInt(i)+1)+'</td>\
                        //         <td>'+data[i].email+'</td>\
                        //     </tr>';
                        // }

                        // $('#set_waitlist_data').html(html);
                    
                    }
            });
            })



            $('#next').click(function(){
                $('#all_transaction').html('');
                $('#preloader').show();
                var page = parseInt($('#page').val())+parseInt(1);
                $('#page').val(page);
                if(parseInt(page) == parseInt(0)){
                    $('#previous').hide();
                }else{
                    $('#previous').show();
                }
                $.ajax({
                    url: "api/getTransactions.php",
                    type: "POST",
                    data:{
                        user_id:<?php print_r($_SESSION['telegram_id']); ?>,
                        page:page
                    },
                    dataType: 'json',
                    success: function(data){
                        $('#preloader').hide();
                        var html='';
                        var transactions = data.data.transactions;
                        
                        //console.log(transactions);
                        for(var i=0;i<transactions.length;i++){ 
                            let milliseconds = transactions[i]['timestamp'];
                            
                            let date = new Date(milliseconds);
                            // console.log("Milliseconds = " + date.toString());

                            var final_date =convert(date.toString());

                            var final_time =converttime(date.toString());

                            html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                <div class="row">\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">From</strong>\
                        '+transactions[i]['from']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">To</strong>\
                        '+transactions[i]['to']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">Description</strong>\
                        '+transactions[i]['description']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">Amount (BTC)</strong>\
                        '+(transactions[i]['amount']/97589538).toFixed(10)+'\
                    </div>\
                </div>';
                if(transactions[i]['kind'] == 'send')
                html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                else{
                    html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';
                   
                }
               html+='</div>'
                        }

                        $('#all_transaction').html(html);
                        if(parseInt(transactions.length)==0){
                            $('#next').hide();

                            $('#all_transaction').html('No data found');
                        }
                        // for(var i=0;i<data.length;i++){
                        //     html+='<tr>\
                        //         <td>'+(parseInt(i)+1)+'</td>\
                        //         <td>'+data[i].email+'</td>\
                        //     </tr>';
                        // }

                        // $('#set_waitlist_data').html(html);
                    
                    }
            });
            })


            $('#previous').click(function(){
                $('#all_transaction').html('');
                $('#preloader').show();
                var page = parseInt($('#page').val())-parseInt(1);
                $('#page').val(page);
                $('#next').show();
                if(parseInt(page) == parseInt(0)){
                    $('#previous').hide();
                }else{
                    $('#previous').show();
                }
                $.ajax({
                    url: "api/getTransactions.php",
                    type: "POST",
                    data:{
                        user_id:<?php print_r($_SESSION['telegram_id']); ?>,
                        page:page
                    },
                    dataType: 'json',
                    success: function(data){
                        $('#preloader').hide();
                        var html='';
                        var transactions = data.data.transactions;
                        //console.log(transactions);
                        for(var i=0;i<transactions.length;i++){ 
                            let milliseconds = transactions[i]['timestamp'];
                            
                            let date = new Date(milliseconds);
                            // console.log("Milliseconds = " + date.toString());

                            var final_date =convert(date.toString());

                            var final_time =converttime(date.toString());

                            html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                <div class="row">\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">From</strong>\
                        '+transactions[i]['from']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">To</strong>\
                        '+transactions[i]['to']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">Description</strong>\
                        '+transactions[i]['description']+'\
                    </div>\
                    <div class="col-lg-2 mb-2 mb-lg-0">\
                        <strong class="d-block text-gray-dark">Amount (BTC)</strong>\
                        '+(transactions[i]['amount']/97589538).toFixed(10)+'\
                    </div>\
                </div>';
                if(transactions[i]['kind'] == 'send')
                html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                else{
                    html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';
                   
                }
               html+='</div>'
                        }

                        $('#all_transaction').html(html);
                        // for(var i=0;i<data.length;i++){
                        //     html+='<tr>\
                        //         <td>'+(parseInt(i)+1)+'</td>\
                        //         <td>'+data[i].email+'</td>\
                        //     </tr>';
                        // }

                        // $('#set_waitlist_data').html(html);
                    
                    }
            });
            })

            $('#Apply_filter').click(function(){
                debugger;
                if($('#from_date').val()=='' && $('#to_date').val()!=''){
                    alert('From date is required');
                }

                if($('#to_date').val()=='' && $('#from_date').val()!=''){
                    alert('To date is required');
                }

                //alert($('#transaction_type').val());


                $('#all_transaction').html('');
                $('#preloader').show();
                var page = 0;
                $('#page').val(page);
                $('#next').show();
                if(parseInt(page) == parseInt(0)){
                    $('#previous').hide();
                }else{
                    $('#previous').show();
                }
                $.ajax({
                    url: "api/getTransactions.php",
                    type: "POST",
                    data:{
                        user_id:'395210768',
                        page:page
                    },
                    dataType: 'json',
                    success: function(data){
                        $('#preloader').hide();
                        var html='';
                        var transactions = data.data.transactions;
                        //console.log(transactions);
                        for(var i=0;i<transactions.length;i++){ 
                            let milliseconds = transactions[i]['timestamp'];
                            
                            let date = new Date(milliseconds);
                            // console.log("Milliseconds = " + date.toString());

                            var final_date =convert(date.toString());

                            var final_time =converttime(date.toString());
                            
                            if($('#transaction_type').val() == '' && $('#from_date').val()=='' && $('#to_date').val()==''){
        
                                html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                                        <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                                        <div class="row">\
                                        <div class="col-lg-2 mb-2 mb-lg-0">\
                                            <strong class="d-block text-gray-dark">From</strong>\
                                            '+transactions[i]['from']+'\
                                        </div>\
                                        <div class="col-lg-2 mb-2 mb-lg-0">\
                                            <strong class="d-block text-gray-dark">To</strong>\
                                            '+transactions[i]['to']+'\
                                        </div>\
                                        <div class="col-lg-2 mb-2 mb-lg-0">\
                                            <strong class="d-block text-gray-dark">Description</strong>\
                                            '+transactions[i]['description']+'\
                                        </div>\
                                        <div class="col-lg-2 mb-2 mb-lg-0">\
                                            <strong class="d-block text-gray-dark">Amount</strong>\
                                            '+transactions[i]['amount']+'\
                                        </div>\
                                    </div>';
                                        if(transactions[i]['kind'] == 'send')
                                        html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                                        else{
                                            html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';
                                        
                                        }
                                    html+='</div>'
                            }

                            else if($('#transaction_type').val()!='' && $('#from_date').val()!='' && $('#to_date').val()!=''){
                                
                                var startDateString = $('#from_date').val(); // Start date in dd-mm-yyyy format
                                var endDateString = $('#to_date').val(); // End date in dd-mm-yyyy format
                                var targetDateString = final_date; // Target date in dd-mm-yyyy format

                                var startDate = new Date(startDateString);
                                var endDate = new Date(endDateString);
                                var targetDate = parseDate(targetDateString);

                                if (targetDate > startDate && targetDate < endDate) {
                                    console.log("Target date is between the start and end dates.");
                                } else {
                                    console.log("Target date is not between the start and end dates.");
                                }

                                if (targetDate > startDate && targetDate < endDate &&  transactions[i]['kind'] == $('#transaction_type').val()) {
                
                                html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                                <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                                <div class="row">\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">From</strong>\
                                        '+transactions[i]['from']+'\
                                    </div>\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">To</strong>\
                                        '+transactions[i]['to']+'\
                                    </div>\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">Description</strong>\
                                        '+transactions[i]['description']+'\
                                    </div>\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">Amount</strong>\
                                        '+transactions[i]['amount']+'\
                                    </div>\
                                </div>';
                                if(transactions[i]['kind'] == 'send')
                                html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                                else{
                                    html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';
                                
                                }
                                html+='</div>'
                              }
                            }
                            else if($('#transaction_type').val() == '' && $('#from_date').val()!='' && $('#to_date').val()!=''){
                               
                                var startDateString = $('#from_date').val(); // Start date in dd-mm-yyyy format
                                var endDateString = $('#to_date').val(); // End date in dd-mm-yyyy format
                                var targetDateString = final_date; // Target date in dd-mm-yyyy format

                                var startDate = new Date(startDateString);
                                var endDate = new Date(endDateString);
                                var targetDate = parseDate(targetDateString);

                                console.log(targetDate > startDate && targetDate < endDate);

                                if (targetDate > startDate && targetDate < endDate) {
                                    console.log("Target date is between the start and end dates.");
                                } else {
                                    console.log("Target date is not between the start and end dates. ");
                                }

                               if (targetDate > startDate && targetDate < endDate) {

                                html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                                <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                                <div class="row">\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">From</strong>\
                                        '+transactions[i]['from']+'\
                                    </div>\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">To</strong>\
                                        '+transactions[i]['to']+'\
                                    </div>\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">Description</strong>\
                                        '+transactions[i]['description']+'\
                                    </div>\
                                    <div class="col-lg-2 mb-2 mb-lg-0">\
                                        <strong class="d-block text-gray-dark">Amount</strong>\
                                        '+transactions[i]['amount']+'\
                                    </div>\
                                </div>';
                                if(transactions[i]['kind'] == 'send')
                                html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                                else{
                                    html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';
                                
                                }
                                html+='</div>'
                              }
                            
                            }else if($('#transaction_type').val()!= '' && $('#from_date').val()=='' && $('#to_date').val()==''){

                                if (transactions[i]['kind'] == $('#transaction_type').val()) {

                                        html+='<div class="p-3 bg-body rounded shadow-sm mb-3 position-relative">\
                                        <h6 class="border-bottom pb-2 mb-2 fw-light">'+final_date+' <small class="text-secondary">'+final_time+'</small></h6>\
                                        <div class="row">\
                                            <div class="col-lg-2 mb-2 mb-lg-0">\
                                                <strong class="d-block text-gray-dark">From</strong>\
                                                '+transactions[i]['from']+'\
                                            </div>\
                                            <div class="col-lg-2 mb-2 mb-lg-0">\
                                                <strong class="d-block text-gray-dark">To</strong>\
                                                '+transactions[i]['to']+'\
                                            </div>\
                                            <div class="col-lg-2 mb-2 mb-lg-0">\
                                                <strong class="d-block text-gray-dark">Description</strong>\
                                                '+transactions[i]['description']+'\
                                            </div>\
                                            <div class="col-lg-2 mb-2 mb-lg-0">\
                                                <strong class="d-block text-gray-dark">Amount</strong>\
                                                '+transactions[i]['amount']+'\
                                            </div>\
                                        </div>';
                                        if(transactions[i]['kind'] == 'send')
                                        html+='<div class="arrow badge bg-danger bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-down-short fs-4 text-white"></i></div>';
                                        else{
                                            html+='<div class="arrow badge bg-success bg-opacity-75 px-1 py-1"><i class="bi bi-arrow-up-short fs-4 text-white"></i></div>';

                                        }
                                      html+='</div>'
                                }
                            }
                        }

                        $('#all_transaction').html(html);
                        // for(var i=0;i<data.length;i++){
                        //     html+='<tr>\
                        //         <td>'+(parseInt(i)+1)+'</td>\
                        //         <td>'+data[i].email+'</td>\
                        //     </tr>';
                        // }

                        // $('#set_waitlist_data').html(html);
                    
                    }
                });


            })
        </script>
        

<script>
	// setInterval(function() {
    //             $.ajax({
    //                 url: "checksession.php",
    //                 type:'POST',
    //                 dataType: 'json',
    //                 success: function(result){
    //                     if(result.status){
    //                         window.location.href="login.php";
    //                     }
    //                	}       
    //             });
    //         },5000);
</script>
    </body>
</html>