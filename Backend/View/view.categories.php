
<div class="layout-categories">
    <h1>Catégories (exposants)</h1>
    
    <div class="layout-list">
        <?= $categories ?>    
    </div>

</div>

<script>
    window.addEventListener('load', function(){
        let checkboxes = Array.from(document.querySelectorAll('.checkbox'));
        if(!checkboxes.length) return;
        checkboxes.forEach(function(checkbox, index){
            checkbox.addEventListener('click', checkboxClickHandler, false);
        });
    }, false);

    function checkboxClickHandler(event)
    {
        let checkbox = event.target;
        let categotyId = checkbox.getAttribute('data-category-id');

        let formData = new FormData();    
        formData.append('categoryId', categotyId);
        
        fetch( SITE_URL + 'admin/categories/lock', {
            method 	: 'POST',
            body 	: formData
        })
        .then( function( response ){ return response.json() })
        .then( function( json ) {            
            if(json.result === 'success') {
                window.location.reload();
            }            
        });
        
    }
</script>