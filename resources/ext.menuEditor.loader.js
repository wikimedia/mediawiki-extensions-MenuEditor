( function( mw, $ ) {
	ext.menueditor.init.loadEntities = function() {
		var dfd = $.Deferred();

		function register( value, registry ) {
			var dfd = $.Deferred(), modules = [];
			for ( var name in value ) {
				if ( !value.hasOwnProperty( name ) ) {
					continue;
				}
				modules.push( value[name].module );
				registry.register( name, value[name].classname );
			}

			mw.loader.using( $.unique( modules ), function() {
				dfd.resolve();
			}, function() {
				dfd.reject();
			} );

			return dfd.promise();
		}

		ext.menueditor.registry.menu = new OO.Registry();
		ext.menueditor.registry.node = new OO.Registry();

		$.when(
			register( require( './menus.json' ), ext.menueditor.registry.menu, dfd ),
			register( require( './nodes.json' ), ext.menueditor.registry.node, dfd )
		).then( function() {
			dfd.resolve();
		} );
		return dfd.promise();
	};
}( mediaWiki, jQuery ) );
