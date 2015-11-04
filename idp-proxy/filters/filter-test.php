#!/usr/bin/php
<?php

$ldapSrv = $argv[1];
$query = $argv[2];

echo findElixirId($ldapSrv, $query) . "\n";


function findElixirId($ldapSrv, $query) {

	$conn = ldap_connect($ldapSrv)
		or die("Unable to connect to ldap server");

	ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3)
		or die ("Failed to set ldap protocol to version 3");

	ldap_bind($conn)
		or die ("Unable to bind ldap");

	$retorn = findElixirIdWithConnection($query, $conn);

	ldap_close($conn);

	return $retorn;
}

function findElixirIdWithConnection($query, $conn) {

	$result = ldap_search($conn
		// TODO - hidden content
	);

	$entries = ldap_get_entries($conn, $result);

	if ($entries["count"] == 0) {
		echo "No entry found";
		return;
	}

	if ($entries["count"] != 1) {
		echo "More entries found";
		return;
	}	

	$names = $entries[0]["" /*TODO - hidden content*/];

	for($i=0; $i<$names["count"]; $i++) {

		$needle = "@elixir-europe.org";
		if (substr($names[$i], -strlen($needle))===$needle) {

			return $names[$i];

		}
	}

}

