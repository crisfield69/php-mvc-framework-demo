
export class Diaporama {

    constructor(parameters)
    {
        this.gallerySelector = parameters.gallerySelector;
        this.photosSelector = parameters.photosSelector; 
        this.diaporamaScreenSelector = parameters.screenSelector;        
        this.galleryUrl = parameters.galleryUrl;
        this.diaporamaPhotos = [];
    }


    load()
    {
        this.gallery = document.querySelector(this.gallerySelector);
        if(!this.gallery) return;        
        this.sourcePhotos = Array.from(this.gallery.querySelectorAll(this.photosSelector));                
        this.setDiaporamaScreen();
        this.setBindings();
        this.setEvents();
        this.hide();
    }


    setEvents()
    {
        window.addEventListener('resize', this.resize, false);
        this.sourcePhotos.map(function(photo) {
            photo.addEventListener('click', this.photoClickHandler, false);
        }.bind(this));
        this.arrowLeft.addEventListener('click', this.arrowLeftClickHandler, false);
        this.arrowRight.addEventListener('click', this.arrowRightClickHandler, false);
        this.closeButton.addEventListener('click', this.close, false);
    }


    setBindings()
    {
        this.photoClickHandler = this.photoClickHandler.bind(this);
        this.setDiaporamaScreen = this.setDiaporamaScreen.bind(this);
        this.setArrows = this.setArrows.bind(this);
        this.resize = this.resize.bind(this);
        this.setEvents = this.setEvents.bind(this);
        this.arrowLeftClickHandler = this.arrowLeftClickHandler.bind(this);
        this.arrowRightClickHandler = this.arrowRightClickHandler.bind(this);
        this.slide = this.slide.bind(this);
        this.close = this.close.bind(this);
        this.show = this.show.bind(this);
        this.hide = this.hide.bind(this);
        this.setContainerLeft = this.setContainerLeft.bind(this);
    }


    photoClickHandler(event)
    {
        let photo = event.currentTarget;
        let filename = photo.getAttribute('data-photo-filename');
        //this.show();
        this.diaporamaScreen.style.display = 'block';        
        setTimeout(function(){
            this.resize();
            this.setContainerLeft(filename);
            this.diaporamaScreen.style.opacity = 1;
        }.bind(this), 200);
    }


    arrowLeftClickHandler()
    {
        let limit = this.container.offsetLeft + this.frame.offsetWidth;
        if(limit>0) return;
        this.slide(1, limit);
    }


    arrowRightClickHandler()
    {        
        let limit = this.container.offsetLeft - this.frame.offsetWidth;
        if(limit<(this.frame.offsetWidth - this.container.offsetWidth)) return;
        this.slide(-1, limit);
    }


    slide(dir, limit)
    {
        let nextLeft = this.container.offsetLeft + (dir * 100);
        if(dir<0) {
            if(nextLeft>=limit) {
                this.container.style.left = nextLeft + 'px';
                setTimeout(function(){this.slide(dir, limit)}.bind(this), 1);
            }
            else{
                this.container.style.left = limit + 'px';
            }
        }
        if(dir>0) {
            if(nextLeft<=limit) {
                this.container.style.left = nextLeft + 'px';
                setTimeout(function(){this.slide(dir, limit)}.bind(this), 1);
            }
            else{
                this.container.style.left = limit + 'px';
            }
        }
    }


    setDiaporamaScreen() 
    {
        this.diaporamaScreen = document.querySelector(this.diaporamaScreenSelector);
        if(!this.diaporamaScreen) {
            let screen = document.createElement('div');
            screen.classList.add(this.diaporamaScreenSelector.substring(1));
            this.diaporamaScreen = screen;            
            document.body.appendChild(this.diaporamaScreen);
            this.frame = document.createElement('div');
            this.frame.classList.add('frame');
            this.container = document.createElement('div');
            this.container.classList.add('container');
            this.frame.appendChild(this.container);
            this.diaporamaScreen.appendChild(this.frame);
        }
        this.totalWidth = 0;
        this.sourcePhotos.map(function(photo){
            this.setDiaporamaScreenPhoto(photo);
        }.bind(this));
        this.container.style.width = this.totalWidth + 'px';
        this.setArrows();
        this.setCloseButton();        
    }


    setDiaporamaScreenPhoto(photo)
    {
        let filename = photo.getAttribute('data-photo-filename');
        let galleryId = photo.getAttribute('data-gallery-id');
        let width = photo.getAttribute('data-photo-width');
        let height = photo.getAttribute('data-photo-height');
        photo = document.createElement('img');
        photo.setAttribute('src', this.galleryUrl + galleryId + '/xlarge/' + filename);
        photo.setAttribute('data-width', width);
        photo.setAttribute('data-height', height);
        photo.setAttribute('data-filename', filename);
        this.container.appendChild(photo);
        this.diaporamaPhotos.push(photo);
        this.totalWidth += photo.offsetWidth;
    }


    setArrows()
    {   
        this.arrowLeft = document.createElement('p');
        this.arrowLeft.classList.add('arrow-left');
        this.arrowRight = document.createElement('p');
        this.arrowRight.classList.add('arrow-right');
        this.diaporamaScreen.appendChild(this.arrowLeft);
        this.diaporamaScreen.appendChild(this.arrowRight);
    }


    setCloseButton()
    {   
        this.closeButton = document.createElement('p');
        this.closeButton.classList.add('close');
        this.diaporamaScreen.appendChild(this.closeButton);
    }


    resize()
    {       
        let cw = 0;
        this.diaporamaPhotos.map(function(photo) {
            let w0 = parseFloat(photo.getAttribute('data-width'));
            let h0 = parseFloat(photo.getAttribute('data-height'));
            let r = h0/w0;
            let fw = parseFloat(this.frame.offsetWidth);
            let fh = parseFloat(this.frame.offsetHeight);
            let w = fw;
            if(w>w0){
                w = w0;
            }
            let h = w * r;
            if(h>fh){
                h = fh;
            }
            w = h/r;
            let m = (fw - w) / 2;
            photo.style.width = w + 'px';
            photo.style.height = h + 'px';
            photo.style.marginLeft = m + 'px';
            photo.style.marginRight = m + 'px';
            cw += w + (2 * m);
        }.bind(this));
        this.container.style.left = '0px';
        this.container.style.width = cw + 'px';
    }


    close()
    {
       this.hide();
    }


    hide() 
    {
        this.diaporamaScreen.style.display = 'none';
        this.diaporamaScreen.style.opacity = 0;
    }


    show()
    {
        this.diaporamaScreen.style.display = 'block';
        this.diaporamaScreen.style.opacity = 1;
        /*
        this.diaporamaScreen.style.display = 'block';
        let i = 0.01;        
        let o = parseFloat(this.diaporamaScreen.style.opacity);
        if(o + i < 1 ) {
            o += i;
            this.diaporamaScreen.style.opacity = o;
            setTimeout(this.show, 1);
        }
        else{
            this.diaporamaScreen.style.opacity = 1;
        }
        */
    }


    setContainerLeft(filename)
    {
        let photo = this.container.querySelector('[data-filename="'+filename+'"]');
        let pl = parseFloat(photo.offsetLeft);
        let pm = parseFloat(photo.style.marginLeft);
        let cl = (-1 * pl) + pm;
        this.container.style.left = cl + 'px';
    }




}

