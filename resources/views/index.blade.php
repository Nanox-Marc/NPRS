@extends('layouts.app')

@section('content')
<div class="container-fluid main-container">
    <div class="holder holder-main">

        <div class="emp-count-container">
            <div class="emp-count">
                <p class="count-label">Total Employee :</p>
                <p class="count-data" id="countData"></p>
            </div>
        </div>
        

        <div class="selection-holder">
            <div class="holder-text">
                <p class="file-label">File Name: </p>
                <p class="file-text" id="fileName"></p>
            </div>
            
            <label class="btn btn-success btn-upload" for="input">Upload Excel File</label>
        </div>

       
        <div class="displayBox">
            <p class="winner-name" id="winnerName">FirstName, MiddleName, LastName</p>
            <p class="winner-details" id="winnerDetails">Local ID: 0000000 --- Department: NXP-Phils</p>
        </div>
        <input type="file" id="input" style="display:none;">
        
        <div class="price-group">
            <p class="price-label">Raffle Prize: </p>
            <input class="form-control" type="text" placeholder="Input Item Here" id="rafflePrize">     
        </div>

        <button type="button" class="btn btn-primary random-btn" id="runRandom" data-toggle="modal" data-target="#exampleModalCenter">RANDOM</button>
        
        
    </div>
    <div class="holder holder-list">
        <div class="table-holder">
            <div class="winner-title">LIST OF WINNERS</div>
            <table id="winnersTbl" class="table table-striped table-sm tbl-list-winners">
                <thead>
                  <tr>
                    <th scope="col" class="tbl-emp-id">No.</th>
                    <th scope="col" class="tbl-emp-id">Local ID</th>
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

<div class="modal fade duplicate-entry-modal" id="duplicateEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-body">
            <i class="fa fa-window-close" aria-hidden="true"></i>
            <p class="modal-title">DUPLICATE ENTRY!</p>
            <p class="modal-text">
                The Employee with ID Number <span class="dupSpan" id="empDupSpan"></span> was already picked from the previous prizes!
            </p>
        </div>
    
      </div>
    </div>
</div>

<div class="modal fade winner-entry-modal" id="winnerEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-body">
            
            <p class="modal-title">CONGRATULATIONS!!</p>
            <p class="modal-text" >
                <span class="winNameSpan" id="empWinNameSpan">? ? ? ? ?</span>
            </p>
            <p class="modal-text winner-id-text" >
                <span class="winIDSpan" id="empWinIDSpan"></span>
            </p>
            <p class="modal-for-text" >FOR WINNING</p>
            <p class="modal-prize-text" id="prizeWin"></p>
        </div>
    
      </div>
    </div>
</div>


 <script>

     const winnerList = [];
    
     var input = document.getElementById('input');
     input.addEventListener('change', function() {
        readXlsxFile(input.files[0]).then(function(data) {
            // console.log(randomNumber);
            // console.log(data[randomNumber]);
            var employeeCount = (data.length)-1;
            console.log(employeeCount);

            document.getElementById("fileName").innerHTML = input.files.item(0).name;
            document.getElementById("countData").innerHTML = employeeCount;
            //alert('Selected file: ' + input.files.item(0).name);
            
            var clickCount = 0;
            $("#runRandom").click(function(){
                var randomNumber = Math.floor(Math.random() * employeeCount) + 1;
                
                console.log(randomNumber);
                console.log(data[randomNumber]);

                
            
                var dEmpID = data[randomNumber][1];
                var dEmpName = data[randomNumber][3] + " " + data[randomNumber][4] + " " + data[randomNumber][2];
                var dEmpDivision = data[randomNumber][8];
                var dEmpDept = data[randomNumber][9];

                
                if(winnerList.includes(dEmpID)) {
                    // alert(dEmpName + " Cannot Win");
                    document.getElementById("empDupSpan").innerHTML = dEmpID;

                    jQuery.noConflict();
                    $('#duplicateEntryModal').modal('show');

                    document.getElementById("winnerName").innerHTML = " ";
                    document.getElementById("winnerDetails").innerHTML = " ";
                } 
                else {
                    

                    winnerList.push(dEmpID);
                    console.log(winnerList);

                    var WinnerTable = document.getElementById("winnersTbl");
                    var row = WinnerTable.insertRow(1);

                    var priceItem = document.getElementById("rafflePrize").value;
                    clickCount = clickCount+1;

                    function addNewRow() {
                        table.row
                            .add([
                                clickCount,
                                dEmpID,
                                dEmpName,
                                dEmpDept,
                                priceItem
                            ])
                            .draw(false);               
                    }
                    const table = new DataTable('#winnersTbl');
                    

                    

                    jQuery.noConflict();
                    $('#winnerEntryModal').modal('show');

                    document.getElementById("empWinNameSpan").innerHTML = "? ? ? ? ?";

                    document.getElementById("prizeWin").innerHTML = priceItem;
                    setTimeout(()=> {

                        document.getElementById("winnerName").innerHTML = data[randomNumber][3] + " " + data[randomNumber][4] + " " + data[randomNumber][2];
                        document.getElementById("winnerDetails").innerHTML = "Local ID: " + data[randomNumber][1] + " ---  Department: " + data[randomNumber][9];
                        
                        document.getElementById("empWinNameSpan").innerHTML = dEmpName;
                        document.getElementById("empWinIDSpan").innerHTML = dEmpID;

                        addNewRow();
                        
                    }
                    ,5000);

                    document.getElementById("empWinNameSpan").innerHTML = " ";
                    document.getElementById("empWinIDSpan").innerHTML = " ";
                    
                    
                }

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
        "searching": false,
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ Winners",
        },
        
        "lengthChange": false,
        "lengthMenu": [[5, 10], [5, 10]],
        
        } );

    } );

 </script>
@endsection