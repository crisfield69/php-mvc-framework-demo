

export class GalleryManager
{

    constructor()
    {
        let galleryUpdate = document.querySelector('.layout-gallery');
        if(!galleryUpdate) return;  


        this.gallery = document.querySelector('.gallery'); 
        this.postInsertGalleryUrl = SITE_URL + 'admin/galeries/insert';                
        this.postUpdateGalleryUrl = SITE_URL + 'admin/galeries/update/';        
        
        this.backwardButtons = Array.from(document.querySelectorAll('.photo>div>p:nth-child(1)'));
        this.forwardButtons = Array.from(document.querySelectorAll('.photo>div>p:nth-child(2)'));
        this.deleteButtons = Array.from(document.querySelectorAll('.photo>div>p:nth-child(3)'));
        this.submitbutton = document.querySelector('button[data-type="submit"]');
        this.form = document.querySelector('form');
        this.timer = document.querySelector('.layout-timer');
        this.setBindings();
        this.setEvents();
    }


    setEvents()
    {
        this.backwardButtons.map(function(button) {
            button.addEventListener('click', this.backwardButtonClickHandler, false);
        }.bind(this));
        this.forwardButtons.map(function(button) {
            button.addEventListener('click', this.forwardButtonClickHandler, false);
        }.bind(this));
        this.deleteButtons.map(function(button) {
            button.addEventListener('click', this.deleteButtonClickHandler, false);
        }.bind(this));
        this.submitbutton.addEventListener('click', this.submitbuttonClickHandler, false);
    }


    setBindings()
    {
        this.backwardButtonClickHandler = this.backwardButtonClickHandler.bind(this);
        this.forwardButtonClickHandler = this.forwardButtonClickHandler.bind(this);
        this.deleteButtonClickHandler = this.deleteButtonClickHandler.bind(this);
        this.submitbuttonClickHandler = this.submitbuttonClickHandler.bind(this);
        this.setTimer = this.setTimer.bind(this);
    }


    backwardButtonClickHandler (event) 
    {
        let button = event.target;
        let id = button.getAttribute('data-id');
        let photo = document.querySelector('.photo[data-id="'+id+'"]');
        let nextPhoto = this.getNextPhoto(photo);        
        let previousPhoto = this.getPreviousPhoto(photo);
        if(previousPhoto !== null) {
            this.gallery.insertBefore(previousPhoto, nextPhoto);
            this.updatePhotosOrders();
        }
    }


    forwardButtonClickHandler(event) 
    {
        let button = event.target;
        let id = button.getAttribute('data-id');
        let photo = document.querySelector('.photo[data-id="'+id+'"]');
        let nextPhoto = this.getNextPhoto(photo);
        if(nextPhoto !== null) {
            this.gallery.insertBefore(nextPhoto, photo);
            this.updatePhotosOrders();
        } 
    }


    deleteButtonClickHandler(event)
    {
        let button = event.target;
        let id = button.getAttribute('data-id');
        let photo = document.querySelector('.photo[data-id="'+id+'"]');
        this.gallery.removeChild(photo);
        this.updatePhotosOrders();
    }


    getNextPhoto(element){
        if(element.nextSibling === null) return null;
        if( element.nextSibling.nodeType === 1 ){
            if(element.nextSibling.classList.contains('photo')) {
                return element.nextSibling;
            }
        }        
        return this.getNextPhoto(element.nextSibling);
    }

    
    getPreviousPhoto(element){        
        if(element.previousSibling === null) return null;
        if( element.previousSibling.nodeType === 1 ){
            if(element.previousSibling.classList.contains('photo')) {
                return element.previousSibling;
            }
        }        
        return this.getPreviousPhoto(element.previousSibling);
    }


    updatePhotosOrders()
    {
        let photos = Array.from(document.querySelectorAll('.gallery .photo'));
        let order = 1;
        photos.map(function(photo){            
            Array.from(photo.querySelectorAll('div>p')).map(function(p){
                p.setAttribute('data-order', order);
            });
            Array.from(photo.querySelectorAll('div>input[name="ordres[]"]')).map(function(input){
                input.value = order;
            });
            order++;
        });
    }


    submitbuttonClickHandler(event) 
    {
        let domNom          =   document.querySelector('input[name="nom"]');
        let domType         =   document.querySelector('select[name="type"]');
        let domHauteur      =   document.querySelector('input[name="hauteur"]');

        let domColonnes     =   document.querySelector('select[name="colonnes"]');
        let domMarges       =   document.querySelector('select[name="marges"]');
        
        let domTitres       =   document.querySelectorAll('input[data-type="title"]');
        let domTextes       =   document.querySelectorAll('textarea[data-type="text"]');
        let domLiens        =   document.querySelectorAll('input[data-type="link"]');
        let domOrdres       =   document.querySelectorAll('input[data-type="order"]');
        let domIds          =   document.querySelectorAll('input[data-type="id"]');
        let domFiles        =   document.querySelector('input[type="file"]');
        let domGalleryId    =   document.querySelector('input[name="gallery_id"]');
        
        let nom = domNom.value;
        if(nom === '') return;

        this.setTimer();

        let formData = new FormData(); 
        formData.append('nom', nom);

      
        if(domColonnes !== null) {
            formData.append('colonnes', domColonnes.value);
        }

        if(domMarges !== null) {
            formData.append('marges', domMarges.value);
        }

        if(domType !== null) {
            formData.append('type', domType.value);
        }

        if(domHauteur !== null) {
            formData.append('hauteur', domHauteur.value);
        }
        
        if(domFiles !== null){
            for(let file of domFiles.files) {
                formData.append('photos[]', file, file.name);
            }
        }

        if(domTitres !== null){
            Array.from(domTitres).map(function(input){
                formData.append(input.name, input.value);
            });
        }
        
        if(domTextes !== null){
            Array.from(domTextes).map(function(textarea){
                formData.append(textarea.name, textarea.value);    
            });
        }

        if(domLiens !== null){
            Array.from(domLiens).map(function(input){
                formData.append(input.name, input.value);
            });
        }

        if(domOrdres !== null){
            Array.from(domOrdres).map(function(input){
                formData.append(input.name, input.value);
            });
        }

        if(domIds !== null){
            Array.from(domIds).map(function(input){
                formData.append(input.name, input.value);
            });
        }

        let galleryId = domGalleryId.value;
        let action = 'insert';
        let url = this.postInsertGalleryUrl;

        if(galleryId !== '') {
            action = 'update';
            url = this.postUpdateGalleryUrl + galleryId;
            formData.append('gallery_id', galleryId);
        }

        formData.append('action', action);
        fetch(url, {
            method 	: 'POST',
            body 	: formData
        })
        .then( function(response){return response.json()})
        .then( function(json){  
            console.log(json);
            window.location.href = SITE_URL + json.redirectUrl;
        }.bind(this));    
    }   

    setTimer()
    {
        window.scrollTo(0,0);
        this.timer.classList.add('show');
        document.body.style.overflow = 'hidden';        
    }

}