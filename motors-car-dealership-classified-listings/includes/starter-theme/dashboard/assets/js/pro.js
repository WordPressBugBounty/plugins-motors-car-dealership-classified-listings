window.addEventListener('load', () => {
  const $ = jQuery

  $(document).on('click', '.mst-starter-wizard__price-button', function(event) {
    event.preventDefault();
    $('.mst-starter-wizard__price-button').removeClass('selected-price');
    $(this).addClass('selected-price');
    let selectedPlan = $(this).hasClass('annual') ? 'annual' : 'lifetime';
    $(this).closest('.mst-starter-wizard__price-box').find('.mst-starter-wizard__button-pro').attr('data-plan', selectedPlan);
  });
});
