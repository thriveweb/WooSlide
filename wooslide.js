;(function(document, window, $) {
  'use strict'
  ;(function swiper() {
    var WooSlideGalleryTop = new Swiper('.WooSlide--gallery-top', {
      loop: false,
      autoHeight: true,
      grabCursor: true,
      spaceBetween: 30,
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      }
    })

    var WooSlideGalleryThumbs = new Swiper('.WooSlide--gallery-thumbs', {
      spaceBetween: 10,
      centeredSlides: true,
      slidesPerView: 'auto',
      touchRatio: 0.2,
      slideToClickedSlide: true
    })

    WooSlideGalleryTop.controller.control = WooSlideGalleryThumbs
    WooSlideGalleryThumbs.controller.control = WooSlideGalleryTop
  })()
  ;(function photoSwipe() {
    var pswpElement = document.querySelectorAll('.pswp')[0]
    var items = []

    function openPswp(index) {
      var options = {
        index: index,
        shareEl: false
      }
      // Initializes and opens PhotoSwipe
      var gallery = new PhotoSwipe(
        pswpElement,
        PhotoSwipeUI_Default,
        items,
        options
      )
      gallery.init()
    }

    // build items array
    function pushItem(image) {
      var src = image.attributes['data-hq'].value
      var w = image.attributes['data-w'].value
      var h = image.attributes['data-h'].value
      var t = image.attributes['data-title'].value
      var item = {
        src: src,
        w: w,
        h: h,
        title: t
      }
      items.push(item)
    }
    // Adding items to image for lightbox
    if ($('.WooSlide--gallery-top .swiper-slide').length > 0) {
      var $thumbs = $('.WooSlide--gallery-top .swiper-slide')
      var thumbAlt = $thumbs.attr('alt')
      for (var i = 0; i < $thumbs.length; i++) {
        $thumbs.attr('data-title', thumbAlt)
        pushItem($thumbs[i])
      }
    } else if ($('.WooSlide--gallery-top .swiper-slide').length > 0) {
      var singleImg = $('.WooSlide--gallery-top .swiper-slide')[0]
      var singleImgAlt = singleImg.attr('alt')
      singleImg.attr('data-title', singleImgAlt)

      var $this = $('.WooSlide--gallery-top .swiper-slide')[0]
      pushItem($this)
    }

    // click event
    if ($('.WooSlide--gallery-top .swiper-slide').length > 0) {
      $('.WooSlide--gallery-top .swiper-slide').click(function(e) {
        // Allow user to open image link in new tab or download it
        if (e.which == 2 || e.ctrlKey || e.altKey) {
          return
        }
        var ind = $(this).index()
        e.preventDefault()
        var index = ind ? parseInt(ind) : 0
        openPswp(index)
      })
    }
  })()
})(document, window, jQuery)
