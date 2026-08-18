<?php include('../header.php'); ?>
<!-- JS + CSS Libraries 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

 Bootstrap 5 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

 DataTables 
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

 Bootstrap Icons 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

 jsPDF 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>-->

<!-- ✨ Custom Styling -->
<style>
    body {
        background-color: #f5f7fa;
        font-family: 'Segoe UI', sans-serif;
    }

/*    .dropdown {
        margin: 40px auto 20px auto;
        text-align: center;
    }

    .dropdown-toggle {
        font-size: 1.2rem;
        padding: 10px 20px;
    }*/

    #reportOutput {
        background-color: #ffffff;
        border-radius: 10px;
        padding: 30px;
        border: 1px solid #dee2e6;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
        font-family: monospace;
    }

    .modal-title {
        font-weight: bold;
        color: #0d6efd;
    }

    .modal-body label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 6px;
    }

    .modal-footer .btn {
        min-width: 120px;
    }
</style>


<!-- 💡 Report Dropdown -->
<div class="dropdown text-center">
  <button class="btn btn-info dropdown-toggle" type="button" id="reportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-file-earmark-text-fill me-2 mt-4"></i> Generate Report
  </button>
  <ul class="dropdown-menu" aria-labelledby="reportDropdown">
    <li><a class="dropdown-item" href="#" id="employeeReportBtn"><i class="bi bi-person-lines-fill me-2"></i>Employee Report</a></li>
    <li><a class="dropdown-item" href="#" id="clientReportBtn"><i class="bi bi-briefcase-fill me-2"></i>Client Report</a></li>
    <li><a class="dropdown-item" href="#" id="userSummaryReportBtn"><i class="bi bi-bar-chart-fill me-2"></i>User Summary</a></li>
  </ul>
</div>

<!--  Report Output -->
<div id="reportOutput" class="container mt-4"></div>

<!-- Employee Report Modal -->
<div class="modal fade" id="employeeReportModal" tabindex="-1" aria-labelledby="employeeReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="employeeReportForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="employeeReportModalLabel">Employee Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <label for="empFromDate" class="form-label">From Date:</label>
        <input type="date" id="empFromDate" name="from" class="form-control mb-3" required>

        <label for="empToDate" class="form-label">To Date:</label>
        <input type="date" id="empToDate" name="to" class="form-control mb-3" required>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-pdf-fill me-1"></i>Generate PDF</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- Client Report Modal -->
<div class="modal fade" id="clientReportModal" tabindex="-1" aria-labelledby="clientReportModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="clientReportForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="clientReportModalLabel">Client Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <label for="clientFromDate" class="form-label">From Date:</label>
        <input type="date" id="clientFromDate" name="from" class="form-control mb-3" required>

        <label for="clientToDate" class="form-label">To Date:</label>
        <input type="date" id="clientToDate" name="to" class="form-control mb-3" required>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-pdf-fill me-1"></i>Generate PDF</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<!-- User Summary Report Modal -->
<div class="modal fade" id="userSummaryModal" tabindex="-1" aria-labelledby="userSummaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="userSummaryReportForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userSummaryModalLabel">User Summary Report</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <label for="userFromDate" class="form-label">From Date:</label>
        <input type="date" id="userFromDate" name="from" class="form-control mb-3" required>

        <label for="userToDate" class="form-label">To Date:</label>
        <input type="date" id="userToDate" name="to" class="form-control mb-3" required>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-pdf-fill me-1"></i>Generate PDF</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>


<script>
// Open modals
$('#employeeReportBtn').on('click', function () {
    $('#employeeReportModal').modal('show');
});

$('#clientReportBtn').on('click', function () {
    $('#clientReportModal').modal('show');
});

$('#userSummaryReportBtn').on('click', function () {
   $('#userSummaryModal').modal('show'); 
});

