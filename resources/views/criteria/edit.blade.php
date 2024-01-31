@extends('layouts.app')

@section('content')

<div id="success-alert" class="alert alert-success" style="display: none;"></div>
<div id="error-alert" class="alert alert-danger" style="display: none;"></div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-primary">
                <dir class="card-header">
                    <h3 class="card-title"> Edit Kriteria</h3>
                </dir>

                <div class="card-body container">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="criteria_name">Kriteria</label>
                                <input type="text" class="form-control" id="criteria_name" name="criteria_name" value="{{ $criteria->criteria_name}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Tipe Kriteria</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="criteria_type" id="criteria_type_benefit" value="benefit" {{ $criteria->criteria_type == "benefit" ? 'checked' : '' }}>
                                    <label class="form-check-label" for="criteria_type_benefit">Benefit</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="criteria_type" id="criteria_type_cost" value="cost" {{ $criteria->criteria_type == "cost" ? 'checked' : '' }}>
                                    <label class="form-check-label" for="criteria_type_cost">Cost</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="uom">UOM</label>
                                <input type="text" class="form-control" id="uom" name="uom" value="{{ $criteria->uom}}">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="remark">Remark</label>
                                <textarea class="form-control" id="remark" name="remark">{{ $criteria->remark}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer float-right">
                        <button type="submit" id="submit_btn" onclick="updateCriteria()" class="btn btn-primary">Submit</button>
                        <a href="{{ route('criteria.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script>

        function updateCriteria(){
            var criteria_name = $('#criteria_name').val();
            var criteria_type = $("input[name='criteria_type']:checked").val();
            var uom = $('#uom').val();
            var remark = $('#remark').val();

            var data = {
                _token: "{{ csrf_token() }}",
                criteria_name: criteria_name,
                criteria_type: criteria_type,
                uom: uom,
                remark: remark
            };

            $.ajax({
                type: "POST",
                url: "{{ route('criteria.update', $criteria->id) }}",
                data: data,
                success: function(response) {
                    if (response.status == true) {
                        $('#submit_btn').attr('disabled', 'disabled');
                        $('#success-alert').html(response.message);
                        $('#success-alert').show();
                        setTimeout(function() {
                            $('#success-alert').fadeOut('fast');
                        }, 3000);
                        setTimeout(function() {
                            window.location.href = "{{ route('criteria.index') }}";
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
            })
        }

    </script>

@endsection