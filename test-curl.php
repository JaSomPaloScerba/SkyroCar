<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.google.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
if ($output === false) {
    echo "cURL Error: " . curl_error($ch);
} else {
    echo "Success!";
}
curl_close($ch);
?>
