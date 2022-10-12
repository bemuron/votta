document.addEventListener('DOMContentLoaded', () => {
    "use strict";
  
    /**
     * Preloader
     */
    const preloader = document.querySelector('#preloader');
    if (preloader) {
      window.addEventListener('load', () => {
        preloader.remove();
      });
    }
  
    /**
     * Sticky header on scroll
     */
    const selectHeader = document.querySelector('#header');
    if (selectHeader) {
      document.addEventListener('scroll', () => {
        window.scrollY > 100 ? selectHeader.classList.add('sticked') : selectHeader.classList.remove('sticked');
      });
    }
  
    /**
     * Mobile nav toggle
     */
    const mobileNavShow = document.querySelector('.mobile-nav-show');
    const mobileNavHide = document.querySelector('.mobile-nav-hide');
  
    document.querySelectorAll('.mobile-nav-toggle').forEach(el => {
      el.addEventListener('click', function(event) {
        event.preventDefault();
        mobileNavToogle();
      })
    });
  
    function mobileNavToogle() {
      document.querySelector('body').classList.toggle('mobile-nav-active');
      mobileNavShow.classList.toggle('d-none');
      mobileNavHide.classList.toggle('d-none');
    }
  
    /**
     * Toggle mobile nav dropdowns
     */
    const navDropdowns = document.querySelectorAll('.navbar .dropdown > a');
  
    navDropdowns.forEach(el => {
      el.addEventListener('click', function(event) {
        if (document.querySelector('.mobile-nav-active')) {
          event.preventDefault();
          this.classList.toggle('active');
          this.nextElementSibling.classList.toggle('dropdown-active');
  
          let dropDownIndicator = this.querySelector('.dropdown-indicator');
          dropDownIndicator.classList.toggle('bi-chevron-up');
          dropDownIndicator.classList.toggle('bi-chevron-down');
        }
      })
    });
  
    /**
     * Scroll top button
     */
    const scrollTop = document.querySelector('.scroll-top');
    if (scrollTop) {
      const togglescrollTop = function() {
        window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
      }
      window.addEventListener('load', togglescrollTop);
      document.addEventListener('scroll', togglescrollTop);
      scrollTop.addEventListener('click', window.scrollTo({
        top: 0,
        behavior: 'smooth'
      }));
    }
  
    /**
     * Initiate glightbox
     */
    const glightbox = GLightbox({
      selector: '.glightbox'
    });
  
    /**
     * Init swiper slider with 1 slide at once in desktop view
     */
    new Swiper('.slides-1', {
      speed: 600,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false
      },
      slidesPerView: 'auto',
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      }
    });
  
    /**
     * Init swiper slider with 3 slides at once in desktop view
     */
    new Swiper('.slides-3', {
      speed: 600,
      loop: true,
      autoplay: {
        delay: 5000,
        disableOnInteraction: false
      },
      slidesPerView: 'auto',
      pagination: {
        el: '.swiper-pagination',
        type: 'bullets',
        clickable: true
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        320: {
          slidesPerView: 1,
          spaceBetween: 40
        },
  
        1200: {
          slidesPerView: 3,
        }
      }
    });
  
    /**
     * Porfolio isotope and filter
     */
    let portfolionIsotope = document.querySelector('.portfolio-isotope');
  
    if (portfolionIsotope) {
  
      let portfolioFilter = portfolionIsotope.getAttribute('data-portfolio-filter') ? portfolionIsotope.getAttribute('data-portfolio-filter') : '*';
      let portfolioLayout = portfolionIsotope.getAttribute('data-portfolio-layout') ? portfolionIsotope.getAttribute('data-portfolio-layout') : 'masonry';
      let portfolioSort = portfolionIsotope.getAttribute('data-portfolio-sort') ? portfolionIsotope.getAttribute('data-portfolio-sort') : 'original-order';
  
      window.addEventListener('load', () => {
        let portfolioIsotope = new Isotope(document.querySelector('.portfolio-container'), {
          itemSelector: '.portfolio-item',
          layoutMode: portfolioLayout,
          filter: portfolioFilter,
          sortBy: portfolioSort
        });
  
        let menuFilters = document.querySelectorAll('.portfolio-isotope .portfolio-flters li');
        menuFilters.forEach(function(el) {
          el.addEventListener('click', function() {
            document.querySelector('.portfolio-isotope .portfolio-flters .filter-active').classList.remove('filter-active');
            this.classList.add('filter-active');
            portfolioIsotope.arrange({
              filter: this.getAttribute('data-filter')
            });
            if (typeof aos_init === 'function') {
              aos_init();
            }
          }, false);
        });
  
      });
  
    }
  
    /**
     * Animation on scroll function and init
     */
    function aos_init() {
      AOS.init({
        duration: 800,
        easing: 'slide',
        once: true,
        mirror: false
      });
    }
    window.addEventListener('load', () => {
      aos_init();
    });
  
  });

  $(function(){
    'use strict'
  
    feather.replace();
  
    ////////// NAVBAR //////////
  
    // Initialize PerfectScrollbar of navbar menu for mobile only
    if(window.matchMedia('(max-width: 991px)').matches) {
      const psNavbar = new PerfectScrollbar('#navbarMenu', {
        suppressScrollX: true
      });
    }
  
    // Showing sub-menu of active menu on navbar when mobile
    function showNavbarActiveSub() {
      if(window.matchMedia('(max-width: 991px)').matches) {
        $('#navbarMenu .active').addClass('show');
      } else {
        $('#navbarMenu .active').removeClass('show');
      }
    }
  
    showNavbarActiveSub()
    $(window).resize(function(){
      showNavbarActiveSub()
    })
  
    // Initialize backdrop for overlay purpose
    $('body').append('<div class="backdrop"></div>');
  
  
    // Showing sub menu of navbar menu while hiding other siblings
    $('.navbar-menu .with-sub .nav-link').on('click', function(e){
      e.preventDefault();
      $(this).parent().toggleClass('show');
      $(this).parent().siblings().removeClass('show');
  
      if(window.matchMedia('(max-width: 991px)').matches) {
        psNavbar.update();
      }
    })
  
    // Closing dropdown menu of navbar menu
    $(document).on('click touchstart', function(e){
      e.stopPropagation();
  
      // closing nav sub menu of header when clicking outside of it
      if(window.matchMedia('(min-width: 992px)').matches) {
        var navTarg = $(e.target).closest('.navbar-menu .nav-item').length;
        if(!navTarg) {
          $('.navbar-header .show').removeClass('show');
        }
      }
    })
  
    $('#mainMenuClose').on('click', function(e){
      e.preventDefault();
      $('body').removeClass('navbar-nav-show');
    });
  
    $('#sidebarMenuOpen').on('click', function(e){
      e.preventDefault();
      $('body').addClass('sidebar-show');
    })
  
    // Navbar Search
    $('#navbarSearch').on('click', function(e){
      e.preventDefault();
      $('.navbar-search').addClass('visible');
      $('.backdrop').addClass('show');
    })
  
    $('#navbarSearchClose').on('click', function(e){
      e.preventDefault();
      $('.navbar-search').removeClass('visible');
      $('.backdrop').removeClass('show');
    })
  
  
  
    ////////// SIDEBAR //////////
  
    // Initialize PerfectScrollbar for sidebar menu
    if($('#sidebarMenu').length) {
      const psSidebar = new PerfectScrollbar('#sidebarMenu', {
        suppressScrollX: true
      });
  
  
      // Showing sub menu in sidebar
      $('.sidebar-nav .with-sub').on('click', function(e){
        e.preventDefault();
        $(this).parent().toggleClass('show');
  
        psSidebar.update();
      })
    }
  
  
    $('#mainMenuOpen').on('click touchstart', function(e){
      e.preventDefault();
      $('body').addClass('navbar-nav-show');
    })
  
    $('#sidebarMenuClose').on('click', function(e){
      e.preventDefault();
      $('body').removeClass('sidebar-show');
    })
  
    // hide sidebar when clicking outside of it
    $(document).on('click touchstart', function(e){
      e.stopPropagation();
  
      // closing of sidebar menu when clicking outside of it
      if(!$(e.target).closest('.burger-menu').length) {
        var sb = $(e.target).closest('.sidebar').length;
        var nb = $(e.target).closest('.navbar-menu-wrapper').length;
        if(!sb && !nb) {
          if($('body').hasClass('navbar-nav-show')) {
            $('body').removeClass('navbar-nav-show');
          } else {
            $('body').removeClass('sidebar-show');
          }
        }
      }
    });
  
  })

  $(function(){

    'use strict'
  
    $('[data-toggle="tooltip"]').tooltip()
  
    const asideBody = new PerfectScrollbar('.aside-body', {
      suppressScrollX: true
    });
  
    if($('.aside-backdrop').length === 0) {
      $('body').append('<div class="aside-backdrop"></div>');
    }
  
    var mql = window.matchMedia('(min-width:992px) and (max-width: 1199px)');
  
    function doMinimize(e) {
      if (e.matches) {
        $('.aside').addClass('minimize');
      } else {
        $('.aside').removeClass('minimize');
      }
  
      asideBody.update()
    }
  
    mql.addListener(doMinimize);
    doMinimize(mql);
  
    $('.aside-menu-link').on('click', function(e){
      e.preventDefault()
  
      if(window.matchMedia('(min-width: 992px)').matches) {
        $(this).closest('.aside').toggleClass('minimize');
      } else {
  
        $('body').toggleClass('show-aside');
      }
  
      asideBody.update()
    })
  
    $('.nav-aside .with-sub').on('click', '.nav-link', function(e){
      e.preventDefault();
  
      $(this).parent().siblings().removeClass('show');
      $(this).parent().toggleClass('show');
  
      asideBody.update()
    })
  
    $('body').on('mouseenter', '.minimize .aside-body', function(e){
      console.log('e');
      $(this).parent().addClass('maximize');
    })
  
    $('body').on('mouseleave', '.minimize .aside-body', function(e){
      $(this).parent().removeClass('maximize');
  
      asideBody.update()
    })
  
    $('body').on('click', '.aside-backdrop', function(e){
      $('body').removeClass('show-aside');
    })
  })