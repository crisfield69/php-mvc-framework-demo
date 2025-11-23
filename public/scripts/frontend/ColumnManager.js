

export class ColumnManager
{
    constructor(params)
    {   
        this.rootSelector = params.rootSelector;
        this.panelSelector = params.panelSelector;
        this.slugsSelector = params.slugsSelector;
        this.slugsContainerSelector = params.slugsContainerSelector;

        this.categorySlugsSelector = params.categorySlugsSelector;
        this.categoriesContainerSelector = params.categoriesContainerSelector;
        
        this.singleContainerSelector = params.singleContainerSelector;
        this.footerHeight = 60;
        this.limitWidth = 1024;
    }

    load()
    {
        this.panel = document.querySelector(this.panelSelector);
        if(!this.panel) return;
        this.root = document.querySelector(this.rootSelector);
        this.columns = Array.from(document.querySelectorAll(this.panelSelector+'>div'));
        this.columns.pop();
        this.slugs = Array.from(document.querySelectorAll(this.slugsSelector));
        this.categorySlugs = Array.from(document.querySelectorAll(this.categorySlugsSelector));
        this.slugsContainer = document.querySelector(this.slugsContainerSelector);
        this.singleContainer = document.querySelector(this.singleContainerSelector);
        this.categoriesContainer = document.querySelector(this.categoriesContainerSelector);

        this.setBidings();
        this.setEvents();
        this.setVerticalPosition();        
        this.resize();
        this.setCategoriesVerticalPosition();
        window.addEventListener('resize', this.resize, false);
    }

    setBidings()
    {
        this.resize = this.resize.bind(this);
        this.getOffset = this.getOffset.bind(this);
        this.getCurrentUrl = this.getCurrentUrl.bind(this);
        this.getCurrentUrlSlug = this.getCurrentUrlSlug.bind(this);
        this.getSlugs = this.getSlugs.bind(this);
        this.setVerticalPosition = this.setVerticalPosition.bind(this);
        this.isMobile = this.isMobile.bind(this);
        this.getCategoriesSlugs = this.getCategoriesSlugs.bind(this);
        this.setCategoriesVerticalPosition = this.setCategoriesVerticalPosition.bind(this);
    }

    setEvents()
    {
        window.addEventListener('resize', this.resize, false);
        window.addEventListener('scroll', this.resize, false);
    }

    resize()
    {
        if(this.isMobile()) {
            this.panel.classList.add('mobile');
            /*
            this.columns.forEach(function(column) {
                column.style.height = 'auto !important';
            }.bind(this));
            */
        }
        else{
            this.panel.classList.remove('mobile');
            this.columns.forEach(function(column) {
                let offsetTop = this.getOffset(column).top;
                column.style.height = (
                    window.innerHeight
                    - offsetTop
                    - 20 - this.footerHeight
                    + window.scrollY
                ) + 'px';
            }.bind(this));
        }
    }

    isMobile()
    {
        if(this.root.offsetWidth<=this.limitWidth) {
            return true;
        }
        return false;
    }

    setVerticalPosition()
    {
        if(this.isMobile()) {
            //setTimeout(function(){
                this.getCategoriesSlugs().forEach(function(slug){
                    if(this.getCurrentUrlSlug() === slug) {
                        let targetElement = this.slugsContainer;
                        window.scrollTo({
                            top: targetElement.offsetTop,
                            left: 0,
                            behavior: 'smooth'
                        });
                    }

                }.bind(this));

                this.getSlugs().forEach(function(slug) {
                    if(this.getCurrentUrlSlug() === slug) {
                        let targetElement = this.singleContainer;
                        window.scrollTo({
                            top: targetElement.offsetTop,
                            left: 0,
                            behavior: 'smooth'
                        });
                    }
                }.bind(this));

            //}.bind(this), 100); 
            
        }
        else {
            this.getSlugs().forEach(function(slug) {
                if(this.getCurrentUrlSlug() === slug) {
                    let targetElement = document.querySelector('[data-slug="'+slug+'"]');
                    targetElement.classList.add('selected');
                    this.slugsContainer.scrollTo({
                        top: targetElement.offsetTop,
                        left: 0,
                        behavior: 'smooth'
                    });
                }
            }.bind(this));
        }        
    }


    setCategoriesVerticalPosition()
    {
        let targetElement = document.querySelector('[data-type="category-slug"].selected');
        if(targetElement) 
        this.categoriesContainer.scrollTo({
            top: targetElement.offsetTop,
            left: 0           
        }); 
    }
    

    getOffset(column) 
    {
        let boundingClientRect = column.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        return { 
            top: boundingClientRect.top + scrollTop, 
            left: boundingClientRect.left + scrollLeft 
        }
    }

    getCurrentUrl()
    {
        return window.location.href;
    }
    
    getCurrentUrlSlug()
    {
        let array = this.getCurrentUrl().split('/');
        if(array.length) {
            return array[array.length-1];
        }
        return null;
    }

    getSlugs()
    {
        let slugs = [];
        this.slugs.forEach(function(slug) {
            slugs.push(slug.getAttribute('data-slug'));
        });
        return slugs;
    }
    
    getCategoriesSlugs()
    {
        let categorySlugs = [];
        this.categorySlugs.forEach(function(slug) {
            categorySlugs.push(slug.getAttribute('data-category-slug'));
        });
        return categorySlugs;
    }

}