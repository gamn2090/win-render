import './bootstrap';

import Alpine from 'alpinejs';

import 'preline'
window.Alpine = Alpine;

Alpine.start();

window.createStarElement = function (rating){
    let el = `<div class="flex items-center mt-1">`;
    for(let i = 0; i < 5; ++i){
        if(i < Math.round(rating)){
            el += `
            <svg class="flex-shrink-0 size-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>`;
        }
        else {
            el += `
            <svg class="flex-shrink-0 size-5 text-gray-300" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
            </svg>`;
        }
    }
    el += "</div>";
    return el;
}

window.capTextLength = function(text, len){
    if(text.length > len){
        return text.substring(0,len) + "...";
    }
    return text;
}
