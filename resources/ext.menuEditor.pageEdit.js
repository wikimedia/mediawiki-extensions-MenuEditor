( function ( mw, $ ) {
	$( function () {
		var $c = $( '#menuEditor-container' ); // eslint-disable-line no-jquery/no-global-selector
		if ( !$c.length ) {
			return;
		}

		var data = $c.data();
		var revId = mw.config.get( 'wgRevisionId' );
		if ( mw.util.getParamValue( 'oldid' ) ) {
			revId = mw.util.getParamValue( 'oldid' );
		}
		ext.menueditor.init.getPanelForPage(
			mw.config.get( 'wgPageName' ), data.menuKey, revId, data.mode, {
				defaultData: $c.data( 'default' )
			}
		).done( function ( panel ) {
			panel.connect( this, {
				saveFail: function ( error ) {
					$.prepend( new OO.ui.MessageWidget( {
						type: 'error',
						text: error
					} ).$element );
				},
				saveSuccess: function () {
					window.location = mw.util.getUrl( mw.config.get( 'wgPageName' ) );
				},
				cancel: function () {
					window.location = mw.util.getUrl( mw.config.get( 'wgPageName' ) );
				}
			} );
			$c.html( panel.$element );
		} ).fail( function ( e ) {
			console.error( e ); // eslint-disable-line no-console
		} );
	} );
}( mediaWiki, jQuery ) );
