<?php include('header.php'); ?>
<!DOCTYPE html>
<h1>Hello</h1>
<html>
<!--    <head>
        <meta charset="UTF-8">
        <title></title>
        
   <script src=" https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>    
     DataTables CSS 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

     DataTables JS 
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
   
    </head>
    <body>-->

    <center> <button class="btn btn-primary" id="loginModalshow-btn" data-bs-toggle="modal" data-bs-target="#loginModal">Login modal</button>  </center>     

    
    
<!--<nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="loggedInUserSection" style="display: none;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Employee Management</a>
    
    <div class="ms-auto d-flex align-items-center">
      <div >
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> <span id="loggedUsername">User</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item change-password-btn" href="#" id="" data-bs-toggle="modal" data-bs-target="#ChangepassModel">Change Password</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="#" onclick="logout()">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>-->
    
    
   
<div id="loginModal"  class="modal">
   <div class="modal-dialog modal-dialog-centered ">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Login Modal</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div> 
        
            <form id="loginForm" class="form-group" >        
                   <div class="modal-body"> 
                       <label for="firmcode" class="form-label">Firm Code:</label>
                       <input type="text" id="firmcode" name="firmcode" class="form-control" required><br>

                        <label for="username" class="form-label" >User Name:</label>
                        <input type="text" id="username" name="username" placeholder="Username" class="form-control" required><br>

                        <label for="userpassword" class="form-label">Password:</label>
                        <input type="password" id="userpassword" name="userpassword" class="form-control" required><br>

                        <a href="#" style="font-size: 0.9em;" data-bs-toggle="modal" data-bs-target="#forgotModal" >Forgot Password?</a><br><br>
                   </div>

                   <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Login</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" >Cancel</button>                      
                   </div>

           </form>         
        
    </div> 
   </div>
</div>    

    
<!-- Forgot Password Modal -->
<div id="forgotModal" class="modal fade" >
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Forgot Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="forgotForm" class="form-group">
        <div class="modal-body">
          <label for="forgotFirmCode" class="form-label">Firm Code:</label>
          <input type="text" id="forgotFirmCode" name="firmcode" class="form-control" required>

          <label for="forgotMobile" class="form-label mt-3">Mobile Number:</label>
          <input type="text" id="forgotMobile" name="mobile" class="form-control" required>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Send New PIN</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>

    </div>
  </div>
</div>



<!-- Employee Table -->
<div  id="employeeSection" style="display: none;">  
    
   <div class="d-flex justify-content-between mb-3 mt-3 ">
    <div class="ms-auto">
        <button class="btn btn-secondary" id="showClientsBtn">Show Clients</button>
        <!--<button class="btn btn-danger" id="logout-btn" onclick="logout()">Logout</button>-->
    </div>
  </div>   
    
    <div class="container mt-4">
    <div class="d-flex justify-content-between mb-3  ">
        <h4>Employee List</h4>
        <button class="btn btn-primary" id="addEmployeeBtn">Add Employee</button>
    </div>
    <table class="table table-striped table-bordered" id="employeeTable" >
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Provider ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Admin</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>    
    
</div>

<!-- Client Table -->
<div  id="clientSection" class="container mt-4" style="display: none;">  
  
    <div class="text-center mb-3">
        <h4>Client List</h4>
    </div>

    <table class="table table-striped table-bordered" id="clientTable" >
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Active</th>
                <th>Contact</th>
                <th>Action</th>
                
            </tr>
        </thead>
        <tbody></tbody>
    </table>   
</div>



<!-- Change Client Password Modal -->
<div id="ChangeClientpass" class="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Client password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="clientPassChangeForm">
            <div class="modal-body">
                <input type="hidden" id="ChangePasswordClientID" name="clientid" required>               

                <label for="newpassword" class="form-label">New Password</label>
                <input type="password" name="newpassword" id="newpassword" class="form-control" required>

                <label for="confirmpassword" class="form-label">Confirm Password</label>
                <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="submit" id="save-cilent-password" class="btn btn-primary">Save </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>                
            </div>
            </form>     
        </div>
    </div>
</div>

<!-- Change User Password Modal -->
<div id="ChangepassModel" class="modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="passchangeForm">
                <div class="modal-body">
