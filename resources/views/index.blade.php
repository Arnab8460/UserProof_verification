<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<form id="filterForm">
    <select name="status" id="status">
        <option value="">All Status</option>
        <option value="Not Submitted">Not Submitted</option>
        <option value="Waiting for Approval">Waiting for Approval</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
    </select>
    <input type="text" id="email" name="email" placeholder="Search Email">
    <button type="button" id="searchBtn">Search</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <!-- <th>gg</th>
            <th>reg</th> -->
            <th>Created Time</th>
            <th>upload file</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="userTable">
        @foreach ($users as $user)
        <tr id="user-row-{{ $user->id }}">
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td class="status-{{ $user->id }}">{{ $user->status }}</td>
            <td>{{ $user->created_at }}</td>
            <td>
                <form id="uploadForm-{{ $user->id }}-id" enctype="multipart/form-data" 
                    style="{{ $user->id_proof_status === 'Rejected' ? 'display:block' : 'display:none' }}">
                    @csrf
                    <input type="hidden" name="proof_type" value="id">
                    <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                    <button type="button" onclick="uploadProof({{ $user->id }}, 'id')">Reupload ID Proof</button>
                </form>

                <form id="uploadForm-{{ $user->id }}-address" enctype="multipart/form-data" 
                    style="{{ $user->address_proof_status === 'Rejected' ? 'display:block' : 'display:none' }}">
                    @csrf
                    <input type="hidden" name="proof_type" value="address">
                    <input type="file" name="proof" accept=".jpg,.png,.pdf" required>
                    <button type="button" onclick="uploadProof({{ $user->id }}, 'address')">Reupload Address Proof</button>
                </form>
            </td>

            <td>
                <button class="approve-btn" data-id="{{ $user->id }}" data-type="id">Approve ID</button>
                <button class="approve-btn" data-id="{{ $user->id }}" data-type="address">Approve Address</button>
                <button class="reject-btn" data-id="{{ $user->id }}" data-type="id">Reject ID</button>
                <button class="reject-btn" data-id="{{ $user->id }}" data-type="address">Reject Address</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ready(function () {
    $(".approve-btn, .reject-btn").click(function() {
        let userId = $(this).data('id');
        let type = $(this).data('type'); 
        let action = $(this).hasClass('approve-btn') ? 'approve' : 'reject';

        $.ajax({
            url: `/${action}/${userId}/${type}`,
            type: "POST",
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                alert(response.success);
                location.reload(); 
                let statusText = action === 'approve' ? 'Approved' : 'Rejected';
                $(`.status-${userId}-${type}`).text(statusText);
            },
            error: function(xhr) {
                alert("Error: " + xhr.responseText);
            }
        });
    });

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
                    tableBody.append('<tr><td colspan="5">No users found.</td></tr>');
                } else {
                    users.forEach(user => {
                        tableBody.append(`
                            <tr id="user-row-${user.id}">
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td class="status-${user.id}">${user.status}</td>
                                <td>${user.created_at}</td>
                                <td>
                                    <button class="approve-btn" data-id="${user.id}" data-type="id">Approve ID</button>
                                    <button class="approve-btn" data-id="${user.id}" data-type="address">Approve Address</button>
                                    <button class="reject-btn" data-id="${user.id}" data-type="id">Reject ID</button>
                                    <button class="reject-btn" data-id="${user.id}" data-type="address">Reject Address</button>
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

function uploadProof(userId, proofType) {
    let formData = new FormData(document.getElementById(`uploadForm-${userId}-${proofType}`));

    $.ajax({
        url: `/reupload/${userId}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        success: function(response) {
            alert(response.success);
            location.reload(); 
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
}


</script>
</body>
</html>
