<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Pending Apartments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
        .hero-section {
            background-color: #0b6c5f;
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card { border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        .btn-success { background-color: #0b6c5f; border-color: #0b6c5f; }
        .btn-danger { background-color: #c62828; border-color: #c62828; }
    </style>
</head>
<body>

<div class="hero-section">
    <div>
        <h1>Pending Apartments</h1>
        <p>Approve or reject apartments submitted by owners.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Dashboard</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</div>

<div class="container mt-4">
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="pending-apartments-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Owner</th>
                            <th>Phone</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
function fetchPendingApartments() {
    $.get("/admin/pending-apartments", function(data) {
        let tbody = $("#pending-apartments-table tbody");
        tbody.empty();
        data.data.forEach(apartment => {
            tbody.append(`
                <tr>
                    <td>#${apartment.id}</td>
                    <td>${apartment.owner.name}</td>
                    <td>${apartment.owner.phone}</td>
                    <td>${apartment.province.name}</td>
                    <td>${apartment.city.name}</td>
                    <td><span class="badge bg-warning text-dark">${apartment.admin_status}</span></td>
                    <td>
                        <button class="btn btn-sm btn-success btn-approve-apartment me-1" data-id="${apartment.id}">Approve</button>
                        <button class="btn btn-sm btn-danger btn-reject-apartment" data-id="${apartment.id}">Reject</button>
                    </td>
                </tr>
            `);
        });
    });
}

$(document).ready(function(){
    fetchPendingApartments();

    $(document).on('click', '.btn-approve-apartment', function(){
        let id = $(this).data('id');
        $.post(`/admin/approve-apartment/${id}`, {_token: '{{ csrf_token() }}'}, function(res){
            alert(res.message);
            fetchPendingApartments();
        });
    });

    $(document).on('click', '.btn-reject-apartment', function(){
        let id = $(this).data('id');
        $.post(`/admin/reject-apartment/${id}`, {_token: '{{ csrf_token() }}'}, function(res){
            alert(res.message);
            fetchPendingApartments();
        });
    });
});
</script>

</body>
</html>
