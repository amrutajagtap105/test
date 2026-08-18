<?php include('../header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP Checking 1</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
<!--     Bootstrap CSS 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
     jQuery 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
     Bootstrap JS 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
     DataTables 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>-->

    <style>
        body {
            background-color: #f8f9fa;
        }
        h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
            color: #343a40;
        }
        #phpresult {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            margin-bottom: 15px;
        }
        label {
            font-weight: 500;
        }
        .spinner-border {
            display: none;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Dynamic PHP </h1>

    <div class="mb-4">
        <label for="phpfile" class="form-label">Select PHP File</label>
        <select id="phpfile" class="form-select form-select-lg">
            <!-- Options populated dynamically -->
        </select>
    </div>

    <div id="parametersdiv" class="mb-4">
        <!-- Parameter inputs appear here -->
    </div>

    <div class="mb-4 d-flex align-items-center">
        <button id="submitToPHP" class="btn btn-primary">Submit</button>
        <div class="spinner-border text-primary" id="loadingSpinner" role="status"></div>
    </div>

    <div id="phpresult" class="mb-5">
        <!-- Results will appear here -->
    </div>
</div>

<script>
    let filelist = [];

    $(document).ready(function() {
        $.ajax({
            url: "getphp.php",
            method: "GET",
            dataType: "json",
            success: function(response) {
                filelist = response;
                $("#phpfile").append(`<option value="">Select a PHP file...</option>`);
                response.forEach(data => {
                    $("#phpfile").append(`<option value="${data.id}">${data.name}</option>`);
                });
            }
        });
    });

    $("#phpfile").on("change", function() {
        const phpfileid = $(this).val();
        $('#parametersdiv').empty();
        if (!phpfileid) return;

        $.ajax({
            url: "getlistofparameter.php",
            method: "POST",
            data: { phpfileid },
            dataType: "json",
            success: function(filelist) {
                filelist.forEach(parameter => {
                    const label = $("<label>").text(parameter.name + " :").addClass('form-label');
                    let input;

                    if (parameter.textarea == 1) {
                        input = $('<textarea>').attr({ name: parameter.name, id: parameter.name }).addClass('form-control');
                    } else {
                        input = $('<input>').attr({ type: 'text', name: parameter.name, id: parameter.name }).addClass('form-control');
                    }

                    $('#parametersdiv').append(label).append(input);
                });
            }
        });
    });

    $("#submitToPHP").on("click", function () {
        const phpfileid = $("#phpfile").val();
        if (!phpfileid) {
            alert("Please select a PHP file first.");
            return;
        }

        const phpfile = filelist.find(file => file.id == phpfileid);
        if (!phpfile) {
            alert("Selected PHP file not found.");
            return;
        }

        const postData = {};
        $("#parametersdiv input, #parametersdiv textarea").each(function () {
            const key = $(this).attr("name");
            const value = $(this).val();
            postData[key] = value;
        });

        $("#loadingSpinner").show();

        $.ajax({
            url: phpfile.name,
            method: "POST",
            dataType: "json",
            data: postData,
            success: function(response) {
                let html = "";

                if (Array.isArray(response) && response.length > 0) {
                    let keys = Object.keys(response[0]);
                    html = '<div class="table-responsive"><table id="docsTable" class="table table-bordered table-striped" style="width:100%"><thead><tr>';


                    keys.forEach(key => {
                        html += `<th>${key}</th>`;
                    });

                    html += '</tr></thead><tbody>';

                    response.forEach(item => {
                        html += '<tr>';
                        keys.forEach(key => {
                            html += `<td>${item[key] || ''}</td>`;
                        });
                        html += '</tr>';
                    });

                    html += '</tbody></table></div>';

                } else if (Array.isArray(response) && response.length === 0) {
                    html = "<i>No documents found.</i>";
                } else if (response.error) {
                    html = `<b>Error:</b> ${response.error}`;
                } else {
                    html = "<pre>" + JSON.stringify(response, null, 2) + "</pre>";
                }

                $("#phpresult").html(html);

                if ($("#docsTable").length) {
                    $('#docsTable').DataTable();
                }
            },
            complete: function() {
                $("#loadingSpinner").hide();
            }
        });
    });
</script>

</body>
</html>
