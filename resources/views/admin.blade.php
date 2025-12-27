<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Welcome, Admin</h1>

    <hr>
    <h3>Pending Users</h3>
    <table class="table table-bordered" id="pending-users-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Phone</th>
                <th>Role</th>
                <th>ID Image</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
          
        </tbody>
    </table>
</div>

<hr>
<h3>
    <style>
    .table img { width: 50px; height: auto; border-radius: 5px; }
    .badge { padding: 8px 12px; }
    h3 { margin-top: 30px; color: #333; border-left: 5px solid #0d6efd; padding-left: 10px; }
</style>
    All Users Management</h3>
<table class="table table-striped" id="all-users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>






<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
function fetchPendingUsers() {
    $.get("/admin/pending-users", function(data) {
        let tbody = $("#pending-users-table tbody");
        tbody.empty();
        data.pending_users.forEach(user => {
            tbody.append(`
                <tr>
                    <td>${user.id}</td>
                    <td>${user.phone}</td>
                    <td>${user.role}</td>
                    <td><a href="/id_images/${user.id_image}" target="_blank">View</a></td>
                    <td>${user.created_at}</td>
                    <td>
                        <button class="btn btn-success btn-approve" data-id="${user.id}">Approve</button>
                        <button class="btn btn-danger btn-reject" data-id="${user.id}">Reject</button>
                    </td>
                </tr>
            `);
        });
    });
}

$(document).ready(function(){
    fetchPendingUsers();

    $(document).on('click', '.btn-approve', function(){
        let id = $(this).data('id');
        $.post(`/admin/approve/${id}`, {_token: '{{ csrf_token() }}'}, function(res){
            alert(res.message);
            fetchPendingUsers();
        });
    });

    $(document).on('click', '.btn-reject', function(){
        let id = $(this).data('id');
        $.post(`/admin/reject/${id}`, {_token: '{{ csrf_token() }}'}, function(res){
            alert(res.message);
            fetchPendingUsers();
        });
    });
});
// Function to delete user
$(document).on('click', '.btn-delete', function() {
    let id = $(this).data('id');
    
    if (confirm('Are you sure you want to delete this user permanently?')) {
        $.ajax({
            url: `/admin/delete-user/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                alert(response.message);
                fetchPendingUsers(); // Refresh the list
                fetchAllUsers();    // Refresh the general list
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    }
});

function fetchAllUsers() {
    $.get("/admin/all-users", function(data) {
        let tbody = $("#all-users-table tbody");
        tbody.empty();
        data.users.forEach(user => {
            tbody.append(`
                <tr>
                    <td>${user.id}</td>
                    <td>${user.phone}</td>
                    <td>${user.role}</td>
                    <td><span class="badge bg-info">${user.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${user.id}">Delete</button>
                    </td>
                </tr>
            `);
        });
    });
}


$(document).ready(function(){
    fetchPendingUsers();
    fetchAllUsers(); 
});
</script>
</body>
</html>
