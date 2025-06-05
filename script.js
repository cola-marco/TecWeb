function menuToggle(){
    const menu = document.getElementById('vertical-menu');
    const closeIcon = document.getElementById('close-icon');
    const hambIcon = document.getElementById('hamb-icon');
    const menu_attribute = menu.getAttribute('data-open');
    const closeIcon_attribute = closeIcon.getAttribute('data-open');
    const hambIcon_attribute = hambIcon.getAttribute('data-open');
    if(menu_attribute == "true" && closeIcon_attribute == "true" && hambIcon_attribute == "true"){
        menu.setAttribute('data-open', "false");
        closeIcon.setAttribute('data-open', "false");
        hambIcon.setAttribute('data-open', "false");
    }
    else{
        menu.setAttribute('data-open', "true");
        closeIcon.setAttribute('data-open', "true");
        hambIcon.setAttribute('data-open', "true");
    }
}