<!--                  <label for="ChangePasswordEmployeeID" class="form-label">Change Password Employee ID:</label>-->
                    <input type="hidden" id="ChangePasswordEmployeeID" name="ChangePasswordEmployeeID" class="form-control" required>

                    <label for="NewPassword" class="form-label mt-3">New Password:</label>
                    <input type="text" id="NewPassword" name="NewPassword" class="form-control" required>  
                    
                    <label for="ReenterNewPassword" class="form-label mt-3">Reenter New Password :</label>
                    <input type="text" id="ReenterNewPassword" name="ReenterNewPassword" class="form-control" required>                        
                    
                </div>
                <div  class="modal-footer">
                    <button type="submit" class="btn btn-primary"> Ok </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>                   
                </div>
            </form>
        </div>
    </div>   
</div>

<!-- Emplyee Update Form -->
<div class="modal" id="employeeinfo">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Employee Information</h5>
              </div>
            <form id="editEmployeeForm" class="form-group" >        
                   <div class="modal-body"> 
                       <!--<label for="firmcode" class="form-label">ID:</label>-->
                       <input type="hidden" id="emp-editid" name="employeeid" class="form-control" required><br>

                        <label for="username" class="form-label" >Name:</label>
                        <input type="text" id="update-username" name="username" placeholder="Username" class="form-control"  required><br>

                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control"  required><br>
                        
                        <div id="password-field" style="display: none;">
                            <label for="employeepassword" class="form-label">Password:</label>
                            <input type="password" id="employeepassword" name="employeepassword" placeholder="Password" class="form-control"><br>
                        </div>                        

                        <label for="mobile" class="form-label" >Mobile:</label>
                        <input type="text" id="mobile" name="mobile" placeholder="mobile" class="form-control"  required><br>

                        <label for="admin" class="form-label">admin:</label>
                        <input type="text" id="admin" name="admin" class="form-control" required><br>  

                        <label for="Download-Rights" class="form-label" >Download Rights:</label>
                        <input type="text" id="Download-Rights" name="Download-Rights" placeholder="" class="form-control"  required><br>

                        <label for="Inactive" class="form-label">Inactive:</label>
                        <input type="text" id="Inactive" name="Inactive" class="form-control"  required><br>     
                   </div>

                   <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" >Cancel</button>                      
                   </div>

           </form>
    </div> 
  </div>
</div>


<script>
   
//function closeModal() {
//  document.getElementById("loginModal").style.display = "none";
//}
//    const guid = '';
//    const userid = '';
//    const username = '';
//        const guid = getCookie("guid");
//    const userid = getCookie("userid");
//    const username = getCookie("username");
document.addEventListener("DOMContentLoaded", function () {
    const guid = getCookie("guid");
    const userid = getCookie("userid");
    const username = getCookie("username");
    if (guid && userid) {
        // User is "logged in" 
        $('#loginModal').modal('hide');
        $('#loginModalshow-btn').hide();
        $('#employeeSection').show();
        $('#mainHeaderNav').show();
        $('#loggedInUserSection').show();
        $('#loggedUsername').text(username || "User");
        $('.dropdown-item.change-password-btn').attr('id', userid);
        loadEmployeeList();  
    } else {
       // $('#loginModal').modal('show');
        $('#employeeSection').hide();
        $('#loginModalshow-btn').show();
        $('#loggedInUserSection').hide();
    }
});

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}
function logout() {
    document.cookie = "guid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "userid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";   
    location.reload();  // forces re-check
}



$("#loginForm").on("submit", function(e){
    e.preventDefault();
    
    const formData =new FormData(this);
    
    $.ajax({
        url : "login.php",
        type :"POST",
        data : formData,
        dataType : "json",
        contentType : false,
        processData : false,
        success : function(data){
            if (data && data.username) {
                document.cookie = `username=${data.username}; path=/;`;
                $("#loginModal").modal("hide");
                alert("Login Successful. Welcome " + data.username);         
                $('#loginModalshow-btn').hide();
                // Show employee section
                $('#employeeSection').show();
                 // Load employee list
                $('#loggedInUserSection').show();
                $('#mainHeaderNav').show();
                $('#loggedUsername').text(data.username);  
                //$('#loggedUsername').text(username || "User");
                $('.dropdown-item.change-password-btn').attr('id', data.userID);
                    loadEmployeeList();
                //console.log(data);
                
            } else if (data.error) {
                alert("Login Failed: " + data.error);
            } else {
                alert("Login failed. Please check credentials.");
            }
       },
       error :function(xhr,status ,error){
           console.error("haves error :", error);
       }
    });
});


