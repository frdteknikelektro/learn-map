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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Detail Peta</h3>
                </div>
                <div class="panel-body">
                    <div>
                        <label class="col-sm-2">Nama</label>
                        <div class="col-sm-10">
                            <p>{{ $map->name }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="col-sm-2">Poligon</label>
                        <div class="col-sm-10">
                            <div class="map"></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer clearfix">
                    <a class="btn btn-default" href="{{ route('maps.index') }}" role="button">Semua Peta</a>
                    <a class="btn btn-default pull-right" href="{{ route('maps.edit', [ $map->id ]) }}" role="button">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBpf7-ZlrqwGdOiuIqPhjOSEtEcGOcJ0Gs&libraries=drawing,geometry&callback=initMap" async defer></script>
<script type="text/javascript">
    function initMap() {
        var infoWindow = new google.maps.InfoWindow();

        var dataFeature = new google.maps.Data.Feature({
            geometry: new google.maps.Data.Polygon([
                google.maps.geometry.encoding.decodePath('{{ $map->encoded_polygon }}')
            ]),
            id: {{ $map->id }},
            properties: {
                name: '{{ $map->name }}'
            }
        });

        var data = new google.maps.Data();
        data.add(dataFeature);

        var latLngBounds = new google.maps.LatLngBounds();
        data.forEach(function (dataFeature) {
            dataFeature.getGeometry().forEachLatLng(function (latLng) {
                latLngBounds.extend(latLng);
            });
        });

        var map = new google.maps.Map(document.querySelector('.map'), {
            center: latLngBounds.getCenter()
        });
        map.fitBounds(latLngBounds);

        data.setMap(map);
        data.addListener('click', function (dataMouseEvent) {
            var a = document.createElement('a');
            a.href = '/maps/' + dataMouseEvent.feature.getId();
            a.appendChild(document.createTextNode(dataMouseEvent.feature.getProperty('name')));
            infoWindow.setContent(a);
            infoWindow.setPosition(dataMouseEvent.latLng);
            infoWindow.open(map);
        });
    }
</script>
@endpush
