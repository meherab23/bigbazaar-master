@extends('admin.layouts.admin_template')

@section('content')
<!-- Datatable -->

<link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Category Management</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Category Management</li>
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
            <h3 class="card-title">Category Management</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form role="form" action="{{ url('admin/category').'/'.$data['single_info']->category_row_id }}" method="POST" enctype="multipart/form-data">
                {{ method_field('PUT') }}
                @csrf
                <input type="hidden"  name="category_row_id" value="{{ $data['single_info']->category_row_id }}" />
                <div class="box-body">
                  <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text"  class ="form-control" id="category_name" name="category_name" placeholder = "Enter category Name" value="{{ $data['single_info']->category_name }}">
                  </div>
                  <div class="form-group">
                    <label for="catgory_type">Category</label>
                    <select name="parent_id" class = "form-control" required>
                       <option value="" @if( $data['single_info']->parent_id == 0 ) selected = "selected" @endif>Select</option>
                       <option value="0" @if( $data['single_info']->parent_id == 0 ) selected = "selected" @endif> Main Category </option>
                        @foreach($data['all_records'] as $row)
                          <option value="{{ $row->category_row_id }}" @if($data['single_info']->parent_id == $row->category_row_id) selected = "selected" @endif>
                          @if($row->level == 0) <b>  @endif 
                          @if($row->level == 1) &nbsp; - @endif   
                          @if($row->level == 2) &nbsp; &nbsp; - - @endif     
                          @if($row->level == 3) &nbsp; &nbsp; &nbsp; - - - @endif       
                          @if($row->level == 4) &nbsp; &nbsp; &nbsp; &nbsp; - - - - @endif       
                          @if($row->level == 5) &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  - - - - - @endif       
                          @if($row->level > 5)  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; - - - @endif
                          {{ $row->category_name }} 
                          @if($row->level == 0) </b>  @endif  
                          </option>
                        @endforeach
                      </select>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Upload Image</label>
                    <input type="file" id="exampleInputFile" name="category_image">
                    <p class="help-block"></p>
                    <span>
                      @if($data['single_info']->category_image  != null)
                        <img src="{{ asset('uploads/category/').'/'.$data['single_info']->category_image }}" alt="" width="50px" height="50px">
                      @else
                        <img src="{{ asset('images/no-image-found.jpg') }}" alt="" width="50px" height="50px">
                      @endif
                    </span>
                  </div>
                  <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" name="short_desc" class="form-control" value="{{ $data['single_info']->category_description }}">
                  </div>
                  <div class="form-group">
                    <label>Long Description</label>
                    <textarea class="form-control" rows="3" name="long_desc">{{ $data['single_info']->category_description }}</textarea>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" name="featured_category"> Featured Category
                    </label>
                  </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
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
<script src="{{ asset('admin_files/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin_files/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

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