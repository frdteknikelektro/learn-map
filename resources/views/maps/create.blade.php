@extends('layout')

@section('title', 'Belajar Map')
@section('navbar-brand', 'Belajar Map')

@push('styles')
<style media="screen">
    .map {
        height: 450px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" action="{{ route('maps.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tambah Peta</h3>
                    </div>
                    <div class="panel-body">
                        @if (count($errors))
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" class="form-control" required value="{{ old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Poligon</label>
                            <div class="col-sm-10">
                                <input name="encoded_polygon" type="hidden" class="form-control" required value="{{ old('encoded_polygon') }}">
                                <div class="map"></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <button type="submit" class="btn btn-default pull-right">Tambah</button>
                        <a href="{{ route('maps.index') }}" class="btn btn-default">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBpf7-ZlrqwGdOiuIqPhjOSEtEcGOcJ0Gs&libraries=drawing,geometry&callback=initMap" async defer></script>
<script type="text/javascript">
    function initMap() {
        var map = new google.maps.Map(document.querySelector('.map'), {
            center: {
                lat: -2,
                lng: 118
            },
            zoom: 5
        });

        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['polygon']
            },
            polygonOptions: {
                editable: true
            }
        });
        drawingManager.addListener('polygoncomplete', function (polygon) {
            document.querySelector('[name=encoded_polygon]').value = google.maps.geometry.encoding.encodePath(polygon.getPath());
            polygon.addListener('mouseout', function (polyMouseEvent) {
                document.querySelector('[name=encoded_polygon]').value = google.maps.geometry.encoding.encodePath(polygon.getPath());
            });
            drawingManager.setMap(null);
        });
        drawingManager.setMap(map);
    }
</script>
@endpush