// Submit Employee Report
/**$('#employeeReportForm').on('submit', function (e) {
    e.preventDefault();
    const from = $('#empFromDate').val();
    const to = $('#empToDate').val();
    var key = "employeeReport";
    
    $.ajax({
        url:'report.php' ,
        method: 'POST',
        dataType : 'json',
        data : {from:from,to :to,key:key},
        success : function(response){
             console.log("Report data:", response);
             alert(response);
        },
        error: function(xhr, status, error) {
        console.error("Error loading report:", error);
         }
         
        });
    //window.open(`employee_report.php?from=${from}&to=${to}`, '_blank');
});*/
 $('#employeeReportForm').on('submit', function (e) {
    e.preventDefault();
    const from = $('#empFromDate').val();
    const to = $('#empToDate').val();
    const key = "employeeReport";

    $.ajax({
        url: 'report.php',
        method: 'POST',
        dataType: 'json',
        data: { from: from, to: to, key: key },
        
            success: function (response) {
                if (!response || !Array.isArray(response.employees)) {
                    alert("Invalid response format");
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                const from = $('#empFromDate').val();
                const to = $('#empToDate').val();

                
                const username = response.username;
               // const companyName = "ABC Corp";
                //const branch = "New York";
                const currentDate = new Date().toLocaleDateString();
                let pageNo = 1;

                doc.setFontSize(12);
                doc.text(`Username: ${username}`, 10, 10);
                //doc.text(`Company: ${companyName}`, 90, 10);
                doc.text(`Date: ${currentDate}`, 160, 10);

                //doc.text(`Branch: ${branch}`, 10, 18);
                doc.text(`Report: Employee Performance`, 90, 18);
                doc.text(`Page No: ${pageNo}`, 160, 18);

                doc.text(`Report From: ${from} To: ${to}`, 10, 26);

                const tableData = [];
                response.employees.forEach((emp, index) => {
                    tableData.push([
                        index + 1,
                        emp.first_name + " " + emp.last_name,
                        "-", // email not present in DB output
                        "-", // performance not present in DB output
                        emp.job,
                        emp.department_id,
                        emp.salary
                    ]);
                });

                doc.autoTable({
                    head: [['Sr.No', 'Emp Name', 'Email', 'Performance', 'Job Position', 'Department', 'Salary']],
                    body: tableData,
                    startY: 32,
                    theme: 'grid',
                    styles: { fontSize: 10 },
                    headStyles: { fillColor: [41, 128, 185] }
                });

                const finalY = doc.lastAutoTable.finalY + 10;
                doc.setFontSize(12);
                doc.text(`Total Salary = ${response.total_salary.toFixed(2)}`, 10, finalY);

                doc.save(`Employee_Report_${from}_to_${to}.pdf`);
            },

        error: function (xhr, status, error) {
            console.error("Error loading report:", error);
        }
    });
});


// Submit Client Report
//$('#clientReportForm').on('submit', function (e) {
//    e.preventDefault();
//    const from = $('#clientFromDate').val();
//    const to = $('#clientToDate').val();
//    var key = "clientReport";
//    
//    $.ajax({
//        url:'report.php' ,
//        method: 'POST',
//        dataType : 'json',
//        data : {from:from,to :to,key:key},
//        success : function(response){
//             console.log("Report data:", response);
//             alert(response);
//        },
//        error: function(xhr, status, error) {
//        console.error("Error loading report:", error);
//         }
//         
//        });        
//        
//    //window.open(`client_report.php?from=${from}&to=${to}`, '_blank');
//});


$('#clientReportForm').on('submit', function (e) {
    e.preventDefault();
    const from = $('#clientFromDate').val();
    const to = $('#clientToDate').val();
    const key = "clientReport";

    $.ajax({
        url: 'report.php',
        method: 'POST',
        dataType: 'json',
        data: { from: from, to: to, key: key },
        success: function (response) {
            if (!Array.isArray(response.client)) {
                alert(response.error);
                return;
            }

    const username = response.username;
    //const companyName = "ABC Corp";
    //const branch = "New York";
    const reportTitle = "Client Report";

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const currentDate = new Date().toLocaleDateString();
            let pageNo = 1;

            // Header
            doc.setFontSize(12);
            doc.text(`Username: ${username}`, 10, 10);
            //doc.text(`Company: ${companyName}`, 90, 10);
            doc.text(`Date: ${currentDate}`, 160, 10);

            //doc.text(`Branch: ${branch}`, 10, 18);
            doc.text(`Report: ${reportTitle}`, 90, 18);
            doc.text(`Page No: ${pageNo}`, 160, 18);

            doc.text(`Report From: ${from} To: ${to}`, 10, 26);

            // Table data
            const tableData = response.client.map((client, index) => [
                index + 1,
                client.name,
                client.providerid,
                client.active == 1 ? 'Yes' : 'No',
                client.contact,
                client.date
            ]);

            doc.autoTable({
                head: [['Sr.No', 'Client Name', 'Provider ID', 'Active', 'Contact', 'Date']],
                body: tableData,
                startY: 32,
                theme: 'grid',
                styles: { fontSize: 10 },
                headStyles: { fillColor: [41, 128, 185] }
            });

            doc.save(`Client_Report_${from}_to_${to}.pdf`);
        },
        error: function (xhr, status, error) {
            console.error("Error loading report:", error);
        }
    });
});



// Submit User Summary Report

/*$('#userSummaryReportForm').on('submit', function (e) {
    alert("hello");
    e.preventDefault();
    const from = $('#userFromDate').val();
    const to = $('#userToDate').val();
    var key = "userSummary";
    
    $.ajax({
        url:'report.php' ,
        method: 'POST',
        dataType : 'json',
        data : {from:from,to :to,key:key},
        success : function(response){
             console.log("Report data:", response);
             alert(response);
        },
        error: function(xhr, status, error) {
        console.error("Error loading report:", error);
         }
         
        });      
    //window.open(`user_summary_report.php?from=${from}&to=${to}`, '_blank');
});*/
$('#userSummaryReportForm').on('submit', function (e) {
    alert("hello");
    e.preventDefault();
    const from = $('#userFromDate').val();
    const to = $('#userToDate').val();
    var key = "userSummary";
    
    $.ajax({
        url:'report.php' ,
        method: 'POST',
        dataType : 'json',
        data : {from:from,to :to,key:key},
//            success: function (response) {
//                if (!response || !response.summary) {
//                    alert("Invalid summary data");
//                    return;
//                }
//
//                const { jsPDF } = window.jspdf;
//                const doc = new jsPDF();
//
//                const currentDate = new Date().toLocaleDateString();
//                const username = response.username || 'Unknown';
//                const from = $('#userFromDate').val();
//                const to = $('#userToDate').val();
//             //   const branch = "New York"; // You can replace this with dynamic branch info if available
//               // const companyName = "ABC Corp"; // Also make dynamic if needed
//                const reportTitle = "User Summary Report";
//                let pageNo = 1;
//                let y = 10;
//
//                // Header
//                doc.setFontSize(12);
//                doc.text(`Username: ${username}`, 10, y);
//               // doc.text(`Company: ${companyName}`, 90, y);
//                doc.text(`Date: ${currentDate}`, 160, y, { align: 'right' });
//
//                y += 8;
//                //doc.text(`Branch: ${branch}`, 10, y);
//                doc.text(`Report: ${reportTitle}`, 90, y);
//                doc.text(`Page No: ${pageNo}`, 160, y, { align: 'right' });
//
//                y += 8;
//                doc.text(`Report From: ${from} To: ${to}`, 10, y);
//
//                y += 10;
//                const summary = response.summary;
//                let srNo = 1;
//
//                Object.entries(summary).forEach(([providerName, details]) => {
//                    y += 10;
//                    doc.setFontSize(11);
//                    doc.text(`UserData:Provider - ${providerName}`, 10, y);
//                    y += 4;
//
//                    const bodyData = Object.entries(details.data).map(([type, count], index) => [
//                        index + 1,
//                        type,
//                        count
//                    ]);
//
//                    bodyData.push(["", "Total", details.total]);
//
//                    doc.autoTable({
//                        head: [["Sr.No", "Login Type", "Count"]],
//                        body: bodyData,
//                        startY: y,
//                        theme: 'grid',
//                        styles: { fontSize: 10 },
//                        headStyles: { fillColor: [41, 128, 185] },
//                        didDrawPage: function (data) {
//                            y = data.cursor.y + 5; // update Y position after table
//                        }
//                    });
//                });
//
//                doc.save(`User_Summary_Report_${from}_to_${to}.pdf`);
//            },

            success: function (response) {
                if (!response || !response.summary) {
                    alert("Invalid summary data");
                    return;
                }

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                const currentDate = new Date().toLocaleDateString();
                const username = response.username || 'Unknown';
                const fromDate = $('#userFromDate').val();
                const toDate = $('#userToDate').val();
                const reportTitle = "User Summary Report";

                let pageNo = 1;
                let y = 10;

                // Header
                doc.setFontSize(12);
                doc.text(`Username: ${username}`, 10, y);
                doc.text(`Date: ${currentDate}`, 160, y, { align: 'right' });

                y += 8;
                doc.text(`Report: ${reportTitle}`, 90, y);
                doc.text(`Page No: ${pageNo}`, 160, y, { align: 'right' });

                y += 8;
                doc.text(`Report From: ${fromDate} To: ${toDate}`, 10, y);

                y += 10;

                const summary = response.summary;
                let srNoGlobal = 1;  // global serial number for all rows

                Object.entries(summary).forEach(([providerName, details]) => {
                    y += 10;
                    doc.setFontSize(11);
                    doc.text(`Provider: ${providerName}`, 10, y);
                    y += 4;

                    // Build table rows: Sr.No, User Type (admin/employee), Login Count
                    const bodyData = Object.entries(details.data).map(([userType, count], index) => [
                        srNoGlobal++,
                        userType.charAt(0).toUpperCase() + userType.slice(1), // Capitalize first letter
                        count
                    ]);

                    // Add total row
                    bodyData.push(["", "Total", details.total]);

                    doc.autoTable({
                        head: [["Sr.No", "User Type", "Login Count"]],
                        body: bodyData,
                        startY: y,
                        theme: 'grid',
                        styles: { fontSize: 10 },
                        headStyles: { fillColor: [41, 128, 185] },
                        didDrawPage: function (data) {
                            y = data.cursor.y + 5; // update Y for next table
                        }
                    });
                });

                doc.save(`User_Summary_Report_${fromDate}_to_${toDate}.pdf`);
            },


        error: function(xhr, status, error) {
        console.error("Error loading report:", error);
         }
         
        });      
    //window.open(`user_summary_report.php?from=${from}&to=${to}`, '_blank');
});    
    

 </script>   
    
