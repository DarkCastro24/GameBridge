document.addEventListener('DOMContentLoaded', function() {
    // Se inicializa el componente Sidenav para que funcione el menú lateral
    var elems = document.querySelectorAll('.sidenav');
    var instances = M.Sidenav.init(elems);
    // Se inicializa el componente Slider 
    var elems = document.querySelectorAll('.slider');
    var instances = M.Slider.init(elems);
    // Se inicializa el componente modal
    var elems = document.querySelectorAll('.modal');
    var instances = M.Modal.init(elems);
    // Se inicializa el componente carousel 
    var elems = document.querySelectorAll('.carousel');
    var instances = M.Carousel.init(elems,{duration: 500});
    // Se inicializa el componente collapsible
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems);
    // Se inicializa el componente select
    var elems = document.querySelectorAll('.select');
    var instances = M.FormSelect.init(elems);
    // Se inicializa el componente dropdown-trigger
    var elems = document.querySelectorAll('.dropdown-trigger');
    var instances = M.Dropdown.init(elems);
    // Se indica que el maximo tamaño y los indicadores del modal estan activados
    var instance = M.Carousel.init({
        fullWidth: true,
        indicators: true
      });   
});



