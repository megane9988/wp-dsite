;var widgets = ( function( $, window, document, undefined ) {

	$(document).ready( function() {
		var sidebars = $( '#widgets-right .widgets-holder-wrap' );


		// Move inactive sidebars next to the normal sidebars
		var inactive = $( '.inactive-sidebar' );
		inactive.detach();
		inactive.appendTo( $( '#widgets-right' ) );
		inactive.each( function( index, element ) {
			if ( index == 0 )
				$(this).addClass('first');
			$(this).find( '.sidebar-description' ).appendTo( $(this).find( '.sidebar-name' ) );
		} );


		// Change the way the draggable appends the helper
		$('#widget-list').children('.widget').draggable({ appendTo: '#wpwrap div.widget-liquid-left' });


		// Override core's widget-choose handler, which relies on the widget-description being in its default location.
		// Since Better Widgets moves the description inside the widget-top, we have to change the placement of the
		// Area Chooser so that things work.
		setTimeout( function() { // We have to use a setTimeout due to a race condition with the widgets.js and this js.
			$( '#available-widgets .widget .widget-title' ).off( 'click.widgets-chooser' ).on( 'click.widgets-chooser', function() {
				var $widget = $(this).closest( '.widget' ),
					chooser = $('.widgets-chooser'),
					selectSidebar = chooser.find('.widgets-chooser-sidebars');

				if ( $widget.hasClass( 'widget-in-question' ) || $( '#widgets-left' ).hasClass( 'chooser' ) ) {
					wpWidgets.closeChooser();
				} else {
					// Open the chooser
					wpWidgets.clearWidgetSelection();
					$( '#widgets-left' ).addClass( 'chooser' );
					$widget.addClass( 'widget-in-question' ).append( chooser ); // This is the line that changed.

					chooser.slideDown( 300, function() {
						selectSidebar.find('.widgets-chooser-selected').focus();
					});

					selectSidebar.find( 'li' ).on( 'focusin.widgets-chooser', function() {
						selectSidebar.find('.widgets-chooser-selected').removeClass( 'widgets-chooser-selected' );
						$(this).addClass( 'widgets-chooser-selected' );
					} );
				}
			});
		}, 1 );


		// Prevent scrolling the parent while scrolling the available widgets.
		// "Don't cross the streams." -Spengler
		$( '#widgets-left' ).on('DOMMouseScroll mousewheel', function(ev) {
			var $this = $(this),
				scrollTop = this.scrollTop,
				scrollHeight = this.scrollHeight,
				height = $this.height(),
				delta = (ev.type == 'DOMMouseScroll' ?
					ev.originalEvent.detail * -40 :
					ev.originalEvent.wheelDelta),
				up = delta > 0;

			var prevent = function() {
				ev.stopPropagation();
				ev.preventDefault();
				ev.returnValue = false;
				return false;
			}

			if (!up && -delta > scrollHeight - height - scrollTop) {
				// Scrolling down, but this will take us past the bottom.
				$this.scrollTop(scrollHeight);
				return prevent();
			} else if (up && delta > scrollTop) {
				// Scrolling up, but this will take us past the top.
				$this.scrollTop(0);
				return prevent();
			}
		});


		// Clicking save will close the widget inside
		$( '#widgets-right .widgets-sortables' ).on( 'click', '.widget-control-save', function() {
			var widget = $(this).closest( '.widget' );

			var close_check = setInterval( function() {
				if ( widget.find( '.spinner' ).is( ':hidden' ) ) {
					widget.find( '.widget-title' ).click();

					setTimeout( function() {
						widget.addClass( 'saved' );
						setTimeout( function() {
							widget.removeClass( 'saved' );
						}, 3500 );
					}, 200 );
					clearInterval(close_check);
				}
			}, 200 );
		} );


		// Highlight Widgets that are open
		$( '#widgets-right .widgets-sortables' ).on( 'click', '.widget-title, .widget-action, .widget-control-close', function() {
			if ( $(this).closest( '.widget' ).hasClass( 'opened' ) ) {
				$(this).closest( '.widget' ).removeClass( 'opened' );
				$(this).closest( '.widget' ).find( '.widget-top' ).css( 'background-color', '' );
			} else {
				var highlight_background = $( '#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu' ).css( 'background-color' );
				$(this).closest( '.widget' ).find( '.widget-top' ).css( 'background-color', highlight_background );
				$(this).closest( '.widget' ).addClass( 'opened' );
			}
		} );
	});

})( jQuery, window, document );
