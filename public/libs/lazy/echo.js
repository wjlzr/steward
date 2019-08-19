window.Echo = (function(window, document, undefined) {
    'use strict';
    var store = [],
        offset,
        throttle,
        scrolly = 0,
        sub = 1;
    var _inView = function(el) {
        var coords = el.getBoundingClientRect();
        return ((coords.top >= 0 && coords.left >= 0 && coords.top) <= (window.innerHeight || document.documentElement.clientHeight) + parseInt(offset))
    };
    var _pollImages = function() {
        for (var i = store.length; i--;) {
            var self = store[i];
            if (_inView(self)) {
                self.src = self.getAttribute('data-src');
                store.splice(i, 1)
            }
        }
    };
    var _throttle = function() {
        if (sub) {
            _pollImages();
            sub = 0;
        } else {

            if ( window.scrollY-scrolly >= offset/2 ) {
                scrolly = window.scrollY;
                setTimeout(_pollImages, throttle);
            }
        }
    };
    var init = function(obj) {
        sub = 1;
        var nodes = document.querySelectorAll('[data-src]');
        var opts = obj || {};
        offset = opts.offset || 0;
        throttle = opts.throttle || 250;
        for (var i = 0; i < nodes.length; i++) {
            store.push(nodes[i])
        }
        _throttle();
        if (document.addEventListener) {
            window.addEventListener('scroll', _throttle, true)
        } else {
            window.attachEvent('onscroll', _throttle)
        }
    };
    return {
        init: init,
        render: _throttle
    }
})(window, document);