$("#forgotForm").on("submit", function(e){
    e.preventDefault();
    const formData= new FormData(this);
    
    $.ajax({
        
        url : "forgot_password.php",
        type :"POST",
        data : formData,
        dataType: "json",
        contentType: false,
        processData:false,
        success: function(response){
            if(response.message === 'done'){
                console.log(response);
                alert("New PIN sent to your email and mobile.");
                  $("#forgotModal").modal("hide");
                }
                else{
                    console.log(response);
                    alert(response.message);
                }   
        },
        error :function(xhr,status ,error){
        console.error("error :", error);
        }
    });
});


function loadEmployeeList() {
      const userid = getCookie("userid");
      
    $.ajax({
        url: 'employeelist.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
                    const providerId = response.providerid;
                    const users = response.users;

            let table = $('#employeeTable').DataTable();
            table.clear().destroy(); // Reinitialize if needed

            $('#employeeTable tbody').empty();

            let isAdmin = false;

            users.forEach(function(user) {
                if (parseInt(user.id) === parseInt(userid)) {
                    isAdmin = parseInt(user.admin) === 1;
                }
            });

            $.each(users, function(index, user) {
                const isCurrentUser = parseInt(user.id) === parseInt(userid);

                if (isCurrentUser) {
                   // isAdmin = parseInt(user.admin) === 1;
                    return true; // Skip showing logged-in user
                }

                const disableAttr = isAdmin ? '' : 'disabled';

                const row = `
                    <tr>
                        <td>${user.id}</td>
                        <td>${providerId}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.mobile}</td>
                        <td>${user.admin === 1 ? 'admin' : 'employee'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning me-1 change-password-btn" 
                                    id="${user.id}" title="Change Password"
                                    data-bs-toggle="modal" data-bs-target="#ChangepassModel" ${disableAttr}>
                                <i class="bi bi-key"></i>
                            </button>

                            <button class="btn btn-sm btn-info me-1 edit-employee" 
                                    id="${user.id}" title="Edit" >
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button class="btn btn-sm btn-danger me-1 delete-employee" 
                                    id="${user.id}" title="Delete" ${disableAttr}>
                                <i class="bi bi-trash"></i>
                            </button>

                            <div class="form-check form-switch d-inline-block ms-2">
                                <input class="form-check-input activation-toggle" type="checkbox"
                                    data-employeeid="${user.id}" ${user.Inactive == 1 ? 'checked' : ''}
                                    ${disableAttr}>
                            </div>
                        </td>
                    </tr>
                `;

                $('#employeeTable tbody').append(row);
            });


            $('#employeeTable').DataTable();
        },
        error: function(err) {
            console.error("Failed to fetch employee list", err);
        }
    });
}

//Change Employee Password
$(document).on('click','.change-password-btn',function(){
 const id=$(this).attr('id');
 const userfromid=$('#ChangePasswordEmployeeID').val(id);

});

$('#passchangeForm').on('submit',function(e){
   
    e.preventDefault(); 
    
    const current_changepass_userid=$('#ChangePasswordEmployeeID').val();
    const logged_userid=getCookie("userid");
    
    const formData = new FormData(this);
    
    $.ajax({
        url :"employeechangepassword.php",
        type : "POST",
        data : formData,
        dataType: "json",
        contentType: false,
        processData:false,
        success : function(response){
            //const res = JSON.parse(response);
            if (response.message === 'done') {
   
                        if (parseInt(current_changepass_userid) === parseInt(logged_userid)) {
                            logout();
                            window.location.reload(); 
                         }

                $('#ChangepassModel').modal('hide');
                alert("Password Updated Successfully");
            }
            else{
                alert(response.message);
            }
        },
        error: function () {
            alert('Server error. Please try again.');
        }
    });
});


//Edit Employee
$(document).on('click','.edit-employee',function(){
 const id=$(this).attr('id');

 $('#employeeinfo').modal('show');
  $('#password-field').hide();
  $('#emp-editid').val(id);
  
  $.ajax({
      url :"employeeinfo.php",
      type:"POST",
      data :{employeeid:id },
      //dataType: "json",
      success : function(data){   
          var response=JSON.parse(data);
      if(response.message==="success"){      
          $('#update-username').val(response.username);
          $('#email').val(response.email);
          $('#mobile').val(response.mobile);
        $('#admin').val(response.admin == 1 ? "Yes" : "No");
        $('#Download-Rights').val(response.downloadrights == 1 ? "Yes" : "No");
        $('#Inactive').val(response.Inactive == 1 ? "Yes" : "No");
        }
      }
  });
  
  
});

