@extends('layouts.app')

@section('content-header')

@endsection

@section('content')
<div class="container">
    @if(session('success'))
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
              <table id="matriksawal" class="table table-bordered text-center">
                <h6>Pembentukan Matriks Keputusan Awal (X)</h6>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Alternatif</th>
                        <th>C1</th>
                        <th>C2</th>
                        <th>C3</th>
                        <th>C4</th>
                    </tr>
                </thead>
                {{-- <tbody>
                    @foreach ($criteria as $data)
                    <tr>
                        <td>{{ $data->criteria_code }}</td>
                        <td>{{ $data->criteria_name }}</td>
                        <td>{{ $data->criteria_type }}</td>
                        <td>{{ $data->uom }}</td>
                        <td>{{ $data->remark }}</td>
                    </tr>
                    @endforeach
                </tbody> --}}
              </table>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection