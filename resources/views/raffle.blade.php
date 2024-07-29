@extends('layouts.app')

@section('content')
<div class="container-fluid raffle-holder">
    <div class="center-item">
        <div class="logo-holder">
            <img class="nanox-logo" src="{{URL::asset('resources/assets/app-logo2.png')}}">
        </div>

        <div class="selection-holder">
            <div class="holder-text">
                <p class="file-label">Source File: </p>
                <p class="file-text" id="fileName"></p>
            </div>
            <input type="file" id="input" style="display:none;">
            <label class="btn btn-light btn-upload" for="input" id="uploadIDEX">Upload Excel</label>
        </div>

        <div class="price-group">
            <input class="form-control prize-input" type="text" placeholder="INPUT RAFFLE ITEM" id="rafflePrize">  
            <button type="button" class="btn btn-primary random-btn" id="runRandom" data-toggle="modal" data-target="#exampleModalCenter">RANDOM</button>   
        </div>
        
        <div class="detail-group">
            <p class="draw-text">Picking <span class="draw-text-num">1</span> lucky employee out of : <span id="countData" class="totNumber draw-text-num"></span></p>
        </div>

        <div class="winner-group">
            <button type="button" class="btn btn-win-list" data-toggle="modal" data-target="#winnerModal">
                View Winner List
            </button>
        </div>
    </div>

    <div class="displayBox" style="display:none;">
        <p class="winner-name" id="winnerName">FirstName, MiddleName, LastName</p>
        <p class="winner-details" id="winnerDetails">Local ID: 0000000 --- Department: NXP-Phils</p>
    </div>

    
</div>

<div class="modal fade duplicate-entry-modal" id="duplicateEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-body">
            <i class="fa fa-window-close" aria-hidden="true"></i>
            <p class="modal-title">DUPLICATE ENTRY!</p>
            <p class="modal-text">
                The selected employee was already picked from the previous prizes! <span class="dupSpan" id="empDupSpan"></span>
            </p>
        </div>
    
      </div>
    </div>
</div>


<div class="modal fade congrats-winner" id="congratsWinner" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <p class="emp-greetings">CONGRATULATIONS!!</p>
            <p class="emp-name" id="empWinNameSpan"></p>
            <div class="emp-details" id="empDetailsHide">
                <p class="empt-id">RAFFLE NO: <span id="empWinRNSpan"></span></p>
                <p class="empt-id">NANOX ID: <span id="empWinIDSpan"></span></p>
                <p class="emp-dept">DEPARTMENT: <span id="empWinDeptSpan"></span></p>
            </div>
            <div class="emp-win-item">
                <p class="emp-win-greetings">FOR WINNING</p>
                <p class="emp-win-prize" id="prizeWin"></p>
            </div>
            <div class="decisionButton">
                <button type="button" class="btn btn-decision" id="winRedraw">REDRAW</button>
            </div>
            <div class="decisionButtonClaim">
                <button type="button" class="btn btn-decision" id="winClaim">CLAIM</button>
            </div>
            
        </div>
    
      </div>
    </div>
</div>

<div class="modal fade modal-display-winner" id="winnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="table-holder">
            <div class="winner-title">LIST OF WINNERS</div>
            <table id="winnersTbl" class="table table-striped table-sm tbl-list-winners">
                <thead>
                <tr>
                    <th scope="col" class="tbl-emp-id">No.</th>
                    <th scope="col" class="tbl-emp-id">Local ID</th>
                    <th scope="col" class="tbl-emp-name">Raffle Number</th>
                    <th scope="col" class="tbl-emp-name">Name</th>
                    <th scope="col" class="tbl-emp-dept">Department</th>
                    <th scope="col" class="tbl-emp-prize">Prize</th>
                </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table> 
        </div>
      </div>
    </div>
</div>

<audio id="winnerAudio">
    <source src="{{URL::asset('resources/assets/win.mp3')}}" type="audio/mpeg">
</audio>

