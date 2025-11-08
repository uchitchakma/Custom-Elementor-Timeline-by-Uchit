(function($) {
    'use strict';

    class CustomTimelineWidget {
        constructor() {
            this.timelines = [];
            this.init();
        }

        init() {
            // Initialize on document ready
            $(document).ready(() => {
                this.initTimelines();
            });

            // Reinitialize when Elementor preview loads
            $(window).on('elementor/frontend/init', () => {
                elementorFrontend.hooks.addAction('frontend/element_ready/custom-timeline.default', ($scope) => {
                    this.initSingleTimeline($scope.find('.custom-timeline-wrapper'));
                });
            });

            // Handle window resize
            let resizeTimer;
            $(window).on('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    this.handleResize();
                }, 250);
            });

            // Handle scroll
            $(window).on('scroll', () => {
                this.handleScroll();
            });
        }

        initTimelines() {
            $('.custom-timeline-wrapper').each((index, element) => {
                this.initSingleTimeline($(element));
            });
        }

        initSingleTimeline($wrapper) {
            if (!$wrapper.length) return;

            const widgetId = $wrapper.data('widget-id');
            const breakpoint = $wrapper.data('breakpoint') || 768;

            // Store timeline data
            const timelineData = {
                $wrapper: $wrapper,
                widgetId: widgetId,
                breakpoint: breakpoint,
                $desktop: $wrapper.find('.timeline-desktop'),
                $mobile: $wrapper.find('.timeline-mobile'),
                $items: null,
                $dots: null,
                $line: null,
                $progress: null,
                isMobile: false
            };

            // Update references based on viewport
            this.updateTimelineReferences(timelineData);

            // Store in timelines array
            this.timelines.push(timelineData);

            // Initial setup
            this.setupResponsiveBehavior(timelineData);
            this.updateTimelineProgress(timelineData);
            this.setupIntersectionObserver(timelineData);

            console.log('Timeline initialized:', widgetId);
        }

        updateTimelineReferences(timelineData) {
            const windowWidth = $(window).width();
            timelineData.isMobile = windowWidth <= timelineData.breakpoint;

            const $activeContainer = timelineData.isMobile ? 
                timelineData.$mobile : 
                timelineData.$desktop;

            timelineData.$items = $activeContainer.find('.timeline-item');
            timelineData.$dots = $activeContainer.find('.timeline-dot');
            timelineData.$line = $activeContainer.find('.timeline-line');
            timelineData.$progress = $activeContainer.find('.timeline-line-progress');
        }

        setupResponsiveBehavior(timelineData) {
            const windowWidth = $(window).width();
            
            if (windowWidth <= timelineData.breakpoint) {
                timelineData.$desktop.hide();
                timelineData.$mobile.show();
                timelineData.isMobile = true;
            } else {
                timelineData.$desktop.show();
                timelineData.$mobile.hide();
                timelineData.isMobile = false;
            }

            this.updateTimelineReferences(timelineData);
        }

        handleResize() {
            this.timelines.forEach(timelineData => {
                this.setupResponsiveBehavior(timelineData);
                this.updateTimelineProgress(timelineData);
            });
        }

        handleScroll() {
            this.timelines.forEach(timelineData => {
                this.updateTimelineProgress(timelineData);
            });
        }

        updateTimelineProgress(timelineData) {
            if (!timelineData.$line.length || !timelineData.$items.length) return;

            const $line = timelineData.$line;
            const $progress = timelineData.$progress;
            const $items = timelineData.$items;
            const $dots = timelineData.$dots;

            const lineTop = $line.offset().top;
            const lineHeight = $line.height();
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const scrollBottom = scrollTop + windowHeight;

            // Calculate progress
            const startPoint = lineTop;
            const endPoint = lineTop + lineHeight;
            
            let progress = 0;
            if (scrollBottom > startPoint) {
                progress = ((scrollBottom - startPoint) / lineHeight) * 100;
                progress = Math.min(Math.max(progress, 0), 100);
            }

            // Update progress bar
            $progress.css('height', progress + '%');

            // Update dots
            $items.each((index, item) => {
                const $item = $(item);
                const $dot = $dots.eq(index);
                const itemTop = $item.offset().top;
                const itemMiddle = itemTop + ($item.height() / 2);
                
                // Activate dot if scrolled past middle of item
                if (scrollBottom >= itemMiddle) {
                    $dot.addClass('active');
                } else {
                    $dot.removeClass('active');
                }
            });
        }

        setupIntersectionObserver(timelineData) {
            if (!('IntersectionObserver' in window)) {
                // Fallback: show all items
                timelineData.$items.addClass('visible');
                return;
            }

            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('visible');
                    }
                });
            }, options);

            timelineData.$items.each((index, item) => {
                observer.observe(item);
            });
        }
    }

    // Initialize the widget
    const timelineWidget = new CustomTimelineWidget();

    // Expose to global scope
    window.CustomTimelineWidget = CustomTimelineWidget;

})(jQuery);