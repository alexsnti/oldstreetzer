/**
*   jQuery Plugin for simple vertical scrolling - horizontal movement parallax effect
*   I wrote this plugin for a project we have done,
*   inspiration came from a user NLZ on stackoverflow, thread:
*   http://stackoverflow.com/questions/18333085/move-div-horizontally-when-scroll-vertically-jquery-or-css3
*   So credits go to him, i took the idea and transformed it into a jquery plugin
**/
(function($) {
    $.jInvertScroll = function(sel, options) {
        var defaults = {
            width: 'auto',          // The horizontal container width
            height: 'auto',         // How far the user can scroll down (shorter distance = faster scrolling)
            onScroll: function(percent) {  // Callback fired when the user scrolls down, the percentage of how far the user has scrolled down gets passed as parameter (format: 0.xxxx - 1.0000)
                // do whatever you like
            }
        };
        
        var config = $.extend(defaults, options);
        
        if(typeof sel === 'Object' && sel.length > 0) {
            return;
        }
        
        var elements = [];
        var longest = 0;
        
        // Extract all selected elements from dom and save them into an array
        $.each(sel, function(i, val) {
            $(val).each(function(e) {
                var tmp = {
                    width: $(this).width(),
                    height: $(this).height(),
                    el: $(this)
                }
                
                elements.push(tmp);
                
                if(longest < tmp.width) {
                    longest = tmp.width;
                }
            });
        });
        
        // Use the longest elements width + height if set to auto
        if(config.width == 'auto') {
            config.width = longest;
        }
        
        if(config.height == 'auto') {
            config.height = longest;
        }
        
        // Set the body to the selected height
        $('body').css('height', config.height+'px');
        
        // Listen for the actual scroll event
        $(window).on('scroll resize', function(e) {
            var currY = $(this).scrollTop();
            var totalHeight = $(document).height();
            var winHeight = $(this).height();
            var winWidth = $(this).width();
            
            // Current percentual position
            var scrollPercent = (currY / (totalHeight - winHeight)).toFixed(4);
            
            // Call the onScroll callback
            if(typeof config.onScroll === 'function') {
                config.onScroll.call(this, scrollPercent);
            }
            
            // do the position calculation for each element
            $.each(elements, function(i, el) {
                var pos = Math.floor((el.width - winWidth) * scrollPercent) * -1;
                el.el.css('left', pos);
            });
        });
    };
}(jQuery));