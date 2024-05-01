<?php

$conn = mysqli_connect("localhost", "root", "", "turfjs-leaflet-draw-native");



function tambah($data) {
	global $conn;
	// ambil data dari tiap elemen
	$deskripsi = htmlspecialchars($data["deskripsi"]);
	$geojson = htmlspecialchars($data["geojson"]);
	$tipe_geojson = htmlspecialchars($data["tipe_geojson"]);
	$panjang = htmlspecialchars($data["panjang"]);
	$lebar = htmlspecialchars($data["lebar"]);
	$radius = htmlspecialchars($data["radius"]);
	$luas_lingkaran = htmlspecialchars($data["luas_lingkaran"]);
	$panjang_polyline = htmlspecialchars($data["panjang_polyline"]);

	// query insert data
	$query = "INSERT INTO maps 
				VALUES
				('', '$deskripsi', '$geojson', '$tipe_geojson', '$panjang', '$lebar', '$radius', '$luas_lingkaran', '$panjang_polyline')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

?>

