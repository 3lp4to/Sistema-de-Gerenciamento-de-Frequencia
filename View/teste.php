<?php
// Recebe JSON enviado pelo fetch
$data = json_decode(file_get_contents("php://input"), true);

$lat = $data['latitude'] ?? null;
$lon = $data['longitude'] ?? null;

// Se não recebeu latitude/longitude, retorna erro
if ($lat === null || $lon === null) {
    echo "Erro: coordenadas não recebidas.";
    exit;
}

// Coordenadas do centro do perímetro (em decimal)
$centroLat = -29.702388; // 29°42'08.6"S
$centroLon = -54.696472; // 54°41'47.3"W
$raioMetros = 5000; // 5 km de raio

// Função para calcular distância (Haversine)
function distancia($lat1, $lon1, $lat2, $lon2) {
    $raioTerra = 6371000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) ** 2 +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $raioTerra * $c;
}

// Calcula a distância
$dist = distancia($lat, $lon, $centroLat, $centroLon);

// Verifica se está dentro do perímetro
if ($dist <= $raioMetros) {
    echo "✅ Dentro do perímetro! Distância: " . round($dist) . " metros";
} else {
    echo "❌ Fora do perímetro. Distância: " . round($dist) . " metros";
}
?>
