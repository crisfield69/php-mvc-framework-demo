

export class ZoomGallery
{
    constructor(params) 
    {        
        this.sliderSelector     = params['sliderSelector'];
        this.slidesSelector     = params['slidesSelector'];
        this.opacityIncrement   = parseFloat(params['opacityIncrement']);
        this.slow               = parseFloat(params['slow']);
        this.pause              = parseFloat(params['pause']);
        this.scaleIncrement     = parseFloat(params['scaleIncrement']);
        this.maxScale           = parseFloat(params['maxScale']);   

        this.timeout            = null;

        this.zMax;
        this.zMin;
        this.slideTop;
        this.slideBottom;

        this.fade = this.fade.bind(this);
        this.load = this.load.bind(this);
        this.scale = this.scale.bind(this); 
    }

    load() 
    {
        this.slider = document.querySelector(this.sliderSelector);
        this.slides = this.slider.querySelectorAll(this.slidesSelector);
        for (let i = 0; i < this.slides.length; i++) {
            let slide = this.slides[i];
            this.setZindex(slide, this.slides.length - i);
            this.setOpacity(slide, 1);
            this.setScale(slide, 1);
        }
        this.zMax = this.slides.length;
        this.zMin = 1;
        this.play();
    }

    play() 
    {
        let slides = this.getMaxMin();
        this.slideTop = slides.zmax;
        this.slideBottom = slides.zmin;
        this.scale();
    }

    scale()
    {
        let currentScale = this.getScale(this.slideTop);
        let newScale = currentScale + this.scaleIncrement;
        if(newScale < this.maxScale){
            this.setScale(this.slideTop, newScale);
            setTimeout(this.scale, this.slow);
        }
        else{
            this.setScale(this.slideTop, this.maxScale);
            this.fade();
        }
    }

    fade() 
    {
        let currentOpacity = this.getOpacity(this.slideTop);
        let newOpacity = currentOpacity + this.opacityIncrement;
        if (newOpacity <= 0) {
            this.setOpacity(this.slideTop, 0);
            clearTimeout(this.timeout);
            this.sort();
        }
        else {
            this.setOpacity(this.slideTop, newOpacity);
            this.timeout = setTimeout(this.fade, this.slow);
        }
    }
    
    sort() 
    {
        for(let i = 0; i < this.slides.length; i++){
            let slide = this.slides[i];
            let zindex = this.getZindex(slide);
            if(zindex !== this.zMax) {
                this.setZindex(slide, zindex + 1);
            }
        }
        this.setZindex(this.slideTop, this.zMin);
        let slides = this.getMaxMin();
        this.slideTop = slides.zmax;
        this.slideBottom = slides.zmin;
        this.setOpacity(this.slideBottom, 1);
        this.setScale(this.slideBottom, 1);
        this.scale();
    }  

    getMaxMin() 
    {
        let slides = {};
        for (let i = 0; i < this.slides.length; i++) {
            let slide = this.slides[i];
            if (this.getZindex(slide) === this.zMax) {
                slides.zmax = slide;
            }
            if (this.getZindex(slide) === this.zMin) {
                slides.zmin = slide;
            }
        }
        return slides;
    }

    getZindex(slide) 
    {
        return parseInt(slide.style.zIndex);
    }

    setZindex(slide, zindex) 
    {
        slide.style.zIndex = zindex;
    }

    setOpacity(slide, opacity) 
    {
        slide.style.opacity = opacity;
    }

    getOpacity(slide) 
    {
        return parseFloat(slide.style.opacity);
    }

    setScale(slide, scale){
        let img = slide.querySelector('img');
        img.style.transform = 'scale('+scale+')';
    }
    
    getScale(slide) {

        let img = slide.querySelector('img');
        let transform = img.style.transform;

        let index = transform.indexOf('scale(') + 6;
        let string = transform.substring(index, transform.length);
        index = string.indexOf(')');
        let scale = string.substring(0, index);
        return parseFloat(scale);
    }   

}