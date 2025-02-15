<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .filterContainer {
            padding: 10px;
            border-radius: 10px;
            min-height: 30px;
            margin-bottom: 10px;
            display: flex;
            gap: 10px;
            background-color: #f2e7ff;

            .select-drop {
                height: 40px;
                padding: 10px;
                border-radius: 7px;
                outline: none;
                background-color: #ffffff;
            }

            .search-input {
                border: none;
                padding: 0px 15px;
                border-radius: 7px;
                width: 200px;
                outline: none;
                background-color: #ffffff;

                transition: all 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            }

            .search-input.expand {
                width: 400px;
            }

            .search-btn {
                width: 150px;
                border-radius: 7px;
                border: none;
                font-size: 14px;
                background-color: rgb(176, 101, 238);
                color: #ffffff;
            }
        }

        .table-wrapper {
            overflow: hidden;
            border-radius: 10px;
        }

        .table-wrapper .my-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.my-table thead {
            background-color: rgb(169 118 225);
            color: #ffffff;
        }

        table.my-table td {
            border: 1px solid #ffffff;
            padding: 10px;
            text-align: left;
        }

        table.my-table th {
            padding: 15px;
            text-transform: capitalize;
        }

        table.my-table th:not(:first-child):not(:last-child) {
            border-right: 1px solid #ffffff;
        }

        table.my-table th:first-child {
            border-left: none;
            border-right: 1px solid #ffffff;
        }

        table.my-table th:last-child {
            border-right: none;
            border-left: 1px solid #ffffff;
        }

        table.my-table tr:nth-child(even) {
            background: #ededed;
        }

        .upload-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin: 10px 0px;
        }

        .action-btn {
            height: 49px;
            border: 0px;
            background-color: #a377d6;
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 7px;
            padding: 5px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background-color: #8539db;
        }

        .action-btn.approve {
            background-color: #77d6aa;
        }

        .action-btn.approve:hover {
            background-color: #29c97e;
        }

        .action-btn.reject {
            background-color: #d6777c;
        }

        .action-btn.reject:hover {
            background-color: #d44c53;
        }

        .action-container {
            display: flex;
            justify-content: space-around;
        }
        #loadMoreBtn {
            background-color: #4CAF50; /* Green */
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

    #loadMoreBtn:hover {
        background-color: #45a049;
        transform: scale(1.05);
    }

    #loadMoreBtn:active {
        background-color: #3e8e41;
        transform: scale(0.95);
    }

    </style>
</head>

