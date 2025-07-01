<?php
    session_start();

    $metaDescription = $metaDescription ?? 'Biblioteca Luzzatti di Padova';
    $metaKeywords    = $metaKeywords    ?? 'biblioteca, padova, libri';

    $currentPage = basename($_SERVER['PHP_SELF']);
    $headerTemplate = file_get_contents('./html/templates/header.html');

    // mappatura delle pagine
    $pages = [
        'index.php' => ['text' => 'Home', 'class' => '', 'lang' => 'en'],
        'chi-siamo.php'  => ['text' => 'Chi Siamo', 'class' => ''],
        'catalogo.php' => ['text' => 'Catalogo', 'class' => ''],
    ];

    $currentAreaPage = null;

    // Aggiungi "Registrati" SOLO se l'utente NON è loggato + Area Personale dinamica e divisione per ruoli
    if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'Admin') {
            $pages['admin.php'] = ['text' => 'Area Admin', 'class' => ''];
            $currentAreaPage = 'admin.php';
        } else {
            $pages['login.php'] = ['text' => 'Area Personale', 'class' => ''];
            $currentAreaPage = 'login.php';
        }
    } else {
        $pages['login.php'] = ['text' => 'Accedi', 'class' => ''];
        $currentAreaPage = 'login.php';
    }


    // Menu dinamico
    $menuItems = '';
    foreach ($pages as $page => $data) {
        // Determina se questa è la pagina corrente
        $isCurrentPage = false;
        
        if ($page === $currentPage) {
            $isCurrentPage = true;
        }
        // Se siamo su login.php o admin.php dopo il login, evidenzia l'Area Personale appropriata
        else if (($currentPage === 'login.php' || $currentPage === 'admin.php') && $page === $currentAreaPage) {
            $isCurrentPage = true;
        }
        
        if ($isCurrentPage) {
            // Elemento corrente
            $menuItems .= '<li aria-current="page" id="current"' .
                (!empty($data['class']) ? ' class="' . $data['class'] . '"' : '') .
                (!empty($data['lang']) ? ' lang="' . $data['lang'] . '"' : '') .
                '>' . $data['text'] . '</li>';
        } else {
            // Altri elementi
            $menuItems .= '<li><a href="' . $page . '"' .
                (!empty($data['lang']) ? ' lang="' . $data['lang'] . '"' : '') .
                '>' . $data['text'] . '</a></li>';
        }
    }

    $headerTemplate = str_replace(
        ['%%DESCRIPTION%%', '%%KEYWORDS%%'],
        [
            htmlspecialchars($metaDescription, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
            htmlspecialchars($metaKeywords,    ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
        ],
        $headerTemplate
    );
    
    // Sostituzione menu nel template
    $headerContent = preg_replace(
        '/<ul id="vertical-menu" data-open="false">.*?<\/ul>/s',
        '<ul id="vertical-menu" data-open="false">' . $menuItems . '</ul>',
        $headerTemplate
    );

    echo $headerContent;
?>