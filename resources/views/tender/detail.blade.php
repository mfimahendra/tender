@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content-header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <h4>Tender <b class="text-primary">{{ $tender->tender_name }}</b></h4>
            </div>
            <div class="col-2"></div>
            <div class="col-4">
                <a href="{{ route('tender.create') }}">
                    <button class="btn btn btn-success btn-sm float-right">
                        <i class="fa fa-plus"></i>&nbsp;
                        Add Vendor
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
        @if ($criteria_id == null)
            <div id="warning-alert" class="alert alert-warning">
                <b>Warning!</b> Kriteria belum ditambahkan!!!
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-10">
                                <div class="chart">
                                    <canvas id="stackedBarChart" style="height: 350px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                            <div class="col-2">
                                <table id="criteria_table" class="table table-sm table-bordered table-hover">
                                    <tr>
                                        <th colspan="2" style="text-align: center;">Tabel Kriteria</th>
                                    </tr>
                                    @foreach ($criteria_data as $ct_data)
                                        <tr>
                                            <td>{{ $ct_data->criteria_name }}</td>
                                            <td>{{ $ct_data->criteria_weight }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body table-responsive p-0" style="height: 300px;">
                                    <table id="tender_table" class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Vendor</th>
                                                <th>Status</th>
                                                <th>Score</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($vendor_lists as $data)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $data->vendor_name }}</td>
                                                    <td>{{ $data->remark }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary" onclick="openModalCriteriaValue('{{ $data->vendor_id }}', '{{ $data->vendor_name }}')">
                                                            <i class="fas fa-star"></i>&nbsp;
                                                            {{ $data->score }}
                                                        </button>
                                                    </td>
                                                    <td>{{ $data->date }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info">
                                                            <i class="fas fa-edit"></i>&nbsp;
                                                            Edit
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

    <div class="modal fade" id="modalCriteriaValue">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><b class="text-primary" id="modal_vendor_name"></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Table Score&hellip;</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>            
        </div>        
    </div>    
@endsection

@section('scripts')
    {{-- DataTable --}}
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
    {{-- ChartJS --}}
    <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>

    <script>
        $(document).ready(function() {

        });

        function openModalCriteriaValue(vendor_id, vendor_name) {            
            $('#modal_vendor_name').text(vendor_name);
            $('#modalCriteriaValue').modal('show');
        }



        $(function() {
            $("#tender_table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tender_table_wrapper .col-md-6:eq(0)');
        });

        setTimeout(function() {
            $('#success-alert').fadeOut('fast');
            $('#error-alert').fadeOut('fast');
        }, 3000); // Durasi tampilan alert dalam milidetik (3000ms = 3 detik)        


        var areaChartData = {
            labels: ['Garansi', 'Pengalaman Pekerjaan', 'Harga Penawaran', 'Waktu Pengerjaan', 'Jumlah Karyawan', 'Toleransi Pembayaran'],
            datasets: [{
                    label: 'Digital Goods',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: false,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: [28, 48, 40, 19, 86, 27]
                },
                {
                    label: 'Electronics',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: false,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: [65, 59, 80, 81, 56, 55]
                },
            ]
        }

        var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
        var stackedBarChartData = $.extend(true, {}, areaChartData)

        var stackedBarChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }

        new Chart(stackedBarChartCanvas, {
            type: 'bar',
            data: stackedBarChartData,
            options: stackedBarChartOptions
        })
    </script>
@endsection
