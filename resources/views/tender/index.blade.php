@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

@endsection

@section('content-header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <h4>Tender</h4>
            </div>
            <div class="col-6"></div>
            <div class="col-4">
                <a href="{{ route('tender.create') }}">
                    <button class="btn btn btn-success btn-sm float-right">
                        <i class="fa fa-plus"></i>&nbsp;
                        Create Tender
                    </button>
                </a>
                <button class="btn btn btn-info btn-sm float-right mr-1">
                    <i class="fas fa-file-alt"></i>&nbsp;
                    Add Criteria
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="container">
        @if (session('success'))
            <div id="success-alert" class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div id="error-alert" class="alert alert-danger">
                {{ session('error') }}
            </div>        
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-box">
                                            <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Messages</span>
                                                <span class="info-box-number">1,410</span>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-9">Graphs</div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body table-responsive p-0" style="height: 300px;">
                                    <table id="tender_table" class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>ID</th>
                                                <th>Tender Name</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($tender_lists as $data)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $data->tender_id }}</td>
                                                    <td>{{ $data->tender_name }}</td>
                                                    <td>{{ $data->tender_date }}</td>
                                                    <td>
                                                        <a href="{{ route('tender.detail', $data->tender_id) }}" class="btn btn-xs btn-primary">
                                                            <i class="fa fa-eye"></i> Detail
                                                        </a>
                                                        <a href="{{ route('tender.edit', $data->tender_id) }}" class="btn btn-xs btn-warning" data-toggle="tooltip" title='Edit'>
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a>
                                                        <form action="{{ route('tender.destroy', $data->tender_id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-xs btn-danger btn-flat show_confirm" data-toggle="tooltip" title='Delete'>
                                                                <i class="fa fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @php
                                                    $no++;
                                                @endphp                                                                        
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')    
    <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script>
        $(function () {
            $("#tender_table").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tender_table_wrapper .col-md-6:eq(0)');
        });
        
        setTimeout(function() {
            $('#success-alert').fadeOut('fast');
            $('#error-alert').fadeOut('fast');
        }, 3000); // Durasi tampilan alert dalam milidetik (3000ms = 3 detik)        
    </script>
@endsection
