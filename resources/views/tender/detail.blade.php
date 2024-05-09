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
                <a href="{{ route('tender.edit', $tender->tender_id) }}">
                    <button class="btn btn btn-success btn-sm float-right">
                        <i class="fa fa-plus"></i>&nbsp;
                        Add Vendor
                    </button>
                </a>
                <button class="btn btn btn-info btn-sm float-right mr-1" onclick="openModalCriteriaData()">
                    <i class="fas fa-file-alt"></i>&nbsp;
                    Criteria Data
                </button>
                <a href = "{{ route('tender.scoring', $tender->tender_id) }}">
                    <button class="btn btn-sm btn-primary float-right mr-1">
                        <i class="fas fa-star"></i>&nbsp;
                        Scoring
                    </button>
                </a>
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
        @if (count($criteria_data) == 0)
            <div id="warning-alert" class="alert alert-warning">
                <b>Warning!</b> Kriteria belum ditambahkan!!!
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="card card-primary card-outline card-outline-tabs">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="custom-tabs-four-graph-tab" data-toggle="pill"
                                                href="#custom-tabs-four-graph" role="tab"
                                                aria-controls="custom-tabs-four-graph" aria-selected="true">Grafik</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="custom-tabs-four-table-tab" data-toggle="pill"
                                                href="#custom-tabs-four-table" role="tab"
                                                aria-controls="custom-tabs-four-table" aria-selected="false">Tabel</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="custom-tabs-four-tabContent">
                                        <div class="tab-pane fade show active" id="custom-tabs-four-graph" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-graph-tab">
                                            <div class="row">
                                                <div class="col-10">
                                                    <div class="chart">
                                                        <canvas id="stackedBarChart"
                                                            style="height: 350px; max-width: 100%;"></canvas>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <table id="criteria_table"
                                                        class="table table-sm table-bordered table-hover"
                                                        style="font-size: 14px;">
                                                        <tr>
                                                            <th colspan="2" style="text-align: center;">Tabel Kriteria
                                                            </th>
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
                                        </div>
                                        <div class="tab-pane fade" id="custom-tabs-four-table" role="tabpanel"
                                            aria-labelledby="custom-tabs-four-table-tab">
                                            <div class="row">
                                                <div class="col-12">
                                                    <table id="criteria_table"
                                                        class="table table-sm table-bordered table-hover"
                                                        style="font-size: 14px;">
                                                        <tr>
                                                            <th colspan="2" style="text-align: center;">Tabel Kriteria
                                                            </th>
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
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
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
                                                        <button class="btn btn-sm bg-orange"
                                                            onclick="openModalCriteriaValue('{{ $data->vendor_id }}', '{{ $data->vendor_name }}')">
                                                            <i class="fas fa-star"></i>&nbsp;
                                                            {{ $data->score }}
                                                        </button>
                                                    </td>
                                                    <td>{{ $data->date }}</td>
                                                    <td>
                                                        {{-- <a href="#" class="btn btn-xs btn-warning"
                                                            data-toggle="tooltip" title='Edit'>
                                                            <i class="fa fa-edit"></i> Edit
                                                        </a> --}}
                                                        <form action="{{ route('tender.delete', $data->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-xs btn-danger btn-flat show_confirm"
                                                                data-toggle="tooltip" title='Delete'>
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

    <div class="modal fade" id="modalCriteriaData">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Criteria Data<b class="text-primary"></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="criteria_data_percentage_container" class="btn btn-sm bg-info float-left">
                        Total :
                        <span id="criteria_data_percentage_counter">10</span>&nbsp;%
                    </span>

                    <button class="btn btn-success btn-sm float-right mb-2" onclick="addCriteriaData()">
                        <i class="fas fa-plus"></i>
                        Tambah
                    </button>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Bobot Kriteria</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tender_criteria_data_body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveCriteriaData()">Save changes</button>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kriteria</th>
                                <th>Nilai</th>
                                <th>uom</th>
                            </tr>
                        </thead>
                        <tbody id="value_criteria_data_body">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveValueCriteria()">Save changes</button>
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
        var tender_criteria_data = @json($criteria_data);
        var criteria_masters = @json($criteria_masters);
        var tender_criteria_value = @json($data_value_criteria);

        $(document).ready(function() {
            getDataChart();
        });

        function openModalCriteriaValue(vendor_id, vendor_name) {
            $('#modal_vendor_name').text(vendor_name);
            $('#modalCriteriaValue').modal('show');

            $('#value_criteria_data_body').html('');

            let html = '';
            let no = 1;

            $.each(tender_criteria_value, function(key, value) {
                if (value.vendor_id == vendor_id) {
                    html += '<tr>';
                    html += '<td>' + no + '</td>';
                    html += '<td class="vendorid" style="display: none;">' + value.vendor_id + '</td>';
                    html += '<td class="criteriacode" style="display: none;">' + value.criteria_code + '</td>';
                    html += '<td>' + value.criteria_name + '</td>';
                    if (value.value != null) {
                        html += '<td><input class="form-control value_data_input" type="number" value="' + value
                            .value + '"/></td>';
                    } else {
                        html += '<td><input class="form-control value_data_input" type="number" value="' + '0' +
                            '"/></td>';
                    }
                    html += '<td>' + value.uom + '</td>';
                    html += '</tr>';

                    no++

                } else if (value.vendor_id == vendor_id && value.value == null) {
                    html += '<tr>';
                    html += '<td>' + no + '</td>';
                    html += '<td class="vendorid" style="display: none;">' + vendor_id + '</td>';
                    html += '<td class="criteriacode" style="display: none;">' + value.criteria_code + '</td>';
                    html += '<td>' + value.criteria_name + '</td>';
                    html += '<td><input class="form-control value_data_input" type="number" value="' + '0' +
                        '"/></td>';
                    html += '<td>' + value.uom + '</td>';
                    html += '</tr>';

                    no++
                }
                // else {
                //     html += '<tr>';
                //     html += '<td>' + no + '</td>';
                //     html += '<td class="vendorid" style="display: none;">' + vendor_id + '</td>';
                //     html += '<td class="criteriacode" style="display: none;">' + value.criteria_code + '</td>';
                //     html += '<td>' + value.criteria_name + '</td>';
                //     html += '<td><input class="form-control value_data_input" type="number" value="' + '0' + '"/></td>';
                //     html += '<td>' + value.uom + '</td>';
                //     html += '</tr>';

                //     no++
                // }
            });

            $('#value_criteria_data_body').html(html);
        }

        function saveValueCriteria() {


            let criteria_value = [];


            $('#value_criteria_data_body tr').each(function() {
                let value = $(this).find('input').val();
                vendorid = $(this).find('.vendorid').text();
                let criteriacode = $(this).find('.criteriacode').text();

                let data = {
                    criteria_code: criteriacode,
                    value: value
                };

                criteria_value.push(data);
            });

            let data = {
                _token: "{{ csrf_token() }}",
                tender_id: "{{ $tender->tender_id }}",
                vendor_id: vendorid,
                criteria_value: criteria_value
            };

            $.ajax({
                type: "POST",
                url: "{{ route('tender.criteria_value.save') }}",
                data: data,
                success: function(response) {
                    if (response.status == true) {
                        $('#success-alert').html(response.message);
                        $('#success-alert').show();
                        setTimeout(function() {
                            $('#success-alert').fadeOut('fast');
                        }, 3000);
                        setTimeout(function() {
                            window.location.href = "{{ route('tender.detail', $tender->tender_id) }}";
                        }, 3000);
                        $("html, body").animate({
                            scrollTop: 0
                        }, "slow");
                    } else {
                        $('#error-alert').html(response.message);
                        $('#error-alert').show();
                        setTimeout(function() {
                            $('#error-alert').fadeOut('fast');
                        }, 3000);
                    }
                },
                error: function(response) {
                    $('#error-alert').html(response.message);
                    $('#error-alert').show();
                    setTimeout(function() {
                        $('#error-alert').fadeOut('fast');
                    }, 3000);
                    $("html, body").animate({
                        scrollTop: 0
                    }, "slow");
                }
            });
        }


        // CRUD Criteria Data
        function openModalCriteriaData() {

            $('#tender_criteria_data_body').html('');

            let html = '';
            let no = 1;

            $.each(tender_criteria_data, function(key, value) {
                html += '<tr>';
                html += '<td>' + no + '</td>';
                // html += '<td>' + value.criteria_name + '</td>';
                html += '<td>';
                html += '<select id="criteria_data" class="form-control select2" style="width: 100%;">';
                $.each(criteria_masters, function(idx, val) {
                    if (value.criteria_code == val.criteria_code) {
                        html += '<option value=' + value.criteria_code + ' selected>' + value
                            .criteria_name + '</option>';
                    } else {
                        html += '<option value=' + val.criteria_code + '>' + val.criteria_name +
                            '</option>';
                    }
                });

                html += '</select>';

                // html += '<td>' + value.criteria_weight + '</td>';
                let value_percent = (value.criteria_weight * 100);

                html += '<td><input class="form-control criteria_data_input" type="number" value="' +
                    value_percent + '"/></td>';

                html += '<td>';
                // html += '<div class="btn-group">';
                html += '<button class="btn btn-sm btn-warning" onclick="deleteCriteriaData(' + no + ')">';
                html += '<i class="fas fa-minus"></i>';
                html += '</button>';
                // html += '<button class="btn btn-sm btn-info" onclick="addCriteriaData()">';
                // html += '<i class="fas fa-plus"></i>';
                // html += '</button>';
                // html += '</div>';
                html += '</td>';


                html += '</tr>';

                no++
            });

            $('#tender_criteria_data_body').html(html);

            // criteria_data_percentage_counter
            let total = 0;
            $('.criteria_data_input').each(function() {
                total += parseInt($(this).val());
            });

            $('#criteria_data_percentage_counter').text(total);

            $('.criteria_data_input').on('input', function() {
                renderCriteriaDataPercentage();
            });


            $('#modalCriteriaData').modal('show');
        }

        function addCriteriaData() {
            let html = '';
            let no = $('#tender_criteria_data_body tr').length;

            html += '<tr>';

            let idx = no + 1

            html += '<td>' + idx + '</td>';
            html += '<td>';
            html += '<select id="criteria_data" class="form-control select2" style="width: 100%;">';
            html += '<option value="">-- Pilih Kriteria --</option>';
            $.each(criteria_masters, function(idx, val) {
                html += '<option value=' + val.criteria_code + '>' + val.criteria_name + '</option>';
            });
            html += '</select>';
            html += '</td>';
            html += '<td><input class="form-control criteria_data_input" type="number" value="0"/></td>';
            html += '<td>';
            html += '<button class="btn btn-sm btn-warning" onclick="deleteCriteriaData(' + no + ')">';
            html += '<i class="fas fa-minus"></i>';
            html += '</button>';
            html += '</td>';
            html += '</tr>';

            $('#tender_criteria_data_body').append(html);

            no++;

            $('.criteria_data_input').on('input', function() {
                renderCriteriaDataPercentage();
            });
        }

        function renderCriteriaDataPercentage() {
            let total = 0;
            $('.criteria_data_input').each(function() {
                total += parseInt($(this).val());
            });

            // criteria_data_percentage_container
            // if 100 green, if > 100 red and < 100 yellow
            if (total == 100) {
                $('#criteria_data_percentage_container').removeClass('bg-warning');
                $('#criteria_data_percentage_container').removeClass('bg-danger');
                $('#criteria_data_percentage_container').addClass('bg-success');
            } else if (total > 100) {
                $('#criteria_data_percentage_container').removeClass('bg-warning');
                $('#criteria_data_percentage_container').removeClass('bg-success');
                $('#criteria_data_percentage_container').addClass('bg-danger');
            } else {
                $('#criteria_data_percentage_container').removeClass('bg-danger');
                $('#criteria_data_percentage_container').removeClass('bg-success');
                $('#criteria_data_percentage_container').addClass('bg-warning');
            }

            $('#criteria_data_percentage_counter').text(total);
        }

        function deleteCriteriaData(no) {
            $('#tender_criteria_data_body tr').eq(no - 1).remove();

            renderCriteriaDataPercentage();
        }

        function saveCriteriaData() {

            // Prevent not 100%
            let total = 0;

            $('.criteria_data_input').each(function() {
                total += parseInt($(this).val());
            });

            if (total != 100) {
                alert('Total Bobot Kriteria harus 100%, tidak boleh kurang atau lebih dari 100%');
                return;
            }

            let criteria_data = [];

            $('#tender_criteria_data_body tr').each(function() {
                let criteria_code = $(this).find('select').val();
                let criteria_weight = $(this).find('input').val() / 100;

                let data = {
                    criteria_code: criteria_code,
                    criteria_weight: criteria_weight
                };

                criteria_data.push(data);
            });

            let data = {
                _token: "{{ csrf_token() }}",
                tender_id: "{{ $tender->tender_id }}",
                criteria_data: criteria_data
            };

            $.ajax({
                type: "POST",
                url: "{{ route('tender.criteria_data.save') }}",
                data: data,
                success: function(response) {
                    if (response.status == true) {
                        $('#success-alert').html(response.message);
                        $('#success-alert').show();
                        setTimeout(function() {
                            $('#success-alert').fadeOut('fast');
                        }, 3000);
                        setTimeout(function() {
                            window.location.href = "{{ route('tender.detail', $tender->tender_id) }}";
                        }, 3000);
                        $("html, body").animate({
                            scrollTop: 0
                        }, "slow");
                    } else {
                        $('#error-alert').html(response.message);
                        $('#error-alert').show();
                        setTimeout(function() {
                            $('#error-alert').fadeOut('fast');
                        }, 3000);
                    }
                },
                error: function(response) {
                    $('#error-alert').html(response.message);
                    $('#error-alert').show();
                    setTimeout(function() {
                        $('#error-alert').fadeOut('fast');
                    }, 3000);
                    $("html, body").animate({
                        scrollTop: 0
                    }, "slow");
                }
            });

        }


        // function getDataChart() {

        //     let label_data = [];
        //     let datasets = [];

        //     for (let i = 0; i < tender_criteria_data.length; i++) {
        //         label_data.push(tender_criteria_data[i].criteria_name);
        //     }

        //     $.get("{{ route('tender.criteria_values.fetchAllCriteriaValuesByTenderId', $tender->tender_id) }}", function(response) {
        //         console.log(response);
        //     });


        //     var areaChartData = {
        //         labels: label_data,
        //         // datasets: 
        //         // datasets: [{
        //         //         label: 'Digital Goods',
        //         //         backgroundColor: 'rgba(60,141,188,0.9)',
        //         //         borderColor: 'rgba(60,141,188,0.8)',
        //         //         pointRadius: false,
        //         //         pointColor: '#3b8bba',
        //         //         pointStrokeColor: 'rgba(60,141,188,1)',
        //         //         pointHighlightFill: '#fff',
        //         //         pointHighlightStroke: 'rgba(60,141,188,1)',
        //         //         data: [28, 48, 40, 19, 86, 27]
        //         //     },
        //         //     {
        //         //         label: 'Electronics',
        //         //         backgroundColor: 'rgba(210, 214, 222, 1)',
        //         //         borderColor: 'rgba(210, 214, 222, 1)',
        //         //         pointRadius: false,
        //         //         pointColor: 'rgba(210, 214, 222, 1)',
        //         //         pointStrokeColor: '#c1c7d1',
        //         //         pointHighlightFill: '#fff',
        //         //         pointHighlightStroke: 'rgba(220,220,220,1)',
        //         //         data: [65, 59, 80, 81, 56, 55]
        //         //     },
        //         // ]
        //     }

        //     var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
        //     var stackedBarChartData = $.extend(true, {}, areaChartData)

        //     var stackedBarChartOptions = {
        //         responsive: true,
        //         maintainAspectRatio: false,
        //         scales: {
        //             xAxes: [{
        //                 stacked: true,
        //             }],
        //             yAxes: [{
        //                 stacked: true
        //             }]
        //         }
        //     }

        //     new Chart(stackedBarChartCanvas, {
        //         type: 'bar',
        //         data: stackedBarChartData,
        //         options: stackedBarChartOptions
        //     })

        // }

        function getDataChart() {
            let label_data = [];
            let datasets = [];

            // Definisikan palet warna tetap
            const colors = ['rgb(171,205,239)', 'rgb(110,151,218)', 'rgb(71,111,194)','rgb(46,72,167)', 'rgb(26,30,117)'];

            for (let i = 0; i < tender_criteria_data.length; i++) {
                label_data.push(tender_criteria_data[i].criteria_name);
            }

            $.get("{{ route('tender.criteria_values.fetchAllCriteriaValuesByTenderId', $tender->tender_id) }}", function(
                response) {
                if (response.status) {
                    let criteria_values = response.data;

                    let data_by_vendor = [];

                    // Mengelompokkan nilai berdasarkan vendor_id
                    criteria_values.forEach(function(value, index) {
                        if (!data_by_vendor[value.vendor_id]) {
                            data_by_vendor[value.vendor_id] = {
                                label: value.vendor_name,
                                backgroundColor: colors[index % colors.length], // Menggunakan warna dari palet yang telah ditentukan
                                borderColor: 'rgba(60,141,188,0.8)',
                                borderWidth: 1,
                                data: []
                            };
                        }
                        data_by_vendor[value.vendor_id].data.push(value.value);
                    });

                    // Menambahkan dataset baru ke dalam datasets
                    for (let vendor_id in data_by_vendor) {
                        datasets.push(data_by_vendor[vendor_id]);
                    }

                    // Membuat objek data chart baru dengan label dan datasets yang sudah dimodifikasi
                    var areaChartData = {
                        labels: label_data,
                        datasets: datasets
                    };

                    // Mendapatkan konteks dari elemen canvas
                    var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d');
                    var stackedBarChartData = $.extend(true, {}, areaChartData);

                    // Opsi untuk chart stacked bar
                    var stackedBarChartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            xAxes: [{
                                stacked: true
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    };

                    // Membuat chart stacked bar baru dengan data dan opsi yang sudah dimodifikasi
                    new Chart(stackedBarChartCanvas, {
                        type: 'bar',
                        data: stackedBarChartData,
                        options: stackedBarChartOptions
                    });
                } else {
                    console.error(response.message); // Menampilkan pesan kesalahan jika gagal mengambil data
                }
            });
        }


        $(function() {
            $("#tender_table").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                "pageLength": 10
            }).buttons().container().appendTo('#tender_table_wrapper .col-md-6:eq(0)');
        });

        setTimeout(function() {
            $('#success-alert').fadeOut('fast');
            $('#error-alert').fadeOut('fast');
        }, 3000); // Durasi tampilan alert dalam milidetik (3000ms = 3 detik)        
    </script>
@endsection
