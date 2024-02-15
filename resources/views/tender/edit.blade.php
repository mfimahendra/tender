@extends('layouts.app')

@section('styles')    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    <style>
        .dualist-title {
            text-align: center;
            font-weight: bold;            

        }
    </style>
@endsection

@section('content')    

   
 <div id="success-alert" class="alert alert-success" style="display: none;"></div>
    <div id="error-alert" class="alert alert-danger" style="display: none;"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Tender</h3>
                    </div>
                                        
                        <div class="card-body container">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tender_name">Tender Name</label>
                                        <input type="text" class="form-control" id="tender_name" name="tender_name" placeholder="Enter Tender Name" value="{{ $tender->tender_name }}">
                                    </div>                                    
                                </div>                                
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="tender_date">Tender Date</label>                                                                                    
                                        <div class="input-group date" id="tender_date" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#tender_date" value="{{ $tender->tender_date }}"/>
                                            <div class="input-group-append" data-target="#tender_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>                                          
                                    </div>
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tender_name">Vendor Name</label>
                                        <div class="input-group mb-3">
                                            <div class="card-body" style="padding: 0;">
                                                <div class="row">
                                                    <div class="col-6 dualist-title"><p class="bg-blue">Vendor List </p></div>
                                                    <div class="col-6 dualist-title"><p class="bg-green">Vendor Selected</p></div>                                                    
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">                                                            
                                                            <select class="duallistbox" multiple="multiple" id="vendor_selected" name="vendor_selected">
                                                                @foreach ($vendor_selected as $v_selected)
                                                                    <option value="{{ $v_selected->vendor_id }}" selected>{{ $v_selected->vendor_name }}</option>                                                                    
                                                                @endforeach
                                                                @foreach ($vendor_unselected as $v_unselected)
                                                                    <option value="{{ $v_unselected->vendor_id }}">{{ $v_unselected->vendor_name }}</option>)                                                                    
                                                                @endforeach
                                                            </select>
                                                        </div>                                                        
                                                    </div>
                                                </div>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer float-right">
                                <button type="submit" id="submit_btn" onclick="submitTender()" class="btn btn-primary">Submit</button>
                                <a href="{{ route('tender.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>                    
                        </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="../../plugins/select2/js/select2.full.min.js"></script> --}}
    <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>    
    <script src="{{ asset('adminlte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>                

        $(function () {            
            $('.select2').select2()        
            $('.duallistbox').bootstrapDualListbox()
            $('#tender_date').datetimepicker({
                format: 'L'
            }); 

            var date = moment("{{ $tender->tender_date }}").format('L');
            $('#tender_date input').val(date);
        });       

        // form new form
        function submitTender(){
            var tender_name = $('#tender_name').val();
            var tender_date = $('#tender_date input').val();
            var vendor_selected = [];
            $('#bootstrap-duallistbox-selected-list_vendor_selected option').each(function(){
                vendor_selected.push($(this).val());
            });

            var data = {
                _token: "{{ csrf_token() }}",
                tender_name: tender_name,
                tender_date: tender_date,
                vendor_lists: vendor_selected
            };                    

            $.ajax({
                type: "POST",
                url: "{{ route('tender.store') }}",
                data: data,
                success: function (response) {
                    if (response.status == true) {
                        $('#submit_btn').attr('disabled', 'disabled');
                        $('#success-alert').html(response.message);
                        $('#success-alert').show();
                        setTimeout(function(){
                            $('#success-alert').fadeOut('fast');
                        }, 3000);
                        setTimeout(function(){
                            window.location.href = "{{ route('tender.index') }}";
                        }, 3000);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    } else {
                        $('#error-alert').html(response.message);
                        $('#error-alert').show();
                        setTimeout(function(){
                            $('#error-alert').fadeOut('fast');
                        }, 3000);
                    }                    
                },
                error: function (response) {
                    $('#error-alert').html(response.message);
                    $('#error-alert').show();
                    setTimeout(function(){
                        $('#error-alert').fadeOut('fast');
                    }, 3000);
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                }
            });
        }

    </script>
@endsection