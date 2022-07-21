<?php

include_once 'credentials.php';

function queryDB($query) {
	$conn = new mysqli(HOST, USER, PASS, DB);

	$res = $conn->query($query);

	if (is_bool($res)) {
		return $res;
	}

	$resArray = array();

	if ($res->num_rows > 0) {
		while ($fila = $res->fetch_array(MYSQLI_ASSOC)) {
			$resArray[] = $fila;
		}
	}

	return $resArray;
}

?>
