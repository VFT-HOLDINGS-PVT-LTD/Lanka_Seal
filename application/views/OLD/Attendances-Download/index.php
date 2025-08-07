<!DOCTYPE html>


<!--Description of dashboard page

@author VFT-Software-Team-->


<html lang="en">

    <title><?php echo $title ?></title>

    <head>


        <style>
        /* Add styles for loading animation */
        .loading {
            position: fixed;
            width: 78%;
            height: 78%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
        }
        .spinner {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Simple loading spinner */
        #loadingSpinner {
            display: none;
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
      <!-- Styles -->
      <?php $this->load->view('template/css.php');?>
      <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.addEventListener('contextmenu', (event) => {
                event.preventDefault();
            });
        });
    </script>
    <script>
        // table load function
        document.addEventListener("DOMContentLoaded", function() {
            const loadingElement = document.getElementById('loading');

            // Define mapping of DevSNs to Names
            const devSNToName = {
                "UEED241300013": "Device-1",
                "UEED242400544": "Device-2",
                "UEED242400516": "Device-3",
                "UEED242400506": "Device-4",
                "UEED242400507": "Device-5 (Meegoda)",
                "UEED242400509": "Device-6 (Badulla)"
            };

            // Authenticate and get the APIToken
            const loginData = {
                email: "admin@gmail.com",
                password: "Abc@1234"
            };

            fetch("https://biotime.cloud:8000/api/admin/", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(loginData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    const ApiToken = data.token;

                    // Fetch the device command data using the APIToken
                    fetch('https://biotime.cloud:8000/api/admin/devicecmd', {
                        method: 'GET',
                        headers: {
                            "ApiToken": `${ApiToken}`,
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);

                        const tbody = document.querySelector('#example tbody');
                        const allowedDevSNs = Object.keys(devSNToName); // Get allowed DevSNs from the mapping

                        // Extract the last 20 responses based on the 'id' field
                        const last20Responses = data.slice(-30);

                        last20Responses.forEach(item => {
                            if (allowedDevSNs.includes(item.DevSN)) {
                                const row = document.createElement('tr');
                                row.classList.add('odd', 'gradeX');

                                const status = item.ResponseTime === '01/01/1999, 14:14:14' ? '<span class="get_data label label-warning">Pending &nbsp;<i class="fa fa-eye"></i> </span>' : '<span class="get_data label label-success">Success &nbsp;<i class="fa fa-eye"></i> </span>';

                                row.innerHTML = `
                                    <td width='100'>${item.id}</td>
                                    <td width='100'>${item.DevSN}</td>
                                    <td width='100'>${devSNToName[item.DevSN]}</td> <!-- Display name here -->
                                    <td width='100'>${item.Type}</td>
                                    <td width='100'>${item.CommitTime}</td>
                                    <td width='100'>${status}</td>
                                `;

                                tbody.insertBefore(row, tbody.firstChild);
                            }
                        });

                        // Hide loading animation after data is processed
                        loadingElement.style.display = 'none';
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);

                        // Hide loading animation if there is an error
                        loadingElement.style.display = 'none';
                    });
                } else {
                    alert("APIToken not found in response.");
                }
            })
            .catch(error => {
                console.error("Error during authentication:", error);
                alert("An error occurred during authentication: " + error.message);

                // Hide loading animation if there is an error
                loadingElement.style.display = 'none';
            });
        });

        function base_url() {
            return "https://biotime.cloud:8000/";
        }

        function delete_id(id) {
            console.log('Delete item with ID:', id);
            // Implement your delete logic here
        }

    </script>
    </head>

    <body class="infobar-offcanvas">

        <!--header-->

        <?php $this->load->view('template/header.php');?>

        <!--end header-->

        <div id="wrapper">
            <div id="layout-static">

                <!--dashboard side-->

                <?php $this->load->view('template/dashboard_side.php');?>

                <!--dashboard side end-->

                <div class="static-content-wrapper">
                    <div class="static-content">
                        <div class="page-content">
                            <ol class="breadcrumb">

                                <li class=""><a href="index.html">HOME</a></li>
                                <li class="active"><a href="index.html">ATTENDANCES DOWNLOAD</a></li>

                            </ol>


                            <div class="page-tabs">
                                <ul class="nav nav-tabs">

                                    <li class="active"><a data-toggle="tab" href="#tab1">ATTENDANCES DOWNLOAD</a></li>
                                    <li><a data-toggle="tab" href="#tab2">ATTENDANCES DOWNLOAD DETAILS</a></li>


                                </ul>
                            </div>
                            <div class="container-fluid">


                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab1">

                                        <div class="row">
                                            <div class="col-xs-12">


                                                     <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-primary">
                                                            <div class="panel-heading">
                                                                <h2>ATTENDANCES DOWNLOAD</h2>
                                                            </div>
                                                            <div class="panel-body">
                                                                <!-- <form class="form-horizontal" id="frm_comp_pro" name="frm_comp_pro" action="<?php echo base_url(); ?>Company_Profile/Company_Profile/insert_Data" method="POST" enctype="multipart/form-data"> -->
                                                                    <div id="successMessage" class="alert alert-success" style="display: none;"></div>

                                                                    <div class="form-group col-sm-12">
                                                                        <div class="col-sm-6">
                                                                            <img class="imagecss" src="https://cdni.iconscout.com/illustration/premium/thumb/online-attendance-7107957-5791782.png?f=webp" >
                                                                        </div>
                                                                        <div class="col-sm-2">
                                                                            <div id="loadingSpinner"style="margin-top:13px"></div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group col-sm-12">
                                                                        <label for="focusedinput" class="col-sm-2 control-label">Device Name</label>
                                                                        <div class="col-sm-8">
                                                                        <select name="" id="deviceSelect" class="form-control">
                                                                            <option value="">Select</option>
                                                                        </select>
                                                                    </div>
                                                                    </div>

                                                                    <div class="form-group col-sm-6">
                                                                        <label for="fromDate" class="col-sm-4 control-label">Select From Date</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="date" class="form-control" required="" id="fromDate">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-sm-6">
                                                                        <label for="toDate" class="col-sm-3 control-label">Select To Date</label>
                                                                        <div class="col-sm-5">
                                                                            <input type="date" class="form-control" id="toDate">
                                                                        </div>
                                                                    </div>



                                                                    <div class="row">
                                                                        <div class="col-sm-8 col-sm-offset-2">
                                                                        <button type="submit" id="submit" class="btn btn-primary btn fa fa-check">
                                                                            &nbsp;&nbsp;SUBMIT
                                                                        </button>
                                                                        <button type="button" id="Cancel" name="Cancel" class="btn btn-danger-alt fa fa-times-circle">
                                                                            &nbsp;&nbsp;CANCEL
                                                                        </button>
                                                                        </div>
                                                                    </div>

                                                                <!-- </form> -->
                                                                <hr>

                                                                <div id="divmessage" class="">

                                                                    <div id="spnmessage"> </div>
                                                                </div>


                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>


                                            </div>
                                        </div>


                                      <!--***************************-->
                                    </div>
                                    <div class="tab-pane" id="tab2">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="panel panel-primary">
                                                    <div class="col-md-12">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading">
                                                                <h2>ATTENDANCES DOWNLOAD DETAILS</h2>
                                                                <a href="<?php echo base_url(); ?>Attendances-Download/Attendances_Download" class="btn btn-primary" style="margin-left: 40px;">Refresh</a>
                                                                <!-- <h1>API Data Fetch</h1>
                                                                <pre id="output">Fetching data...</pre>
                                                                <div class="panel-ctrls">
                                                                </div> -->
                                                            </div>
                                                            <div class="panel-body panel-no-padding">
                                                                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>DevSN</th>
                                                                            <th>Device Name</th>
                                                                            <th>Type</th>
                                                                            <th>CommitTime</th>
                                                                            <th>Status</th>

                                                                        </tr>
                                                                    </thead>
                                                                    <center>
                                                                        <div class="loading" id="loading">
                                                                            <div class="spinner"></div>
                                                                        </div>
                                                                    </center>
                                                                    <tbody>
                                                                        <tr></tr>
                                                                        <!-- Data will be inserted here -->
                                                                    </tbody>
                                                                </table>
                                                                <div class="panel-footer"></div>

                                                                <!-- <div class="panel-footer"></div> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                      <!--***************************-->
                                    </div>

                                </div>

                            </div> <!-- .container-fluid -->
                        </div>
                        <!--Footer-->
                        <?php $this->load->view('template/footer.php');?>
                        <!--End Footer-->
                    </div>
                </div>
            </div>


           <script>
            // window.onload = function() {
            //     // Data to send in the POST request for authentication(load the date for select tag)
            //     const loginData = {
            //         email: "admin@gmail.com",
            //         password: "Abc@1234"
            //     };

            //     // Map DevSN to names
            //     const devSNToName = {
            //         "CQQC233162192": "Colombo",
            //         "GED7240200099": "Huluganga",
            //         "GED7240200084": "HulugangaDown",
            //         "GED7240200064": "GiddawaMini",
            //         "GED7240200046": "Thalawakale",
            //         "GED7240200039": "Siyambalanduwa",
            //         "GED7240200080": "RanwalaOya",
            //         "GED7240200096": "GomaleOya",
            //         "GED7240200075": "MagalGanga",
            //         "GED7240200102": "Karapalagama",
            //         "GED7240200066": "Mahiyanganaya"
            //     };

            //     // Authenticate and get the APIToken
            //     fetch("https://biotime.cloud:8000/api/admin/", {
            //         method: "POST",
            //         headers: {
            //             "Content-Type": "application/json"
            //         },
            //         body: JSON.stringify(loginData)
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.token) {
            //             const ApiToken = data.token;

            //             // Fetch the device data using the APIToken
            //             fetch("https://biotime.cloud:8000/api/admin/device/4", {
            //                 method: "GET",
            //                 headers: {
            //                     "ApiToken": `${ApiToken}`,
            //                     "Content-Type": "application/json"
            //                 }
            //             })
            //             .then(response => response.json())
            //             .then(deviceData => {
            //                 const deviceSelect = document.getElementById("deviceSelect");

            //                 // Populate the <select> element with the DevSN data and names
            //                 deviceData.forEach(device => {
            //                     const devSN = device.DevSN;
            //                     const name = devSNToName[devSN] || devSN;

            //                     const option = document.createElement("option");
            //                     option.value = devSN;
            //                     option.textContent = name;
            //                     deviceSelect.appendChild(option);
            //                 });
            //             })
            //             .catch(error => {
            //                 console.error("Error fetching device data:", error);
            //                 alert("An error occurred while fetching device data: " + error.message);
            //             });

            //             // Event listener for the submit button(submite button)
            //             const submitButton = document.getElementById("submit");
            //             submitButton.addEventListener("click", function(event) {
            //                 event.preventDefault(); // Prevent default form submission

            //                 const deviceSelect = document.getElementById("deviceSelect");
            //                 const fromDate = document.getElementById("fromDate").value;
            //                 const toDate = document.getElementById("toDate").value;

            //                 if (!deviceSelect.value || !fromDate || !toDate) {
            //                     alert("Please select a device and both dates.");
            //                     return;
            //                 }


            //                 var fromDateTime = fromDate + " 00:00:00";
            //                 var toDateTime = toDate + " 23:59:59";
            //                 // Prepare the data for the POST request
            //                 var requestData = {
            //                     DevSN: deviceSelect.value,
            //                     Type: "General",
            //                     Content: `DATA QUERY ATTLOG StartTime=${fromDateTime} EndTime=${toDateTime}`
            //                 };

            //                 // Send the data to the API
            //                 fetch("https://biotime.cloud:8000/api/admin/devicecmd", {
            //                     method: "POST",
            //                     headers: {
            //                         "ApiToken": `${ApiToken}`,
            //                         "Content-Type": "application/json"
            //                     },
            //                     body: JSON.stringify(requestData)
            //                 })
            //                 .then(response => response.json())
            //                 .then(responseData => {
            //                     alert("Data sent successfully!");
            //                     console.log(responseData);
            //                 })
            //                 .catch(error => {
            //                     console.error("Error sending data:", error);
            //                     alert("An error occurred while sending data: " + error.message);
            //                 });
            //             });
            //         } else {
            //             alert("APIToken not found in response.");
            //         }
            //     })
            //     .catch(error => {
            //         console.error("Error during authentication:", error);
            //         alert("An error occurred during authentication: " + error.message);
            //     });
            // };

            window.onload = function() {
                // Data to send in the POST request for authentication
                const loginData = {
                    email: "admin@gmail.com",
                    password: "Abc@1234"
                };

                // Map DevSN to names
                const devSNToName = {
                    "UEED241300013": "Device-1",
                    "UEED242400544": "Device-2",
                    "UEED242400516": "Device-3",
                    "UEED242400506": "Device-4",
                    "UEED242400507": "Device-5 (Meegoda)",
                    "UEED242400509": "Device-6 (Badulla)"
                };

                // Authenticate and get the APIToken
                fetch("https://biotime.cloud:8000/api/admin/", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(loginData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        const ApiToken = data.token;

                        // Fetch the device data using the APIToken
                        fetch("https://biotime.cloud:8000/api/admin/device/16", {
                            method: "GET",
                            headers: {
                                "ApiToken": `${ApiToken}`,
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(deviceData => {
                            const deviceSelect = document.getElementById("deviceSelect");

                            // Populate the <select> element with the DevSN data and names
                            deviceData.forEach(device => {
                                const devSN = device.DevSN;
                                const name = devSNToName[devSN] || devSN;

                                const option = document.createElement("option");
                                option.value = devSN;
                                option.textContent = name;
                                deviceSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error("Error fetching device data:", error);
                            alert("An error occurred while fetching device data: " + error.message);
                        });

                        // Event listener for the submit button
                        const submitButton = document.getElementById("submit");
                        const loadingSpinner = document.getElementById("loadingSpinner");

                        submitButton.addEventListener("click", function(event) {
                            event.preventDefault(); // Prevent default form submission

                            const deviceSelect = document.getElementById("deviceSelect");
                            const fromDate = document.getElementById("fromDate").value;
                            const toDate = document.getElementById("toDate").value;

                            if (!deviceSelect.value || !fromDate || !toDate) {
                                alert("Please select a device and both dates.");
                                return;
                            }

                            var fromDateTime = fromDate + " 00:00:00";
                            var toDateTime = toDate + " 23:59:59";

                            // Prepare the data for the POST request
                            var requestData = {
                                DevSN: deviceSelect.value,
                                Type: "General",
                                Content: `DATA QUERY ATTLOG StartTime=${fromDateTime} EndTime=${toDateTime}`
                            };

                            // Show the loading spinner
                            loadingSpinner.style.display = "block";

                            // Send the data to the API
                            fetch("https://biotime.cloud:8000/api/admin/devicecmd", {
                                method: "POST",
                                headers: {
                                    "ApiToken": `${ApiToken}`,
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify(requestData)
                            })
                            .then(response => response.json())
                            .then(responseData => {
                                alert("Data sent successfully!");
                                console.log(responseData);
                            })
                            .catch(error => {
                                console.error("Error sending data:", error);
                                alert("An error occurred while sending data: " + error.message);
                            })
                            .finally(() => {
                                // Hide the loading spinner after the request is complete
                                loadingSpinner.style.display = "none";
                            });
                        });
                    } else {
                        alert("APIToken not found in response.");
                    }
                })
                .catch(error => {
                    console.error("Error during authentication:", error);
                    alert("An error occurred during authentication: " + error.message);
                });
            };
           </script>

            <!-- Load site level scripts -->

            <?php $this->load->view('template/js.php');?>

            <!-- End loading page level scripts-->

    </body>


</html>