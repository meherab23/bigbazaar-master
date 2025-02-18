@extends('admin.layouts.admin_template')

@section('content')
<!-- Datatable -->

<link rel="stylesheet" href="{{ asset('/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Product Management</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Product Management</li>
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
            <h3 class="card-title">Product Management</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
          <table id="product_table" class="table table-bordered table-striped">
			              <thead>
			                  <tr>
                          <th>SL.</th>
			                    <th>Product Title</th>
			                    <th>Image</th>
			                    <th>Category</th>
			                    <th>Brand</th>
			                    <th>Price</th>
			                    <th>Quantity</th>
			                    <th>Action</th>
			                  </tr>
			              </thead>
		                 <tbody> 
                          @php $count = 1; @endphp
		                      @foreach($all_products as $row)
                            @php $pid = $row->product_id; @endphp
                            <tr>            
                                <td>{{ $count }}</td>
                                <td align="left">{{ $row->product_title }}</td>
                                <td align="center">
                                  @if($row->product_images->feature_image != null)
                                  <img src="{{ asset('uploads/products/'.$pid.'/thumbnail').'/'.$row->product_images->feature_image }}" alt="" width="50px">
                                  @else
                                  <img src="{{ asset('images/no_category.png') }}" alt="" width="50px" height="50px">
                                  @endif
                                </td>
                                <td align="left">{{ $row->getCategory->category_name }}</td>
                                <td align="left"></td>
                                <td align="left">{{ $row->product_price }}</td>
                                <td align="left">@if(isset($row->product_inventory->stock_amount)){{ $row->product_inventory->stock_amount }}@endif</td> 
                                <td>
                                  <button class="btn btn-sm btn-info mb-2 product_details" data-toggle="modal" data-target="#modal-xl" product_id="{{ $pid }}">View</button>
                                  <button onclick="window.location='{{ url('/')}}/admin/products/{{$pid}}/edit'" class="btn btn-sm btn-warning mb-2">Edit</button>
                                  <form id="deleteCategory_{{$pid}}" action="{{ url('/')}}/admin/products/{{$pid}}" style="display: inline;" method="POST">
                                    {{ method_field('DELETE') }}
                                    @csrf
                                    <input class="btn btn-sm btn-danger deleteLink" category_name="{{ $row->product_title }}" category_row_id="{{$pid}}" data-toggle="modal" data-target="#category-delete-modal" deleteID="{{$pid}}" value="Delete" style="width: 100px; margin-top: -8px;">
                                  </form>
                                </td>         
                              </tr>
                              @php $count++; @endphp
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

<div class="modal fade" id="modal-xl">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Product Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


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
      $('#product_table').DataTable({
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

      $(document).on('click','.product-image-thumb',function(){
        var $image_element = $(this).find('img')
        $('.product-image').prop('src', $image_element.attr('src'))
        $('.product-image-thumb.active').removeClass('active')
        $(this).addClass('active')
      })


      $('.product_details').click(function(){
        var pid = $(this).attr('product_id');
        var _token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "{{ route('get-product-details') }}",
            method: 'POST',
            data: {
              pid: pid,
              _token: _token
            },
            success: function(data) {
               //console.log(data);
               $('#modal-xl .modal-body').empty();
               $('#modal-xl .modal-body').append(data);
            },
            error: function(data) {
                // If you got an error code.
            }
        }); 



      })


  });
</script>
@endsection