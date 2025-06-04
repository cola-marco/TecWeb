<?php
    $currentPage = basename($_SERVER['PHP_SELF']);
    $headerTemplate = file_get_contents('./html/templates/header.html');

    // mappatura delle pagine
    $pages = [
        'index.php' => ['text' => 'Home', 'class' => ''],
        'catalogo.php' => ['text' => 'Catalogo', 'class' => ''],
        'login.php' => ['text' => 'Login', 'class' => '', 'lang' => 'en']
    ];

    // Menu dinamico
    $menuItems = '';
    foreach ($pages as $page => $data) {
        if ($page === $currentPage) {
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

    // Sostituzione menu nel template
    $headerContent = preg_replace(
        '/<ul id="vertical-menu" data-open="false">.*?<\/ul>/s',
        '<ul id="vertical-menu" data-open="false">' . $menuItems . '</ul>',
        $headerTemplate
    );

    echo $headerContent;
?>