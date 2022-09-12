<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 Crud operation using ajax(Real Programmer)</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body>
    
<div class="container">
    <h1>Laravel 8 Crud with Ajax</h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewItem"> Create New Item</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Category</th>
                <th>Name</th>
                <th width="300px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
   
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="itemForm" name="itemForm" class="form-horizontal">
                   <input type="hidden" name="item_id" id="item_id">
                    {{-- <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="category" name="category" placeholder="Enter Category" value="" maxlength="50" required="">
                        </div>
                    </div> --}}
                    <select class="form-select mb-3" name="category" aria-label="Default select example">
                        <option selected disabled>Select Category</option>
                        @foreach ($category as $category)
                            <option value="{{ $category->category }}">{{ $category->category }}</option>
                        @endforeach
                    </select>
     
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input id="name" name="name" placeholder="Enter Name" class="form-control" required>
                        </div>
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>  
<script type="text/javascript">
  $(function () {

    //*** Query For CSRF token ***//
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    //*** jquery to show datatable ****// 
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('items.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'category', name: 'category'},
            {data: 'name', name: 'name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    //***  Jquery 'click' for Modal Popup Form  ***//
    $('#createNewItem').click(function () {
        $('#saveBtn').val("create-item");
        $('#item_id').val('');
        $('#itemForm').trigger("reset");
        $('#modelHeading').html("Create New Item");
        $('#ajaxModel').modal('show');
    });
    
    //*** JQuery 'On' for Edit Function in form ***//
    $('body').on('click', '.editItem', function () {
      var item_id = $(this).data('id');
      $.get("{{ route('items.index') }}" +'/' + item_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Item");
          $('#saveBtn').val("edit-item");
          $('#ajaxModel').modal('show');
          $('#item_id').val(data.id);
          $('#title').val(data.title);
          $('#author').val(data.author);
      })
   });
   
       //*** JQuery 'click' for save button in form ***//

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Save');

        //*** JQuery 'ajax' to store data from form ***//
        $.ajax({
          data: $('#itemForm').serialize(),
          url: "{{ route('items.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
     
              $('#itemForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();
         
          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });
        //*** JQuery 'On' for delete Function in form ***//

    $('body').on('click', '.deleteItem', function () {
     
        var item_id = $(this).data("id");
        confirm("Are You sure want to delete !");
      
        $.ajax({
            type: "DELETE",
            url: "{{ route('items.store') }}"+'/'+item_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
     
  });
</script>
</body>
</html>