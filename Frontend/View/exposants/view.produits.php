<div class="produits">
    <h4 data-statut="off">Produits <i class="las la-angle-down"></i></h4>
    <div><?= $produits ?></div>
</div>

<script>
    window.addEventListener('load', function(){
        let produitsButton = document.querySelector('.produits h4');
        let produitsArrow = document.querySelector('.produits h4 i');

        produitsButton.addEventListener('click', function(){
            swap(produitsButton, produitsArrow);
        }, false);
        produitsArrow.addEventListener('click', function(){
            swap(produitsButton, produitsArrow);
        }, false);

    }, false);


    function swap(produitsButton, produitsArrow)
    {        
        if(produitsButton.getAttribute('data-statut') === 'off') {
            produitsButton.setAttribute('data-statut', 'on');
            produitsArrow.classList.remove('la-angle-down');
            produitsArrow.classList.add('la-angle-up');
        }
        else {
            produitsButton.setAttribute('data-statut', 'off'); 
            produitsArrow.classList.remove('la-angle-up');
            produitsArrow.classList.add('la-angle-down');
        }
    }
</script>