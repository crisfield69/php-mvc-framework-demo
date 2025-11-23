

export class SliderGallery
{
    constructor()
    {
        let gallery = document.querySelector('.gallery div[data-type="slider"]');
        if(!gallery) return; 

        this.gallery        =   gallery;
        this.isPLaying      =   false;
        this.direction      =   1;
        this.pause          =   1;
        this.incrementPart  =   10;
        this.timeout        =   null;
        this.interval       =   null;
        this.autoPlay       =   true;

        this.setLayout();
        this.setBindings();
        this.setEvents();
    }
    
    
    setLayout()
    {
        this.container = document.createElement('div');
        this.container.classList.add('container');
        this.gallery.appendChild(this.container);
        this.photos = [];
        let photos = Array.from(this.gallery.querySelectorAll('.photo'));
        let zIndex = photos.length;
        photos.map(function(photo){
            photo.style.zIndex = zIndex;
            photo.style.width = this.gallery.offsetWidth + 'px';
            this.container.appendChild(photo);
            this.photos.push(photo);
            zIndex--;
        }.bind(this));
        this.arrowLeft = document.createElement('div');
        this.arrowLeft.classList.add('arrow-left');
        this.arrowRight = document.createElement('div');
        this.arrowRight.classList.add('arrow-right');
        this.gallery.appendChild(this.arrowLeft);
        this.gallery.appendChild(this.arrowRight); 
        this.container.style.width = this.photos.length * this.gallery.offsetWidth + 'px';
        this.containerLimitLeft = this.gallery.offsetWidth - this.container.offsetWidth;
    }


    setEvents()
    {
        this.arrowLeft.addEventListener('click', this.arrowLeftClickHandler, false);
        this.arrowRight.addEventListener('click', this.arrowRightClickHandler, false);

        window.addEventListener('resize', function(){
            setTimeout(this.resizeLayout, 1);
        }.bind(this), false);

        if(this.autoPlay)
            this.interval = setInterval(this.play, 10000);
        
        window.addEventListener('focus', function(){
            this.reset();
        }.bind(this), false);         
    }

    
    reset()
    {
        if(this.autoPlay) {
            clearInterval(this.interval);
            clearTimeout(this.timeout);
            this.container.style.transform = 'translateX(0px)';
            this.interval = setInterval(this.play, 10000);
        }
    }


    setBindings()
    {
        this.arrowLeftClickHandler = this.arrowLeftClickHandler.bind(this);
        this.arrowRightClickHandler = this.arrowRightClickHandler.bind(this);
        this.resizeLayout = this.resizeLayout.bind(this);
        this.slide = this.slide.bind(this);
        this.play = this.play.bind(this);
    }
 

    arrowLeftClickHandler()
    {
        if(this.isPLaying === true) return;
        let targetLeft = this.getCurrentTranslate(this.container) + this.gallery.offsetWidth;
        this.slide(targetLeft);
    }


    arrowRightClickHandler()
    {
        if(this.isPLaying === true) return;
        let targetLeft = this.getCurrentTranslate(this.container) - this.gallery.offsetWidth;        
        this.slide(targetLeft);
    }


    resizeLayout()
    {
        this.container.style.width = this.photos.length * this.gallery.offsetWidth + 'px';
        this.photos.map(function(photo) {
            photo.style.width = this.gallery.offsetWidth + 'px';
        }.bind(this));
        this.containerLimitLeft = this.gallery.offsetWidth - this.container.offsetWidth;
        this.container.style.left = '0px';
        this.container.style.transform = 'translateX(0px)';
    }


    slide(targetLeft)
    {   
        if(targetLeft<this.containerLimitLeft || targetLeft>0) {
            return;
        }
        let increment = Math.floor((targetLeft - this.getCurrentTranslate(this.container)) / this.incrementPart);
        let nextTranslate = this.getCurrentTranslate(this.container) + increment;
        if(
            (increment>0 && nextTranslate<=targetLeft) ||
            (increment<0 && nextTranslate>=targetLeft)
        ){
            this.isPLaying = true;
            this.container.style.transform = 'translateX('+nextTranslate+'px)';
            this.timeout = setTimeout(function() {
                this.slide(targetLeft);
            }.bind(this), this.pause);
        }
        else{
            this.container.style.transform = 'translateX('+targetLeft+'px)';
            this.isPLaying = false;
            return;
        }
    }


    play() 
    {
        if(this.getCurrentTranslate(this.container)>=0) {
            this.direction = -1;
        }
        else if(this.getCurrentTranslate(this.container)<=this.containerLimitLeft){
            this.direction = 1;
        }        
        let galleryOffsetWidth = this.direction * this.gallery.offsetWidth;
        let targetLeft = this.getCurrentTranslate(this.container) + galleryOffsetWidth;        
        this.slide(targetLeft);
    }
    
    
    getCurrentTranslate(element)
    {
        let transform = element.style.transform;        
        if(!transform || transform=='' || transform=='undefined') return 0;
        let start = transform.indexOf("translateX(") + 11;
        let end = transform.indexOf("px)");
        if(start == -1 || end == -1) return 0;
        return parseFloat(transform.substring(start, end));
    } 

}