<body>
    <form id="filterForm">
        <div class="filterContainer">
            <select name="status" class="select-drop" id="status">
                <option value="">All Status</option>
                <option value="Not Submitted">Not Submitted</option>
                <option value="Waiting for Approval">Waiting for Approval</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
            <input class="search-input" type="text" id="email" name="email" placeholder="Search Email">
            <button class="search-btn" type="button" id="searchBtn">Search</button>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="my-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Created Time</th>
                    <th>upload file</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="userTable">
                @foreach ($users as $user)
                    <tr id="user-row-{{ $user['id'] }}">
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td class="status-{{ $user['id'] }}">{{ $user['status'] }}</td>
                        <td>{{ $user['created_at'] }}</td>
                        <td>
                            <form id="uploadForm-{{ $user['id'] }}-id" enctype="multipart/form-data"
                                style="{{ $user['id_proof_status'] === 'Rejected' ? 'display:block' : 'display:none' }}">
                                <div class="upload-container">
                                    @csrf
                                    <input type="hidden" name="proof_type" value="id">
                                    <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                                    <button type="button" class="action-btn"
                                        onclick="uploadProof({{ $user['id'] }}, 'id')">Reupload ID Proof</button>
                                </div>
                            </form>

                            <form id="uploadForm-{{ $user['id'] }}-address" enctype="multipart/form-data"
                                style="{{ $user['address_proof_status'] === 'Rejected' ? 'display:block' : 'display:none' }}">
                                @csrf
                                <div class="upload-container">
                                    <input type="hidden" name="proof_type" value="address">
                                    <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                                    <button type="button" class="action-btn"
                                        onclick="uploadProof({{ $user['id'] }}, 'address')">Reupload Address Proof</button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="action-container">
                                <button class="action-btn approve" data-id="{{ $user['id'] }}" data-type="id">Approve
                                    ID</button>
                                <button class="action-btn approve" data-id="{{ $user['id'] }}" data-type="address">Approve
                                    Address</button>
                                <button class="action-btn reject" data-id="{{ $user['id'] }}" data-type="id">Reject
                                    ID</button>
                                <button class="action-btn reject" data-id="{{ $user['id'] }}" data-type="address">Reject
                                    Address</button>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <button id="loadMoreBtn">Load More</button>
    </div>

    <script>
        let offset = 5;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $('.search-input').on('focus', function (e) {
                $(this).addClass('expand')
            });
            $('.search-input').on('blur', function (e) {
                $(this).removeClass('expand')
            });

            $(".approve, .reject").click(function () {
                let userId = $(this).data('id');
                let type = $(this).data('type');
                let action = $(this).hasClass('approve') ? 'approve' : 'reject';
                // Function to update proof status via AJAX
                $.ajax({
                    url: `/${action}/${userId}/${type}`,
                    type: "POST",
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        alert(response.success);
                        location.reload();
                        let statusText = action === 'approve' ? 'Approved' : 'Rejected';
                        $(`.status-${userId}-${type}`).text(statusText); // Update status text in the table
                    },
                    error: function (xhr) {
                        alert("Error: " + xhr.responseText);
                    }
                });
            });
            //function to filter status and email
            function fetchFilteredUsers() {
                let status = $('#status').val();
                let email = $('#email').val();

                $.ajax({
                    url: "/filter-users",
                    type: "post",
                    data: { status: status, email: email },
                    success: function (response) {
                        let users = response.users;
                        let tableBody = $('#userTable');
                        tableBody.empty();

                        if (users.length === 0) {
                            tableBody.append('<tr><td colspan="6">No users found.</td></tr>');
                        } else {
                            users.forEach(user => {
                                tableBody.append(`
                        <tr id="user-row-${user.id}">
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td class="status-${user.id}">${user.status}</td>
                            <td>${user.created_at}</td>
                            <td>
                                <form id="uploadForm-${user.id}-id" enctype="multipart/form-data"
                                    style="${user.id_proof_status === 'Rejected' ? 'display:block' : 'display:none'}">
                                    <div class="upload-container">
                                        <input type="hidden" name="proof_type" value="id">
                                        <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                                        <button type="button" class="action-btn"
                                            onclick="uploadProof(${user.id}, 'id')">Reupload ID Proof</button>
                                    </div>
                                </form>

                                <form id="uploadForm-${user.id}-address" enctype="multipart/form-data"
                                    style="${user.address_proof_status === 'Rejected' ? 'display:block' : 'display:none'}">
                                    <div class="upload-container">
                                        <input type="hidden" name="proof_type" value="address">
                                        <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                                        <button type="button" class="action-btn"
                                            onclick="uploadProof(${user.id}, 'address')">Reupload Address Proof</button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="action-container">
                                    <button class="action-btn approve" data-id="${user.id}" data-type="id">Approve ID</button>
                                    <button class="action-btn approve" data-id="${user.id}" data-type="address">Approve Address</button>
                                    <button class="action-btn reject" data-id="${user.id}" data-type="id">Reject ID</button>
                                    <button class="action-btn reject" data-id="${user.id}" data-type="address">Reject Address</button>
                                </div>
                            </td>
                        </tr>
                    `);
                            });
                        }
                    }
                });
            }

            $('#status').change(fetchFilteredUsers);
            $('#searchBtn').click(fetchFilteredUsers);
        });
 // Function to handle proof reupload via AJAX
        function uploadProof(userId, proofType) {
            let formData = new FormData(document.getElementById(`uploadForm-${userId}-${proofType}`));

            $.ajax({
                url: `/reupload/${userId}`, // Laravel route to handle reupload
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                success: function (response) {
                    alert(response.success);
                    location.reload();
                },
                error: function (xhr) {
                    alert("Error: " + xhr.responseText);
                }
            });
        }

    //new load more button function
    $("#loadMoreBtn").click(function () {
        $.ajax({
            url: "/load-more-users",
            type: "GET",
            data: { offset: offset },
            success: function (response) {
                if (response.users.length > 0) {
                    response.users.forEach(user => {
                        $("#userTable").append(`
                            <tr id="user-row-${user.id}">
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td class="status-${user.id}">${user.status}</td>
                            <td>${user.created_at}</td>
                            <td>
                                <form id="uploadForm-${user.id}-id" enctype="multipart/form-data"
                                    style="${user.id_proof_status === 'Rejected' ? 'display:block' : 'display:none'}">
                                    <div class="upload-container">
                                        <input type="hidden" name="proof_type" value="id">
                                        <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                                        <button type="button" class="action-btn"
                                            onclick="uploadProof(${user.id}, 'id')">Reupload ID Proof</button>
                                    </div>
                                </form>

                                <form id="uploadForm-${user.id}-address" enctype="multipart/form-data"
                                    style="${user.address_proof_status === 'Rejected' ? 'display:block' : 'display:none'}">
                                    <div class="upload-container">
                                        <input type="hidden" name="proof_type" value="address">
                                        <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                                        <button type="button" class="action-btn"
                                            onclick="uploadProof(${user.id}, 'address')">Reupload Address Proof</button>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="action-container">
                                    <button class="action-btn approve" data-id="${user.id}" data-type="id">Approve ID</button>
                                    <button class="action-btn approve" data-id="${user.id}" data-type="address">Approve Address</button>
                                    <button class="action-btn reject" data-id="${user.id}" data-type="id">Reject ID</button>
                                    <button class="action-btn reject" data-id="${user.id}" data-type="address">Reject Address</button>
                                </div>
                            </td>
                        </tr>
                        `);
                    });
                    offset += 5;
                } else {
                    $("#loadMoreBtn").hide();
                }
            },
            error: function () {
                alert("Error loading more users!");
            }
        });
    });
    </script>
</body>

</html>