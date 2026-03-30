(function() {

	"use strict";
  
	var app = {
		
		init: function() {

			app.setUpListeners();

            // Scroll to the element in the URL's hash on load
            app.scrollToHash(window.location.hash);

            app.changeMenuActiveClass();
			
		},

        setUpListeners: function() {

            var links = document.querySelectorAll('.main-mnu-landing-page a[href], .mmm-landing-page a[href]');
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    app.scrollToHash(app.getSamePageAnchor(link), e);
                    if(e.currentTarget.closest('.mmm') !== null) {
                        document.querySelector('body').classList.remove('mmm-open');
                        if( document.querySelector('.mf-bg') !== null ) { document.querySelector('.mf-bg').classList.remove('visible', 'mm'); }
                        if( document.querySelector('.main-mnu-btn') !== null ) { document.querySelector('.main-mnu-btn').classList.remove('active'); }
                    }
                });
            });

        },

        changeMenuActiveClass: function() {

            var sections = gsap.utils.toArray(".section, .intro, .intro-grid");
            sections.forEach(function(section) {
                var id = section.getAttribute('id');
                ScrollTrigger.create({
                    trigger: section,
                    start: "top top+=25%",
                    end: "bottom top+=25%",
                    onEnter: function() { app.menuItemChangeActiveClass(id); },
                    onEnterBack: function() { app.menuItemChangeActiveClass(id); },
                });
            });

        },

        menuItemChangeActiveClass: function(id) {

            var currentLinks = document.querySelectorAll('a[href="#' + id + '"]');
            currentLinks.forEach(function(link) {
                link.parentNode.classList.add("active");
                app.siblings(link.parentNode).forEach(item => { item.classList.remove("active"); });
            });

        },

        // Detect if a link's href goes to the current page
        getSamePageAnchor: function (link) {

            if (
                link.protocol !== window.location.protocol ||
                link.host !== window.location.host ||
                link.pathname !== window.location.pathname ||
                link.search !== window.location.search
            ) {
              return false;
            }
          
            return link.hash;

        },

        // Scroll to a given hash, preventing the event given if there is one
        scrollToHash: function(hash, e) {

            if( hash !== '#!' ) {

                var elem = hash ? document.querySelector(hash) : false;
                var offsetY = 0;
                if(elem) {
                    if(e) { e.preventDefault(); }
                    var headerFixed = document.querySelector('.header-fixed');
                    if(headerFixed !== null) { offsetY = headerFixed.offsetHeight - 2; }
                    //gsap.to(window, { scrollTo: { y: elem, offsetY: offsetY } });
                    jQuery('html, body').animate({ scrollTop: elem.offsetTop - offsetY }, 1000);
                }
                
            }

        },

        siblings: function(el) {

			if (el.parentNode === null) return [];

            return Array.prototype.filter.call(el.parentNode.children, function (child) {

                return child !== el;

            });

		},
		
	}

	app.init();
 
}());