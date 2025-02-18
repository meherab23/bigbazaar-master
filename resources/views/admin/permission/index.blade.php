@extends('admin.layouts.admin_template')

@section('content')
<!-- Datatable -->

<link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Permission Management</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Permission Management</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Permission Management</h3>
            <a class="btn btn-primary float-end" style="float:right" href="{{ route('permissions.create') }}">Add New Permission</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <table id="attribute_table" class="table table-bordered table-striped">
              <thead>
                  <tr>
                    <th>Sl.</th>
                    <th>Permission Name</th>
                    <th>Action</th>
                  </tr>
              </thead>
              <tbody>  
                    @foreach($permissions as $row)  
                    <tr>            
                      <td>{{ $row['id'] }}</td> 
                      <td align="center">{{ $row['name'] }}</td>
                      <td>
                        <button onclick="window.location='" class="btn btn-sm btn-warning mb-2">Edit</button>
                        <form id="deleteCategory_{{ $row['id'] }}" action="" style="display: inline;" method="POST">
                          {{ method_field('DELETE') }}
                          @csrf
                          <input class="btn btn-sm btn-danger deleteLink"  data-toggle="modal" data-target="#category-delete-modal"  value="Delete" style="width: 100px; margin-top: -8px;">
                        </form>
                      </td>                        
                    </tr>
                  @endforeach
							</tbody>
						</table>
        
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->


<div class="modal modal-danger fade" id="category-delete-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete Category</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want to delete <b class="catname"></b> category?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-outline submitDeleteModal">Submit</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
      $('#example1').DataTable({
      	"order": [],
      });

      $('.deleteLink').click(function(){
      	var category_name = $(this).attr('category_name');
      	var category_row_id = $(this).attr('category_row_id');
      	console.log(category_name);
      	$('#category-delete-modal .catname').empty();
      	$('#category-delete-modal .catname').append(category_name);
      	$('#category-delete-modal .submitDeleteModal').attr('category_row_id', category_row_id);
      });

      $('.submitDeleteModal').click(function(){
      	var category_row_id = $(this).attr('category_row_id');
        console.log(category_row_id);
      	$('#deleteCategory_'+category_row_id).submit();
      });
  });
</script>
@endsection