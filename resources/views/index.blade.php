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

<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Created Time</th>
        <th>Actions</th>
    </tr>
    @foreach ($users as $user)
    <tr id="user-row-{{ $user->id }}">
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td class="status-{{ $user->id }}">{{ $user->status }}</td>
        <td>{{ $user->created_at }}</td>
        <!-- <td>
            <button class="approve-btn" data-id="{{ $user->id }}">Approve</button>
            <button class="reject-btn" data-id="{{ $user->id }}">Reject</button>
        </td> -->
        <td>
    <button class="approve-btn" data-id="{{ $user->id }}" data-type="id">Approve ID</button>
    <button class="approve-btn" data-id="{{ $user->id }}" data-type="address">Approve Address</button>
    <button class="reject-btn" data-id="{{ $user->id }}" data-type="id">Reject ID</button>
    <button class="reject-btn" data-id="{{ $user->id }}" data-type="address">Reject Address</button>
</td>

    </tr>
    @endforeach
</table>

<script>
    // $(document).ready(function() {
    //     $(".approve-btn").click(function() {
    //         let userId = $(this).data('id');
    //         updateStatus(userId, 'approve');
    //     });
    //     $(".reject-btn").click(function() {
    //         let userId = $(this).data('id');
    //         updateStatus(userId, 'reject');
    //     });

    //     function updateStatus(userId, action) {
    //         $.ajax({
    //             url: `/${action}/${userId}`,
    //             type: "POST",
    //             data: { _token: $('meta[name="csrf-token"]').attr('content') },
    //             success: function(response) {
    //                 alert(response.success);
    //                 $(".status-" + userId).text(action === 'approve' ? 'approved' : 'rejected');
    //             },
    //             error: function(xhr) {
    //                 alert("Error: " + xhr.responseText);
    //             }
    //         });
    //     }
    // });

    $(".approve-btn, .reject-btn").click(function() {
    let userId = $(this).data('id');
    let type = $(this).data('type'); 
    let action = $(this).hasClass('approve-btn') ? 'approve' : 'reject';

    $.ajax({
        url: `/${action}/${userId}/${type}`,
        type: "POST",
        data: { _token: $('meta[name="csrf-token"]').attr('content') },
        success: function(response) {
            alert(response.success)
            let statusText = action === 'approve' ? 'Approved' : 'Rejected';
            $(`.status-${userId}`).text(statusText);
        },
        error: function(xhr) {
            alert("Error: " + xhr.responseText);
        }
    });
});


</script>

</body>
</html>
