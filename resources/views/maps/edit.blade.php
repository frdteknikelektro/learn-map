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
            <form class="form-horizontal" action="{{ route('maps.update', [ $map->id ]) }}" method="POST">
                {{ csrf_field() }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Edit Peta</h3>
                    </div>
                    <div class="panel-body">
                        @if (count($errors))
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                        @endif
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nama</label>
                            <div class="col-sm-10">
                                <input name="name" type="text" class="form-control" required value="{{ $map->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Poligon</label>
                            <div class="col-sm-10">
                                <input name="encoded_polygon" type="hidden" class="form-control" required value="{{ $map->encoded_polygon }}">
                                <div class="map"></div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer clearfix">
                        <button type="submit" name="_method" value="PUT" class="btn btn-default pull-right">Simpan</button>
                        <button type="submit" name="_method" value="DELETE" class="btn btn-default">Hapus</button>
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
        var polygon = new google.maps.Polygon({
            editable: true,
            paths: google.maps.geometry.encoding.decodePath('{{ $map->encoded_polygon }}')
        });
        polygon.addListener('mouseout', function (polyMouseEvent) {
            document.querySelector('[name=encoded_polygon]').value = google.maps.geometry.encoding.encodePath(polygon.getPath());
        });

        var latLngBounds = new google.maps.LatLngBounds();
        polygon.getPaths().forEach(function (path) {
            path.forEach(function (latLng) {
                latLngBounds.extend(latLng);
            });
        });

        var map = new google.maps.Map(document.querySelector('.map'), {
            center: latLngBounds.getCenter()
        });
        map.fitBounds(latLngBounds);

        polygon.setMap(map);
    }
</script>
@endpush
