<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: 'Poppins', sans-serif;
            color: #2d403b;
        }

        /* Hero section inspired by the image background */
        .hero-section {
            background-color: #0b6c5f; /* Teal green from image */
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            color: white;
            padding: 50px 0;
            border-bottom: 8px solid #c62828; /* Crimson red from fez/sash */
            margin-bottom: 40px;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 2px solid #0b6c5f;
            padding: 15px 20px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #0b6c5f;
            border-left: 5px solid #c62828;
            padding-left: 15px;
        }

        /* Table Styling */
        .table thead {
            background-color: #f8faf9;
            color: #0b6c5f;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-responsive {
            margin: 0;
        }

        /* Buttons & Badges */
        .btn-success {
            background-color: #0b6c5f;
            border-color: #0b6c5f;
        }
        .btn-success:hover {
            background-color: #085248;
        }

        .btn-danger {
            background-color: #c62828;
            border-color: #c62828;
        }

        .badge-status {
            background-color: #1eb2a6 !important;
            color: white;
            font-weight: 400;
        }

        .id-link {
            color: #0b6c5f;
            text-decoration: none;
            font-weight: 600;
        }
        .id-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="hero-section">
    <div class="container">
        <h1 class="fw-bold">Welcome, Admin</h1>
        <p class="opacity-75">Manage your system users and pending requests efficiently.</p>
    </div>
</div>

<div class="container">
    
    <div class="card">
        <div class="card-header">
            <h3>Pending Requests</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pending-users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>ID Document</th>
                            <th>Requested At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>All Users Management</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0" id="all-users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
// Logic remains the same, only the rendered HTML strings are in English

function fetchPendingUsers() {
    $.get("/admin/pending-users", function(data) {
        let tbody = $("#pending-users-table tbody");
        tbody.empty();
        data.pending_users.forEach(user => {
            tbody.append(`
                <tr>
                    <td>#${user.id}</td>
                    <td>${user.phone}</td>
                    <td><span class="badge bg-light text-dark border">${user.role}</span></td>
                    <td><a href="/id_images/${user.id_image}" target="_blank" class="id-link">View Image</a></td>
                    <td>${user.created_at}</td>
                    <td>
                        <button class="btn btn-sm btn-success btn-approve me-1" data-id="${user.id}">Approve</button>
                        <button class="btn btn-sm btn-danger btn-reject" data-id="${user.id}">Reject</button>
                    </td>
                </tr>
            `);
        });
    });
}

function fetchAllUsers() {
    $.get("/admin/all-users", function(data) {
        let tbody = $("#all-users-table tbody");
        tbody.empty();
        data.users.forEach(user => {
            tbody.append(`
                <tr>
                    <td>#${user.id}</td>
                    <td>${user.phone}</td>
                    <td>${user.role}</td>
                    <td><span class="badge badge-status">${user.status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${user.id}">Delete User</button>
                    </td>
                </tr>
            `);
        });
    });
}

$(document).ready(function(){
    fetchPendingUsers();
    fetchAllUsers();

    $(document).on('click', '.btn-approve', function(){
        let id = $(this).data('id');
        $.post(`/admin/approve/${id}`, {_token: '{{ csrf_token() }}'}, function(res){
            alert(res.message);
            fetchPendingUsers();
            fetchAllUsers();
        });
    });

    $(document).on('click', '.btn-reject', function(){
        let id = $(this).data('id');
        $.post(`/admin/reject/${id}`, {_token: '{{ csrf_token() }}'}, function(res){
            alert(res.message);
            fetchPendingUsers();
            fetchAllUsers();
        });
    });

    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this user permanently?')) {
            $.ajax({
                url: `/admin/delete-user/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    alert(response.message);
                    fetchPendingUsers();
                    fetchAllUsers();
                }
            });
        }
    });
});
</script>
</body>
</html>