//Save Employee details
$("#editEmployeeForm").on("submit",function(e){
      e.preventDefault();
      const formData= $(this).serialize();
      
        $.ajax({
              url :"employeeinfosave.php",
              type:"POST",
              data :formData,
              //dataType: "json",
              success : function(data){   
                  var response=JSON.parse(data);
              if(response.status==="success"){ 
                    $('#employeeinfo').modal('hide');
                    loadEmployeeList();
                    alert(response.message);                  
                }
                else{
                    alert(response.status+":"+response.message);
                }
              }, 
        error: function(xhr, status, error) {
            console.log('Status:', status);
            console.log('Error:', error);
            console.log('Response:', xhr.responseText);
            alert('Server error: ' + error);
        }

          });           
  });
  
$("#addEmployeeBtn").on("click",function(){
 $('#emp-editid').val(0);   
 $('#password-field').show();
 $('#employeeinfo').modal('show');
 });
 
 $("#employeeTable").on("change",'.activation-toggle',function(){
   const employeeid=$(this).data('employeeid');
   const isChecked = $(this).is(':checked'); //true or false
   //alert(isChecked);
   const activatedeactivate= isChecked ? 1 : 0 ;
   
   $.ajax({
       url :"employeeactivatedeactivate.php",
       type: "POST",
       data :{   employeeid:employeeid, 
                 activatedeactivate:activatedeactivate,
                 isChecked:isChecked },
       success : function(data){
          var  response =JSON.parse(data);
           console.log(response.status);
         //  console.log(response.ischeked);
       }
       
   });
   
 });
 
 $(document).on('click','.delete-employee',function(){
    const id=$(this).attr('id');
    
    $.ajax({
        url:"employeedelete.php",
        type:"POST",
        data:{id : id},
        success :function(responce){
            var  data =JSON.parse(responce);
        if(data.status=='success'){
            window.location.reload();
            alert(data.message);
            
        }else{
            alert(data.status+":"+data.message);
        }
                      
        },
    });
 });
 
 
 //Show Client 
 $('#showClientsBtn').on('click', function(){
     $.ajax({
         url : 'clientlist.php', 
         type :'POST',
         dataType :'json',
         success : function(response){
             $('#clientSection').show();
             
            let table = $('#clientTable').DataTable();
            table.clear().destroy(); // Reinitialize if needed

            $('#clientTable tbody').empty();

            $.each(response, function(index, response) {
                const row = `
                    <tr>
                        <td>${response.id}</td>
                        <td>${response.name}</td>
                        <td>${response.active}</td>
                        <td> ${response.contact}</td>
                        
                        <td>
                            <button class="btn btn-sm btn-warning me-1 change-cilent-password-btn" id="${response.id}" title="Change Client Password" data-bs-toggle="modal" data-bs-target="#ChangeClientpass"><i class="bi bi-key"></i></button>
                            <!--<button class="btn btn-sm btn-info me-1 edit-employee" id="${response.id}" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>-->
    
                        </td>
                    </tr>
                `;
                $('#clientTable tbody').append(row);
            });

            $('#clientTable').DataTable();               
         },
                 error: function(err) {
            console.error("Failed to fetch client list", err);
        }

     });
 });

//Change Client Password
$(document).on('click','.change-cilent-password-btn',function(){
var id = $(this).attr('id');
//alert(id);
$('#ChangePasswordClientID').val(id);
});    


$('#clientPassChangeForm').on('submit',function(e){  
    e.preventDefault();
    const formData = new FormData(this);
    $.ajax({
        url :"clientchangepassword.php",
        type : "POST",
        data : formData,
        dataType: "json",
        contentType: false,
        processData:false,
        success : function(response){
            //const res = JSON.parse(response);
            if (response.message === 'done') {
                $("#ChangeClientpass").modal("hide");
                alert("Client password updated successfully.");
                $("#clientPassChangeForm")[0].reset();
            }
            else{
                alert(response.message);
            }
        },
        error: function () {
            alert('Server error. Please try again.');
        }
    });
});

</script>






    </body>
</html>
