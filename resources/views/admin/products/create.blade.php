@extends('admin.layouts.admin_template')

@section('content')
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- summernote -->

<style type="text/css">
  .dark-mode input:-webkit-autofill{
    -webkit-text-fill-color: #000 !important;
  }

  .atr-wrapper{
    border-radius: 5px; border: 1px solid #ddd; background: #f7f7f5;  
  }

  .atr-wrapper .single-atr {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      border-bottom: 1px solid #ddd;
      margin-left: 10px;
  }

  .atr-wrapper .single-atr .atr-title, .atr-wrapper .single-atr .icheck-primary {
      width: 120px;
      max-width: 120px;
  }

  .atr-wrapper .single-atr .icheck-primary .main_label{
    font-weight: bold;
    color: #000;
  }

  .atr-wrapper .single-atr .icheck-primary label span{
    color: #c76e6e;
  }

  .atr-wrapper .single-atr label.cb span {
      line-height: 20px;
      margin-left: 30px;
      color: #000;
  }

  .preview {
      display: inline-block;
      margin: 10px;
  }
  .preview img {
      width: 100px;
      height: 100px;
      margin-right: 10px;
  }
</style>
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
                <h3 class="card-title">Manage Products</h3>
              </div>
              
              <!-- Main content -->
                <form role="form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                    <section class="content" style="padding: 10px;">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Product Basic Information</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label for="productName">Product Name</label>
                                <input type="text" class="form-control" id="productName" placeholder="Enter product title" name="product_name" required>
                              </div>
                              <div class="form-group">
                                <label>Product Short Description</label>
                                <textarea class="form-control" rows="3" placeholder="Enter short description" name="short_description" required></textarea>
                              </div>
                              <div class="form-group">
                                <label>Product Detail Description</label>
                                <textarea id="summernote" name="long_description">
                                  Place <em>some</em> <u>text</u> <strong>here</strong>
                                </textarea>
                              </div>
                              <div class="form-group">
                                <label for="exampleInputFile">Product Main Image</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" id="exampleInputFile"  class="custom-file-input"  name="feature_image">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                  </div>
                                  <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <!-- /.card -->
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Product Links</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label for="inputStatus">Category Level</label>
                                <select name="parent_id" class = "form-control" required>
                                    <option value="">Select</option>
                                    @foreach($all_categories as $row)
                                        <option value="{{ $row->category_row_id}}">
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
                                <label for="inputStatus">Brand/Manufacturer</label>
                                <select name="brand_id" class="form-control">
                                    <option value="">Select</option>
                                    <option value="1">MCP</option>
                                    <option value="2">RNS</option>
                                    <option value="3">DRD</option>
                                </select>
                              </div>

                              <div class="form-group">
                                <label for="productTags">Tags</label>
                                <input type="text" class="form-control" id="productTags" placeholder="Enter tags" name="product_tags">
                              </div>

                            </div>
                          <!-- /.card -->
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Product Price, SKU & Stock Information</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="row">
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="modelName">Model/Code</label>
                                    <input type="text" class="form-control" id="modelName" placeholder="Enter product model" name="product_model">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="productSKU">SKU</label>
                                    <input type="text" class="form-control" id="productSKU" placeholder="Enter product SKU" name="product_sku">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" class="form-control" id="quantity" placeholder="Enter stock quantity" name="stock_amount">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label>Unit</label>
                                    <select class="form-control" name="product_unit">
                                      <option value="">Select Unit</option>
                                      <option value="1">gm</option>
                                      <option value="2">kg</option>
                                      <option value="3">pcs</option>
                                      <option value="4">ml</option>
                                      <option value="5">ltr</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="productPrice">Price</label>
                                    <input type="number" class="form-control" id="productPrice" placeholder="Enter price" name="product_price">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label for="offerPrice">Discount/Offer Price</label>
                                    <input type="number" class="form-control" id="offerPrice" placeholder="Enter discount" name="discount_price">
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                    <label>Percent or Fixed</label>
                                    <select class="form-control" name="discount_type">
                                      <option value="">Discount Type</option>
                                      <option value="1">Percent %</option>
                                      <option value="2">Fixed</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-md-3">
                                  <div class="form-group">
                                  <label>Discount Date Range:</label>
                                  <div class="input-group">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                      </span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="reservation" name="datefilter">
                                  </div>
                                </div>
                                </div>
                              </div>
                            </div>
                          <!-- /.card -->
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Product Attributes/Variations</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <input class="inventory_on_off" type="checkbox" name="my-checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success">

                              <div class="atr-wrapper" style="display: none; margin-top: 10px;">
                                  @foreach($all_attributes as $adata)
                                    @php
                                      $aid = $adata->attribute_row_id; 
                                      $atr_value = json_decode($adata->attribute_value, true);
                                      $atr_count = count(json_decode($adata->attribute_value, true));
                                    @endphp
                                    <div class="single-atr">
                                        <div class="icheck-primary d-inline">
                                            <input class="variation_type" type="checkbox" id="checkboxPrimary_{{ $aid }}" atr_id="{{ $aid }}" atr_count="{{ $atr_count }}" atr_name="{{ $adata->attribute_name }}">
                                            <label for="checkboxPrimary_{{ $aid }}"><span class="main_label">{{ $adata->attribute_name }}</span></label>
                                          </div>
                                        @foreach($atr_value as $key => $atrdata)
                                          <div class="icheck-primary d-inline variation_values_{{ $aid }}">
                                            <input class="attribute_data" type="checkbox" id="checkboxPrimary_{{ $aid }}_{{ $key }}" atr_data="{{ $atrdata }}" parent_atr_title="{{ $adata->attribute_name }}">
                                            <label for="checkboxPrimary_{{ $aid }}_{{ $key }}"><span>{{ $atrdata }}</span></label>
                                          </div>
                                        @endforeach
                                    </div>
                                  @endforeach
                              </div>

                              <div class="row attribute_combination" style="display: none; margin-top:15px;">
                                <div class="col-12">
                                  <div class="card">
                                    <div class="card-header">
                                      <h3 class="card-title">Attributes Combination</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive p-0">
                                      <table class="table table-hover text-nowrap attributes_details">
                                        <thead>
                                          <tr>
                                            <th>SL.</th>
                                            <th>Attributes</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                      </table>
                                    </div>
                                    <!-- /.card-body -->
                                  </div>
                                  <!-- /.card -->
                                </div>
                              </div>

                            </div>
                          <!-- /.card -->
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Product Additional Gallery Images</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label for="file-input">Product Gallery Images</label>
                                <div class="input-group">
                                  <div class="custom-file">
                                    <input type="file" id="file-input"  class="custom-file-input"  name="gallery_images[]" multiple>
                                    <label class="custom-file-label" for="file-input">Choose file</label>
                                  </div>
                                  <div class="input-group-append">
                                    <span class="input-group-text">Upload</span>
                                  </div>
                                </div>
                                <div id="preview-container"></div>
                              </div>
                            </div>
                          <!-- /.card -->
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="card card-primary">
                            <div class="card-header">
                              <h3 class="card-title">Products Additional Information</h3>

                              <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                  <i class="fas fa-minus"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="form-group">
                                <label class="col-form-label" for="inputWarning"><i class="far fa-bell"></i> Product Collection</label>
                              </div>
                              <div class="form-group">
                                <div class="form-group clearfix">
                                  <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkboxPrimary1" name="is_featured">
                                    <label for="checkboxPrimary1">Featured Product</label>
                                  </div>
                                  <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkboxPrimary2" name="top_selling">
                                    <label for="checkboxPrimary2">Top Selling Product</label>
                                  </div>
                                  <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="checkboxPrimary3" name="is_refundable">
                                    <label for="checkboxPrimary3">Is Refundable</label>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <!-- /.card -->
                          </div>
                        </div>
                      </div>

                      <div class="col-12" style="margin: 10px">
                        <a href="#" class="btn btn-secondary">Cancel</a>
                        <input type="submit" value="Submit" class="btn btn-success float-right">
                      </div>

                    </section>
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



  <script type="text/javascript">

    $(document).ready(function(){
      // Summernote
      $('#summernote').summernote();

      $("#file-input").on("change", function(){
          var files = $(this)[0].files;
          $("#preview-container").empty();
          if(files.length > 0){
              for(var i = 0; i < files.length; i++){
                  var reader = new FileReader();
                  reader.onload = function(e){
                      $("<div class='preview '><img src='" + e.target.result + "'><button class='delete btn btn-danger'>Delete</button></div>").appendTo("#preview-container");
                  };
                  reader.readAsDataURL(files[i]);
              }
          }
      });
      $("#preview-container").on("click", ".delete", function(){
          $(this).parent(".preview").remove();
          $("#file-input").val(""); // Clear input value if needed
      });

      //Date range picker
      $('input[name="datefilter"]').daterangepicker({
          autoUpdateInput: false,
          locale: {
              cancelLabel: 'Clear'
          }
      });

      $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
          $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
      });

      $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
          $(this).val('');
      });



      $('.alert-danger').delay(3000).fadeOut('slow');
      $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
      });

      $('body').on('switchChange.bootstrapSwitch','.inventory_on_off',function () {
         if($(this).is(':checked')){
            //console.log('on');
            $('.atr-wrapper').delay(200).fadeIn();
            $('.attribute_combination').delay(200).fadeIn();
          } else {
            //console.log('off');
            $('.atr-wrapper').delay(200).fadeOut();
            $('.attribute_combination').delay(200).fadeOut();
          }
      });

       var main_attributes = {
        size:[],
        color:[],
       };

      $('.variation_type').click(function(){
        if($(this).is(':checked')){
           var atr_id = $(this).attr('atr_id');
           var atr_count = $(this).attr('atr_count');
           var atr_name = $(this).attr('atr_name');

           for (var i = 0; i < atr_count; i++) {
              $('#checkboxPrimary_'+atr_id+'_'+i).prop('checked', true);
           }

        } else {
           var atr_id = $(this).attr('atr_id');
           var atr_count = $(this).attr('atr_count');
           for (var i = 0; i < atr_count; i++) {
              $('#checkboxPrimary_'+atr_id+'_'+i).prop('checked', false);
           }
        }
      });

      const combineArrays = (...arr) => {
         const res = [];
         const combinePart = (part, index) => {
            arr[index].forEach(el => {
               const p = part.concat(el);
               if(p.length === arr.length){
                  res.push(p.join(' + '));
                  return;
               };
               combinePart(p, index + 1);
            });
         };
         combinePart([], 0);
         return res;
      }

      $('.attribute_data').click(function(){
          var parent_atr_title = ($(this).attr('parent_atr_title').toLowerCase()).split(' ').join('_');
          var variation_title = $(this).attr('atr_data');
          var combination_array = [];
            
          if($(this).is(':checked')){
          
            if(!main_attributes[parent_atr_title]){
              main_attributes[parent_atr_title] = [];
              var combinations = [];
              main_attributes[parent_atr_title].push(variation_title);
              
            } else {
              main_attributes[parent_atr_title].push(variation_title);
            }
            var html = '';
            $(".attributes_details tbody").empty();

            if ((main_attributes['size'].length > 0) && (main_attributes['color'].length > 0)) {
              combination_array = combineArrays(main_attributes['size'], main_attributes['color']);
              //console.log(combination_array);
              
              $.each(combination_array , function(index, val) {
                var serial = index + 1; 
                html+='<tr><td>'+serial+'</td><td>'+val+'</td><td><input type="text" class="form-control" placeholder="Price" name="attr_price['+val+']"></td><td><input type="text" class="form-control" placeholder="Quantity" name="attr_quantity['+val+']"></td></tr>';
              });
              $(".attributes_details tbody").append(html);
            } else {
              console.log(main_attributes);
              //console.log(main_attributes['color']);
              $.each(main_attributes[parent_atr_title] , function(index, val) {
                var serial = index + 1; 
                html+='<tr><td>'+serial+'</td><td>'+val+'</td><td><input type="text" class="form-control" placeholder="Price" name="attr_price['+val+']"></td><td><input type="text" class="form-control" placeholder="Quantity" name="attr_quantity['+val+']"></td></tr>';
              });
              $(".attributes_details tbody").append(html);
            } 

          } else {

            main_attributes[parent_atr_title].splice($.inArray(variation_title, main_attributes[parent_atr_title]), 1);
            
            var html = '';
            $(".attributes_details tbody").empty();

            if ((main_attributes['size'].length > 0) && (main_attributes['color'].length > 0)) {
              combination_array = combineArrays(main_attributes['size'], main_attributes['color']);
              //console.log(combination_array);
              
              $.each(combination_array , function(index, val) {
                var serial = index + 1; 
                html+='<tr><td>'+serial+'</td><td>'+val+'</td><td><input type="text" class="form-control" placeholder="Price" name="attr_price['+val+']"></td><td><input type="text" class="form-control" placeholder="Quantity" name="attr_quantity['+val+']"></td></tr>';
              });
              $(".attributes_details tbody").append(html);
            } else {
              //console.log(main_attributes['size']);
              //console.log(main_attributes['color']);
              $.each(main_attributes[parent_atr_title] , function(index, val) {
                var serial = index + 1; 
                html+='<tr><td>'+serial+'</td><td>'+val+'</td><td><input type="text" class="form-control" placeholder="Price" name="attr_price['+val+']"></td><td><input type="text" class="form-control" placeholder="Quantity" name="attr_quantity['+val+']"></td></tr>';
              });
              $(".attributes_details tbody").append(html);
            } 
          }
      });

    });
  </script>


@endsection