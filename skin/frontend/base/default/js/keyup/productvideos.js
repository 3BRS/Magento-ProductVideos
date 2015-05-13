document.observe('dom:loaded', function(){
    $$('div.more-views').first().observe('click', function (e) {
        var clickedThumb = e.findElement('.thumb-link');
        if (clickedThumb) {
            $$('.productvideos-gallery').invoke('show').invoke('removeClassName', 'productvideos-hidden');
            $$('.productvideos-player').invoke('hide');
        }
        var clickedThumbV = e.findElement('.thumb-link-pv');
        if (clickedThumbV) {
            e.stop();
            $$('.productvideos-gallery, .productvideos-player').invoke('hide').invoke('removeClassName', 'productvideos-hidden');
            $$('.productvideos-player-'+clickedThumbV.readAttribute('data-video-index')).invoke('show');
        }
    });
});