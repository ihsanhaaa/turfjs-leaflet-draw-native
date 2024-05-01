<?php 
// Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "turfjs-leaflet-draw-native");

function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function tambah($data) {
	global $conn;
	// ambil data dari tiap elemen
	$deskripsi = htmlspecialchars($data["deskripsi"]);
	$geojson = $data["geojson"];
	$tipe_geojson = $data["tipe_geojson"];
	$panjang = $data["panjang"];
	$lebar = $data["lebar"];
	$radius = $data["radius"];
	$luas_lingkaran = $data["luas_lingkaran"];
	$panjang_polyline = $data["panjang_polyline"];

	// query insert data
	$query = "INSERT INTO maps
				VALUES
				('', '$deskripsi', '$geojson', '$tipe_geojson', '$panjang', '$lebar', '$radius', '$luas_lingkaran', '$panjang_polyline')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}


function hapus($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM maps WHERE id = $id");

	return mysqli_affected_rows($conn);
}


function ubah($data) {
	global $conn;

	// ambil data dari tiap elemen
	$id = $data["id"];
	$deskripsi = htmlspecialchars($data["deskripsi"]);
	$geojson = $data["geojson"];
	$tipe_geojson = $data["tipe_geojson"];
	$panjang = $data["panjang"];
	$lebar = $data["lebar"];
	$radius = $data["radius"];
	$luas_lingkaran = $data["luas_lingkaran"];
	$panjang_polyline = $data["panjang_polyline"];

	// query insert data
	$query = "UPDATE maps SET
				deskripsi = '$deskripsi',
				geojson = '$geojson',
				tipe_geojson = '$tipe_geojson',
				panjang = '$panjang',
				lebar = '$lebar',
				radius = '$radius',
				luas_lingkaran = '$luas_lingkaran',
				panjang_polyline = '$panjang_polyline'
				WHERE id = $id
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

?>