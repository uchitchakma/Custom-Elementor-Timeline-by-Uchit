(function($) {
    'use strict';

    // Ensure jQuery is available
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    class CustomTimelineWidget {
        constructor() {
            this.timelines = [];
            this.isInitialized = false;
            this.init();
        }

        init() {
            const self = this;
            
            // Multiple initialization methods to ensure it works everywhere
            
            // Method 1: Document ready
            $(document).ready(function() {
                self.initTimelines();
            });

            // Method 2: Window load (fallback)
            $(window).on('load', function() {
                if (!self.isInitialized) {
                    self.initTimelines();
                }
            });

            // Method 3: Immediate if DOM is already ready
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                setTimeout(function() {
                    self.initTimelines();
                }, 1);
            }

            // Reinitialize when Elementor preview loads
            $(window).on('elementor/frontend/init', function() {
                if (typeof elementorFrontend !== 'undefined') {
                    elementorFrontend.hooks.addAction('frontend/element_ready/custom-timeline.default', function($scope) {
                        self.initSingleTimeline($scope.find('.custom-timeline-wrapper'));
                    });
                }
            });

            // Handle window resize with debounce
            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    self.handleResize();
                }, 250);
            });

            // Handle scroll with throttle for better performance
            let scrollTimer;
            let isScrolling = false;
            $(window).on('scroll', function() {
                if (!isScrolling) {
                    window.requestAnimationFrame(function() {
                        self.handleScroll();
                        isScrolling = false;
                    });
                    isScrolling = true;
                }
            });

            console.log('Custom Timeline Widget initialized');
        }

        initTimelines() {
            const self = this;
            const $timelines = $('.custom-timeline-wrapper');
            
            if ($timelines.length === 0) {
                console.log('No timelines found on page');
                return;
            }

            console.log('Found ' + $timelines.length + ' timeline(s)');

            $timelines.each(function(index, element) {
                self.initSingleTimeline($(element));
            });

            this.isInitialized = true;

            // Force initial update
            setTimeout(function() {
                self.handleScroll();
            }, 100);
        }

        initSingleTimeline($wrapper) {
            if (!$wrapper.length) {
                console.log('Timeline wrapper not found');
                return;
            }

            const widgetId = $wrapper.data('widget-id') || 'timeline-' + Date.now();
            const breakpoint = parseInt($wrapper.data('breakpoint')) || 768;

            console.log('Initializing timeline:', widgetId);

            // Check if already initialized
            const existingIndex = this.timelines.findIndex(function(t) {
                return t.widgetId === widgetId;
            });

            if (existingIndex !== -1) {
                console.log('Timeline already initialized:', widgetId);
                return;
            }

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
                $movingMarker: null,
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

            console.log('Timeline initialized successfully:', widgetId);
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
            timelineData.$movingMarker = $activeContainer.find('.timeline-moving-marker');

            // Debug log
            if (timelineData.$movingMarker.length) {
                console.log('Moving marker found for:', timelineData.widgetId);
            } else {
                console.warn('Moving marker NOT found for:', timelineData.widgetId);
            }
        }

                setupResponsiveBehavior(timelineData) {
            const windowWidth = $(window).width();
            
            // Log for debugging
            console.log('Window width:', windowWidth, 'Breakpoint:', timelineData.breakpoint);
            
            if (windowWidth <= timelineData.breakpoint) {
                timelineData.$desktop.hide();
                timelineData.$mobile.show();
                timelineData.isMobile = true;
                console.log('Mobile mode activated');
                
                // Force mobile marker setup
                setTimeout(() => {
                    const $mobileMarker = timelineData.$mobile.find('.timeline-moving-marker');
                    if ($mobileMarker.length) {
                        const markerElement = $mobileMarker[0];
                        markerElement.style.setProperty('position', 'absolute', 'important');
                        markerElement.style.setProperty('left', '20px', 'important');
                        markerElement.style.setProperty('transform', 'translateX(-50%)', 'important');
                        markerElement.style.setProperty('display', 'flex', 'important');
                        markerElement.style.setProperty('visibility', 'visible', 'important');
                        console.log('Mobile marker forced setup complete');
                    }
                }, 50);
            } else {
                timelineData.$desktop.show();
                timelineData.$mobile.hide();
                timelineData.isMobile = false;
                console.log('Desktop mode activated');
            }

            // Update references AFTER changing visibility
            this.updateTimelineReferences(timelineData);
            
            // Force update after switching modes
            setTimeout(() => {
                this.updateTimelineProgress(timelineData);
            }, 100);
        }

        handleResize() {
            const self = this;
            this.timelines.forEach(function(timelineData) {
                self.setupResponsiveBehavior(timelineData);
                self.updateTimelineProgress(timelineData);
            });
        }

        handleScroll() {
            const self = this;
            this.timelines.forEach(function(timelineData) {
                self.updateTimelineProgress(timelineData);
            });
        }

                updateTimelineProgress(timelineData) {
            if (!timelineData.$line || !timelineData.$line.length) {
                return;
            }

            if (!timelineData.$items || !timelineData.$items.length) {
                return;
            }

            const $line = timelineData.$line;
            const $progress = timelineData.$progress;
            const $items = timelineData.$items;
            const $movingMarker = timelineData.$movingMarker;

            // Get line position and dimensions
            const lineOffset = $line.offset();
            if (!lineOffset) return;

            const lineTop = lineOffset.top;
            const lineHeight = $line.height();
            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const scrollBottom = scrollTop + windowHeight;

            // Calculate progress
            const startPoint = lineTop;
            const markerOffset = windowHeight * 0.3; // 30% from bottom
            
            let progress = 0;
            if (scrollBottom > startPoint) {
                progress = ((scrollBottom - startPoint - markerOffset) / lineHeight) * 100;
                progress = Math.min(Math.max(progress, 0), 100);
            }

            // Update progress bar - FORCE IT WITH SETPROPERTY
            if ($progress && $progress.length) {
                const progressElement = $progress[0];
                if (progressElement) {
                    // Force with setProperty for mobile
                    progressElement.style.setProperty('height', progress + '%', 'important');
                    progressElement.style.setProperty('background-color', '#3b82f6', 'important');
                    progressElement.style.setProperty('width', '100%', 'important');
                    progressElement.style.setProperty('position', 'absolute', 'important');
                    progressElement.style.setProperty('top', '0', 'important');
                    progressElement.style.setProperty('left', '0', 'important');
                    
                    if (timelineData.isMobile) {
                        console.log('Mobile progress line updated:', progress.toFixed(2) + '%');
                    }
                }
            }

            // Update moving marker position - PRESERVE ROTATION
            if ($movingMarker && $movingMarker.length) {
                const markerPosition = (progress / 100) * lineHeight;
                const markerElement = $movingMarker[0];
                
                if (markerElement) {
                    // Only update top position, don't touch transform (rotation is set via CSS)
                    markerElement.style.setProperty('top', markerPosition + 'px', 'important');
                    markerElement.style.setProperty('position', 'absolute', 'important');
                    markerElement.style.setProperty('display', 'flex', 'important');
                    markerElement.style.setProperty('visibility', 'visible', 'important');
                    markerElement.style.setProperty('opacity', '1', 'important');
                    markerElement.style.setProperty('z-index', '10', 'important');
                    
                    // Set left position differently for desktop vs mobile
                    // But DON'T set transform - let CSS handle it with rotation
                    if (timelineData.isMobile) {
                        markerElement.style.setProperty('left', '0', 'important');
                        console.log('Mobile marker forced to:', markerPosition + 'px');
                    } else {
                        markerElement.style.setProperty('left', '50%', 'important');
                        console.log('Desktop marker forced to:', markerPosition + 'px');
                    }
                }
                
                // Also set as attribute for debugging
                $movingMarker.attr('data-position', markerPosition);
                $movingMarker.attr('data-progress', progress.toFixed(2) + '%');
                
                // Force repaint
                if (markerElement) {
                    markerElement.offsetHeight;
                }
            }

            // Update item states
            $items.each(function(index, item) {
                const $item = $(item);
                const itemOffset = $item.offset();
                if (!itemOffset) return;

                const itemTop = itemOffset.top;
                const itemMiddle = itemTop + ($item.height() / 2);
                
                if (scrollBottom - markerOffset >= itemMiddle) {
                    $item.addClass('passed');
                } else {
                    $item.removeClass('passed');
                }
            });
        }

        setupIntersectionObserver(timelineData) {
            if (!('IntersectionObserver' in window)) {
                // Fallback: show all items
                if (timelineData.$items) {
                    timelineData.$items.addClass('visible');
                }
                return;
            }

            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        $(entry.target).addClass('visible');
                    }
                });
            }, options);

            if (timelineData.$items) {
                timelineData.$items.each(function(index, item) {
                    observer.observe(item);
                });
            }
        }
    }

    // Initialize the widget - Use both jQuery ready and native DOMContentLoaded
    let widgetInstance = null;

    function initWidget() {
        if (!widgetInstance) {
            widgetInstance = new CustomTimelineWidget();
            window.CustomTimelineWidget = CustomTimelineWidget;
            window.customTimelineInstance = widgetInstance;
        }
    }

    // jQuery ready
    $(document).ready(initWidget);

    // Native DOMContentLoaded as backup
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWidget);
    } else {
        initWidget();
    }

})(jQuery);