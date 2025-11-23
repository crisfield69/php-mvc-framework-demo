
function d(data)
{
    console.log(data);
}


import { Categories } from './Categories.js?2';
import { PageBuilder } from './PageBuiler.js?2';
import { GalleryManager } from './GalleryManager.js?2';
import { OrderManager } from './OrderManager.js?2';
import { ApiImports } from './ApiImports.js?2';


window.addEventListener('load', function(){
    
    let categories = new Categories();
    let pageBuilder = new PageBuilder();  
    let galleryManager = new GalleryManager();
    let apiImports = new ApiImports();

    let programmationsOrderManager = new OrderManager({
        'selectsSelector'   :   '.layout-programmations [data-type="order"]',
        'fetchUrl'          :   SITE_URL + 'admin/programmation/update-order'
    });

    let pageOrderManager = new OrderManager({
        'selectsSelector'   :   '.layout-page .build-bloc [data-type="order"]',
        'fetchUrl'          :   SITE_URL + 'admin/pages/update-order'
    });

    let conferencesOrdreManager = new OrderManager({
        'selectsSelector'   :   '.layout-conferences [data-type="order"]',
        'fetchUrl'          :   SITE_URL + 'admin/conferences/update-order'
    });

}, false);


