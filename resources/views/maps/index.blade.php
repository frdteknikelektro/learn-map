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
                    <h3 class="panel-title">Map</h3>
                </div>
                <div class="panel-body">
                    <div class="map"></div>
                </div>
                <div class="panel-footer clearfix">
                    <a class="btn btn-default pull-right" href="{{ route('maps.create') }}" role="button">Tambah Peta</a>
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

        var dataFeatures = [];
        @foreach ($maps as $map)
        dataFeatures.push(new google.maps.Data.Feature({
            geometry: new google.maps.Data.Polygon([
                google.maps.geometry.encoding.decodePath('{{ $map->encoded_polygon }}')
            ]),
            id: {{ $map->id }},
            properties: {
                name: '{{ $map->name }}'
            }
        }));
        @endforeach

        var data = new google.maps.Data();
        for (var i = 0; i < dataFeatures.length; i++) {
          data.add(dataFeatures[i]);
        }

        var map = new google.maps.Map(document.querySelector('.map'), {
            center: {
                lat: -2,
                lng: 118
            },
            zoom: 5
        });

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
