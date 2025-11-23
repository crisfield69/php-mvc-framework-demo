

export class FadeGallery
{
    constructor()
    {
        let gallery = document.querySelector('.gallery div[data-type="fading"]');
        if(!gallery) return;
        this.gallery = gallery;

        this.opacityIncrement = 0.05;
        this.timeout = 50;
        this.pause = 6000;
        this.setLayout();
        this.play();
    }


    setLayout()
    {
        this.photos = Array.from(this.gallery.querySelectorAll('.photo'));
        this.zIndexMax = this.photos.length;
        let zIndex = this.zIndexMax;
        this.photos.map(function(photo){
            photo.style.zIndex = zIndex; 
            photo.style.opacity = 1;
            zIndex--;
        });
    }

    
    play()
    {
        let currentTopPhoto = this.getTopPhoto();
        this.fade(currentTopPhoto);
    }


    fade(photo)
    {
        let nextOpacity = parseFloat(photo.style.opacity) - this.opacityIncrement;
        if(nextOpacity>=0) {
            photo.style.opacity = nextOpacity;
            setTimeout(function(){
                this.fade(photo);
            }.bind(this), this.timeout);
        }
        else{
            this.swapPhotos();
        }
    }


    swapPhotos()
    {
        let currentTopPhoto = this.getTopPhoto();
        this.photos.map(function(photo){
            if(photo !== currentTopPhoto) {
                photo.style.zIndex = parseFloat(photo.style.zIndex) + 1;
            }
        });
        currentTopPhoto.style.zIndex = 1;
        currentTopPhoto.style.opacity = 1;
        let nextTopPhoto = this.getTopPhoto();
        setTimeout(function(){
            this.fade(nextTopPhoto);
        }.bind(this), this.pause);
    }


    getTopPhoto()
    {
        let topPhoto;
        this.photos.map(function(photo){
            if(parseInt(photo.style.zIndex) === this.zIndexMax) {
                topPhoto = photo;
            }
        }.bind(this));
        return topPhoto;
    }

    
    getBottomPhoto()
    {
        let bottomPhoto;
        this.photos.map(function(photo){
            if(photo.style.zIndex === 1) {
                bottomPhoto = photo;
            }
        });
        return bottomPhoto;
    }

}