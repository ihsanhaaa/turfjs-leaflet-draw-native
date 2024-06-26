<?php
require 'fungsi.php';

$dataMaps = query("SELECT * FROM maps");

// cek apakah tombol submit sudah ditekan atau belum
if (isset(($_POST["submit"]))) {

  // cek apakah data berhasil ditambahkan
  if (tambah($_POST) > 0) {
    echo "
			<script>
				alert('Data berhasil ditambahkan');
				document.location.href = 'create.php';
			</script>
		";
  } else {
    echo "
			<script>
				alert('Data gagal ditambahkan');
				document.location.href = 'create.php';
			</script>
		";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TurfJS Leaflet Draw with PHP and Form</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>

  <style>
    #dataForm {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      max-width: 1500px;
      margin: 0 auto;
    }

    #dataForm div {
      flex: 1;
      margin-right: 10px;
    }

    #deskripsi,
    #panjang,
    #lebar,
    #radius,
    #luas_lingkaran,
    #panjang_polyline,
    #tipe_geojson,
    #geojson {
      width: 100%;
      box-sizing: border-box;
    }
  </style>

</head>

<body>
  <div id="map" style="height: 550px;"></div>

  <form id="dataForm" action="" method="post" enctype="multipart/form-data" style="margin-top: 25px;">
    <div>
      <label for="deskripsi">Deskripsi:</label><br>
      <textarea id="deskripsi" name="deskripsi" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="geojson">Geojson:</label><br>
      <textarea id="geojson" name="geojson" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="tipe_geojson">Tipe:</label><br>
      <textarea id="tipe_geojson" name="tipe_geojson" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="panjang">Panjang Poligon(m):</label><br>
      <textarea id="panjang" name="panjang" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="lebar">Lebar Poligon(m):</label><br>
      <textarea id="lebar" name="lebar" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="radius">Radius Lingkaran(m):</label><br>
      <textarea id="radius" name="radius" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="luas_lingkaran">Luas Lingkaran(m):</label><br>
      <textarea id="luas_lingkaran" name="luas_lingkaran" rows="4" cols="50"></textarea>
    </div>

    <div>
      <label for="panjang_polyline">Panjang Polyline(m):</label><br>
      <textarea id="panjang_polyline" name="panjang_polyline" rows="4" cols="50"></textarea>
    </div>

    <div>
      <button type="submit" name="submit" style="margin-top: 40px;">Simpan Data</button>
    </div>
  </form>

  <!-- tambahkan cdn turf.js untuk dapat menjalankan algoritma perhitungan luas poligon -->
  <script src="https://cdn.jsdelivr.net/npm/@turf/turf@latest"></script>

  <script>
    var map = L.map("map").setView([-0.05509435153361005, 109.34942867782628], 15);

    // Basemaps
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'OpenStreetMap'
    });
    var openTopoMap = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
      attribution: 'OpenTopoMap'
    });

    var Esri_WorldImagery = L.tileLayer(
      'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
      });

    var googleStreets = L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
    });
    var googleTraffic = L.tileLayer('https://{s}.google.com/vt/lyrs=m@221097413,traffic&x={x}&y={y}&z={z}', {
      maxZoom: 20,
      minZoom: 2,
      subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
    });

    // Default basemap
    osm.addTo(map);

    var baseMaps = {
      "OSM": osm,
      "OpenTopoMap": openTopoMap,
      "Esri": Esri_WorldImagery,
      "Google Streets": googleStreets,
      "Google Traffic": googleTraffic,
    };

    L.control.layers(baseMaps).addTo(map);

    var mapsData = <?php echo json_encode($dataMaps); ?>;

    mapsData.forEach(function(mapData) {
      const geojson = JSON.parse(mapData.geojson);

      // Membuat layer GeoJSON dan menambahkannya ke peta
      L.geoJSON(geojson, {
        onEachFeature: function(feature, layer) {
          const popupContent = '<b>Popup Content</b><br>' +
            'Deskripsi: ' + mapData.deskripsi +
            '<br>Tipe: ' + mapData.tipe_geojson +
            '<br>Panjang: ' + mapData.panjang + ' m' +
            '<br>Lebar: ' + mapData.lebar + ' m' +

            '<br>Radius: ' + mapData.radius + ' m' +
            '<br>Luas Lingkaran: ' + mapData.luas_lingkaran + ' m' +
            '<br>Panjang Polyline: ' + mapData.panjang_polyline + ' m' +
            '<br><a href="edit.php?id=' + mapData.id + '">Edit</a>' +
            '<br><a href="hapus.php?id=' + mapData.id + '">Hapus</a>';
          layer.bindPopup(popupContent);
          layer.addTo(map);
        }
      });
    });

    var drawControl = new L.Control.Draw({
      edit: {
        featureGroup: new L.FeatureGroup(),
      },
    });
    map.addControl(drawControl);

    // Feature group to store drawn layers
    var drawnItems = new L.FeatureGroup().addTo(map);
    map.addLayer(drawnItems);

    // Event listener for when a new feature is created
    map.on("draw:created", function(event) {
      var layer = event.layer,
        feature = (layer.feature = layer.feature || {});
      feature.type = feature.type || "Feature";
      var props = (feature.properties = feature.properties || {});
      drawnItems.addLayer(layer);

      if (event.layerType === 'circle') {
        var layerType = event.layerType;
        document.getElementById("tipe_geojson").value = layerType;

        var radius = event.layer.getRadius();
        var area = Math.PI * Math.pow(radius, 2);

        console.log("Jari-jari lingkaran: " + radius + " meters");
        console.log("Luas lingkaran: " + area + " meter persegi");

        // Menampilkan hasil pengukuran dalam meter
        document.getElementById("radius").value = parseFloat(radius.toFixed(2));
        document.getElementById("luas_lingkaran").value = parseFloat(area.toFixed(2));

        var geojson = JSON.stringify(event.layer.toGeoJSON());
        document.getElementById("geojson").value = geojson;
      } else if (event.layerType === 'marker') {
        var layerType = event.layerType;
        document.getElementById("tipe_geojson").value = layerType;

        var layer = event.layer,
          feature = (layer.feature = layer.feature || {});
        feature.type = feature.type || "Feature";
        var props = (feature.properties = feature.properties || {});
        drawnItems.addLayer(layer);

        var geojson = JSON.stringify(event.layer.toGeoJSON());
        document.getElementById("geojson").value = geojson;
        alert('Marker tidak dapat melakukan perhitungan apapun');
      } else if (event.layerType === 'polyline') {
        var layerType = event.layerType;
        document.getElementById("tipe_geojson").value = layerType;

        // Handle polyline creation
        var polylineLatLngs = event.layer.getLatLngs();
        var totalLength = 0;

        // Menghitung panjang tiap segmen garis polilinier dan menambahkannya ke total panjang
        for (var i = 0; i < polylineLatLngs.length - 1; i++) {
          var segmentLength = polylineLatLngs[i].distanceTo(polylineLatLngs[i + 1]);
          totalLength += segmentLength;
        }

        console.log("Panjang polyline: " + totalLength + " meters");

        // Menampilkan hasil pengukuran dalam meter
        document.getElementById("panjang_polyline").value = parseFloat(totalLength.toFixed(2));

        var geojson = JSON.stringify(event.layer.toGeoJSON());
        document.getElementById("geojson").value = geojson;
      } else {
        var layerType = event.layerType;
        document.getElementById("tipe_geojson").value = layerType;

        // Mengukur panjang tepi polygon dalam meter
        var vertices = event.layer._latlngs[0];
        var firstVertex = vertices[0];
        var secondVertex = vertices[1];
        var edgeLength = firstVertex.distanceTo(secondVertex);

        // Mengukur luas polygon menggunakan turf.js
        var polygon = turf.polygon(event.layer.toGeoJSON().geometry.coordinates);
        var area = turf.area(polygon);

        // Menghitung lebar menggunakan rumus: Luas / Panjang
        var width = area / edgeLength;

        var width2 = parseFloat(width.toFixed(2));
        var edgeLength2 = parseFloat(edgeLength.toFixed(2));

        // Menampilkan hasil pengukuran dalam meter
        console.log("Panjang tepi polygon dari vertex pertama ke vertex kedua: " + edgeLength + " meters");
        console.log("Lebar polygon: " + width + " meters");
        console.log("Luas polygon: " + area + " meter persegi");

        document.getElementById("panjang").value = edgeLength2;
        document.getElementById("lebar").value = width2;

        var geojson = JSON.stringify(event.layer.toGeoJSON());
        document.getElementById("geojson").value = geojson;
      }
    });
  </script>
</body>

</html>