<script>

    const winnerList = [];
   
    var input = document.getElementById('input');
    input.addEventListener('change', function() {
       readXlsxFile(input.files[0]).then(function(data) {
           // console.log(randomNumber);
           // console.log(data[randomNumber]);
           var employeeCount = (data.length)-1;
           console.log(employeeCount);

           document.getElementById("uploadIDEX").style.display = "none";
           document.getElementById("fileName").innerHTML = input.files.item(0).name;
           document.getElementById("countData").innerHTML = employeeCount;
           //alert('Selected file: ' + input.files.item(0).name);
           document.getElementById("empDetailsHide").style.display = "none";

           var drumRollWinner = document.getElementById("winnerAudio"); 

           var clickCount = 0;
           $("#runRandom").click(function(){
               var randomNumber = Math.floor(Math.random() * employeeCount) + 1;
               
               console.log(randomNumber);
               console.log(data[randomNumber]);

               
           
               var dEmpID = data[randomNumber][2];
               var dEmpName = data[randomNumber][1];
               var dRaffleNo = data[randomNumber][3];

                if(dEmpName.toString().length == 3) {
                    dEmpName = "0" + dEmpName;
                }

                if(dEmpName.toString().length == 2) {
                    dEmpName = "00" + dEmpName;
                }

                if(dEmpName.toString().length == 1) {
                    dEmpName = "000" + dEmpName;
                }

                
                
                

               var dEmpDivision = data[randomNumber][8];
               var dEmpDept = data[randomNumber][7];

               
               if(winnerList.includes(dEmpID)) {
                   // alert(dEmpName + " Cannot Win");
                //    document.getElementById("empDupSpan").innerHTML = dEmpID;

                   jQuery.noConflict();
                   $('#duplicateEntryModal').modal('show');

                   document.getElementById("winnerName").innerHTML = " ";
                   document.getElementById("winnerDetails").innerHTML = " ";
               } 
               else {

                
                   

                   winnerList.push(dEmpID);
                   console.log(winnerList);

                //    var WinnerTable = document.getElementById("winnersTbl");
                //    var row = WinnerTable.insertRow(1);

                   var priceItem = document.getElementById("rafflePrize").value;
                //    clickCount = clickCount+1;

                //    function addNewRow() {
                //        table.row
                //            .add([
                //                clickCount,
                //                dEmpID,
                //                dEmpName,
                //                dEmpDept,
                //                priceItem
                //            ])
                //            .draw(false);               
                //    }
                //    const table = new DataTable('#winnersTbl');
                   

                   

                   jQuery.noConflict();
                   $('#congratsWinner').modal('show');

                   document.getElementById("empWinNameSpan").innerHTML = "? ? ? ? ?";

                   document.getElementById("prizeWin").innerHTML = priceItem;

                   drumRollWinner.play(); 

                   setTimeout(()=> {

                       document.getElementById("winnerName").innerHTML = dEmpName;
                       document.getElementById("winnerDetails").innerHTML = "Local ID: " + data[randomNumber][1] + " ---  Department: " + data[randomNumber][9];
                       
                       document.getElementById("empWinNameSpan").innerHTML = dEmpName;
                       //edited today 121523
                       document.getElementById("empWinRNSpan").innerHTML = dRaffleNo;
                       document.getElementById("empWinIDSpan").innerHTML = dEmpID;
                       document.getElementById("empWinDeptSpan").innerHTML = dEmpDept;

                    //    addNewRow();

                    document.getElementById("empDetailsHide").style.display = "flex";
                       
                   }
                   ,5200);

                   document.getElementById("empWinNameSpan").innerHTML = " ";
                   document.getElementById("empWinRNSpan").innerHTML = " ";
                   document.getElementById("empWinIDSpan").innerHTML = " ";
                   document.getElementById("empWinDeptSpan").innerHTML = " ";
                   
                   
               }

           });    


            $("#winClaim").click(function(){

                var WinnerTable = document.getElementById("winnersTbl");
                var row = WinnerTable.insertRow(1);

                clickCount = clickCount+1;
                var dEmpID = document.getElementById("empWinIDSpan").innerHTML;
                var dRaffleNo = document.getElementById("empWinRNSpan").innerHTML;
                var dEmpName = document.getElementById("winnerName").innerHTML;
                var dEmpDept = document.getElementById("empWinDeptSpan").innerHTML;
                var priceItem = document.getElementById("rafflePrize").value;

                   function addNewRow() {
                       table.row
                           .add([
                               clickCount,
                               dEmpID,
                               dRaffleNo,
                               dEmpName,
                               dEmpDept,
                               priceItem
                           ])
                           .draw(false);               
                   }
                const table = new DataTable('#winnersTbl');

                addNewRow();

                $('#congratsWinner').modal('hide');
                document.getElementById("empDetailsHide").style.display = "none";

            });

            $("#winRedraw").click(function(){
                $('#congratsWinner').modal('hide');
                document.getElementById("empDetailsHide").style.display = "none";
            });

       });
    });

    
    $(document).ready( function () {

       $('#winnersTbl').DataTable( {
           dom: 'Bfrtip',
           buttons: [
                'csv'
           ],

       "ordering": true,
       "searching": true,
       "language": {
           "info": "Showing _START_ to _END_ of _TOTAL_ Winners",
       },
       
       "lengthChange": false,
       "lengthMenu": [[10, 10], [10, 10]],
       
       } );

   } );

</script>


@endsection