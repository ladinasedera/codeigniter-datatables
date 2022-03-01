<html>
<head>
    <title>CodeIgniter 3 - CRUD - DataTables - Bootstrap Modals</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"/>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }

        .box {
            width: 1270px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 25px;
        }
        td {
            vertical-align: middle;
        }
        .w-120 {
            width: 120px;
        }
    </style>
</head>
<body>
<div class="container box">
    <h1 class="text-center">CodeIgniter 3 - CRUD - DataTables - Bootstrap Modals</h1>
    <br/>
    <div class="table-responsive">
        <br/>
        <div class="right">
            <button type="button" id="add_button"
                    data-toggle="modal"
                    data-target="#userModal"
                    class="btn btn-info btn-lg w-120">Add
            </button>
        </div>
        <br/><br/>
        <table id="user_data" class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="width: 10%" class="id">#</th>
                <th style="width: 20%" class="first_name">First Name</th>
                <th style="width: 20%" class="last_name">Last Name</th>
                <th style="width: 5%">Edit</th>
                <th style="width: 5%">Delete</th>
            </tr>
            </thead>
        </table>

    </div>
</div>
</body>
</html>

<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add User</h4>
                </div>
                <div class="modal-body">
                    <label>Enter First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"/>
                    <br/>
                    <label>Enter Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"/>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id"/>
                    <input type="hidden" name="operation" id="operation"/>
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add"/>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#userModal').on('shown.bs.modal', function () {
            $('#first_name').focus()
        });

        $('#add_button').click(function () {

            $('#user_form')[0].reset();
            $('.modal-title').text("Add User");
            $('#action').val("Add");
            $('#operation').val("Add");
            $('#user_uploaded_image').html('');

        });

        var dataTable = $('#user_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "<?= base_url('user') ?>",
                type: "POST"
            },
            "columnDefs": [
                {
                    "targets": [3, 4],
                    "orderable": false,
                },
            ],

        });

        $(document).on('submit', '#user_form', function (event) {

            event.preventDefault();
            var firstName = $('#first_name').val();
            var lastName = $('#last_name').val();
            var operation = $('#operation').val();
            var url = '', msg = '';

            if (operation === 'Edit')
            {
                url = "<?= base_url('update-user') ?>";
                msg = 'updated';
            }
            else
            {
                url = "<?= base_url('create-user') ?>";
                msg = 'inserted';
            }

            if (firstName !== '' && lastName !== '')
            {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function (response) {

                        if (response.success)
                        {
                            alert('Data ' + msg);
                        }
                        else
                        {
                            alert(response.error);
                        }

                        $('#user_form')[0].reset();
                        $('#userModal').modal('hide');
                        dataTable.ajax.reload();

                    }
                });
            }
            else
            {
                alert("Both Fields are Required");
            }
        });

        $(document).on('click', '.update', function () {
            var user_id = $(this).attr("id");
            $.ajax({
                url: "<?= base_url("user") ?>/" + user_id,
                method: "GET",
                dataType: "json",
                success: function (data) {
                    $('#userModal').modal('show');
                    $('#first_name').val(data.first_name);
                    $('#last_name').val(data.last_name);
                    $('.modal-title').text("Edit User");
                    $('#user_id').val(user_id);
                    $('#user_uploaded_image').html(data.user_image);
                    $('#action').val("Edit");
                    $('#operation').val("Edit");
                }
            })
        });

        $(document).on('click', '.delete', function () {
            var user_id = $(this).attr("id");
            if (confirm("Are you sure you want to delete this?"))
            {
                $.ajax({
                    url: "<?= base_url("user") ?>/" + user_id,
                    method: "DELETE",
                    success: function (response) {
                        if (response.success)
                        {
                            alert('Deleted');
                        }
                        else
                        {
                            alert(response.error);
                        }
                        dataTable.ajax.reload();
                    }
                });
            }
            else
            {
                return false;
            }
        });
    });
</script>
   