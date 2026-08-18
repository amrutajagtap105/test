<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Management System</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />

    <!-- jQuery & DataTables -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>



  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Custom Styling -->
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fa;
      padding-top: 70px;
    }

    .navbar-custom {
      background: linear-gradient(90deg, #007bff, #00c6ff);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 1.5rem;
      color: #ffffff !important;
    }

    .navbar-nav .nav-link {
      color: #ffffff !important;
      font-weight: 500;
    }

    .navbar-nav .nav-link:hover {
      color: #e0f7ff !important;
    }

    .dropdown-toggle {
      font-weight: 500;
    }

    .btn-light.dropdown-toggle {
      background-color: #ffffff;
      color: #333;
      border: 1px solid #ccc;
    }

    .dropdown-menu {
      min-width: 180px;
    }

    .dropdown-item:hover {
      background-color: #f0f0f0;
    }

    .dropdown-item.text-danger:hover {
      background-color: #ffe5e5;
    }

    .active-page {
      border-bottom: 2px solid #fff;
    }
  </style>
</head>
<body>

<!-- Begin Header -->
<div id="mainHeaderNav" style="display: none;">

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="/Final_Exercise/index.php">
      <i class="bi bi-people-fill me-2"></i> Employee Management
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active-page' : ''; ?>" href="/Final_Exercise/index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'phpcheck.php' ? 'active-page' : ''; ?>" href="/Final_Exercise/web/phpcheck.php">Dynamic PHP</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'jsPDF.php' ? 'active-page' : ''; ?>" href="/Final_Exercise/jsPDF/jsPDF.php">Reports</a>
        </li>
      </ul>

      <!-- 🔽 User Dropdown Section: Still dynamic -->
      <div class="d-flex align-items-center" id="loggedInUserSection" style="display: none;" >
        <div class="dropdown">
          <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-1"></i> <span id="loggedUsername">User</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item change-password-btn" href="#" data-bs-toggle="modal" data-bs-target="#ChangepassModel">Change Password</a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item text-danger" href="#" onclick="logout()">Logout</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- 🔼 End User Dropdown -->
    </div>
  </div>
</nav>
    
</div>    
<!-- End Header -->

<script>
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
        //$('#loggedInUserSection').show();
        $('#loggedUsername').text(username || "User");
        $('.dropdown-item.change-password-btn').attr('id', userid);
          
    } else {
       // $('#loginModal').modal('show');
        $('#employeeSection').hide();
        $('#loginModalshow-btn').show();
        $('#loggedInUserSection').hide();
          //location.reload();  // forces re-check
          location.replace('/Final_Exercise/index.php');

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
    location.replace('../index.php');
}    
</script>