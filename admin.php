<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$templatePath = __DIR__ . '/admin.html';
$dbPath       = __DIR__ . '/biblioteca.db';

if (!file_exists($templatePath)) {
    die("Template non trovato: $templatePath");
}
$html = file_get_contents($templatePath);

if (!file_exists($dbPath)) {
    die("Database non trovato: $dbPath");
}
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Errore connessione DB: " . $e->getMessage());
}

$stmt = $pdo->query('SELECT id, titolo, autore, anno_pubblicazione FROM libri ORDER BY titolo');
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!preg_match('/<!--\s*libriAdmin_start\s*-->([\s\S]*?)<!--\s*libriAdmin_end\s*-->/i', $html, $matches)) {
    die('Markers libriAdmin_start/end non trovati in admin.html');
}
$rowTemplate = $matches[1];

$fullContent = '';
foreach ($books as $b) {
    $row = $rowTemplate;
    $row = str_replace('{{ID_Libro}}', htmlspecialchars($b['id']), $row);
    $row = str_replace('{{Titolo}}', htmlspecialchars($b['titolo']), $row);
    $row = str_replace('{{Autore}}', htmlspecialchars($b['autore']), $row);
    $row = str_replace('{{Anno}}', (int)$b['anno_pubblicazione'], $row);
    $fullContent .= $row;
}

$html = preg_replace(
    '/<!--\s*libriAdmin_start\s*-->[\s\S]*?<!--\s*libriAdmin_end\s*-->/i',
    "<!-- libriAdmin_start -->\n" . $fullContent . "<!-- libriAdmin_end -->",
    $html
);

echo $html;
?>
