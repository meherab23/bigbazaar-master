@extends('admin.layouts.admin_template')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <!-- <h1> Category Create</h1> -->
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
          <!-- <li class="breadcrumb-item active">Category Create</li> -->
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add New Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
	            	@csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="category_name">Category Name</label>
                    <input type="text"  class ="form-control" id="category_name" name="category_name" placeholder = "Enter category Name">
                  </div>
                  <div class="form-group">
	                  <label for="catgory_type">Category</label>
	                  <select name="parent_id" class = "form-control" required>
                          <option value="">Select</option>
                          <option value="0"> Main Category </option>
                          @foreach($data['all_records'] as $row)
	                          <option value="{{ $row->category_row_id}}">
	                          <!-- @if($row->level == 0) <b>  @endif -->  
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
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile" name="category_image">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Short Description</label>
                    <input type="text" name="short_desc" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Long Description</label>
                    <textarea class="form-control" rows="3" name="long_desc"></textarea>
                  </div>
                  <div class="checkbox">
                    <label>
                    <input type="checkbox" name="featured_category"> Featured Category
                    </label>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection