
function d(data)
{
    console.log(data);
}

import { FadeGallery } from './FadeGallery.js?100';
import { SliderGallery } from './SliderGallery.js?100';
import { ZoomGallery } from './ZoomGallery.js?100';
import { Diaporama } from './Diaporama.js?100';
import { Wishlist } from './Wishlist.js?100';
import { Navigation } from './Navigation.js?100';
import { ColumnManager } from './ColumnManager.js?100';
import { AudioPlayer } from './AudioPlayer.js?100';


let navigation = new Navigation({
    'bodySelector'      :   '.layout-navigation-mobile',
    'buttonSelector'    :   '.layout-navigation-mobile button'    
});

let fadeGallery     =   new FadeGallery();

let sliderGallery   =   new SliderGallery();   

let wishlist        =   new Wishlist({
    'addButtonSelector'     :   '[data-button="add-wishlist"]',
    'removeButtonSelector'  :   '[data-button="remove-wishlist"]',
    'printButtonSelector'   :   '[data-button="print-wishlist"]'
});

let diaporama       =   new Diaporama({
    'gallerySelector'   :   '.gallery [data-sub-type="diaporama"]',
    'photosSelector'    :   '.photo',
    'screenSelector'    :   '.diaporama-screen',
    'galleryUrl'        :    SITE_URL + 'uploads/galleries/'
});


let audioPlayer = new AudioPlayer({
    'containerSelector' :   '.layout-conference',
    'viewerSelector'    :   '.layout-viewer',
    'photoClickHandlerCallback' : function(state){
        let conferences = document.querySelector('.layout-conferences');
        if(state === 'show') {
            conferences.classList.add('viewer');
        }
        if(state === 'hide') {
            conferences.classList.remove('viewer');
        }
        return true;
    }
});


let exposantsColumnManager = new ColumnManager({
    'rootSelector'              :   '.layout-root',
    'panelSelector'             :   '.layout-exposants .layout-panel',
    'slugsSelector'             :   '[data-type="slug"]',    
    'slugsContainerSelector'    :   '[data-type="slugs-list"]',    
    'categorySlugsSelector'     :   '[data-type="category-slug"]',
    'categoriesContainerSelector' : '[data-type="category-slugs-list"]',
    'singleContainerSelector'   :   '[data-type="single-content"]'
});


let programmesColumnManager = new ColumnManager({
    'rootSelector'              :   '.layout-root',
    'panelSelector'             :   '.layout-programmation .layout-panel',
    'slugsSelector'             :   '[data-type="slug"]',
    'slugsContainerSelector'    :   '[data-type="slugs-list"]',
    'categorySlugsSelector'     :   '[data-type="category-slug"]',
    'categoriesContainerSelector' : '[data-type="category-slugs-list"]',
    'singleContainerSelector'   :   '[data-type="single-content"]'
});


window.addEventListener('load', function(){

    diaporama.load();
    audioPlayer.load();
    wishlist.load();
    navigation.load();
    exposantsColumnManager.load();
    programmesColumnManager.load();

    let domZoomGallery = document.querySelector('.gallery div[data-type="zoom"]');

    if(domZoomGallery) {
        let params = {
            'sliderSelector'    : '.gallery div[data-type="zoom"]',
            'slidesSelector'    : '.photo',
            'opacityIncrement'  : '-0.1',
            'slow'              : 15,
            'pause'             : 5000,
            'scaleIncrement'    : '0.0005',
            'maxScale'          : '1.1'
        };
        let zoomGallery  =  new ZoomGallery(params);
        zoomGallery.load();
    }
    setSearch();

}, false);


function setSearch()
{
    let searchForm = document.querySelector('.layout-header .layout-search form');
    let searchInput = document.querySelector('.layout-header .layout-search input');
    if(!searchForm) return;
    searchForm.reset();
    searchInput.value = '';
    searchInput.addEventListener('click', function(){
        searchInput.value = '';
    }, false);
}
  