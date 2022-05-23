ext.menueditor.ui.panel.MenuPanel = function ( cfg, treeData, menuType ) {
	ext.menueditor.ui.panel.MenuPanel.parent.call( this, cfg );

	this.pagename = cfg.pagename;
	this.allowEdit = cfg.mode && cfg.mode === 'edit';
	var classname = ext.menueditor.util.callbackFromString(
		ext.menueditor.registry.menu.registry[ menuType ]
	);

	if ( typeof treeData === 'undefined' && !this.allowEdit ) {
		this.$element.append(
			new OO.ui.MessageWidget( {
				type: 'warning',
				label: mw.message( 'menueditor-ui-redlink-notice' ).text()
			} ).$element
		);
		return;
	}

	// eslint-disable-next-line new-cap
	this.tree = new classname( {
		data: treeData || cfg.defaultData || [],
		editable: this.allowEdit
	} );

	if ( this.allowEdit ) {
		this.saveButton = new OO.ui.ButtonWidget( {
			label: mw.message( 'menueditor-ui-submit' ).text(),
			flags: [ 'primary', 'progressive' ]
		} );

		this.saveButton.connect( this, {
			click: function () {
				this.saveButton.setDisabled( true );
				var data = this.tree.getNodes();

				ext.menueditor.api.save( this.pagename, data ).done( function () {
					this.saveButton.setDisabled( false );
					this.emit( 'saveSuccess' );
				}.bind( this ) ).fail( function ( error ) {
					this.saveButton.setDisabled( false );
					this.emit( 'saveFailed', error );
				}.bind( this ) );
			}
		} );
		this.cancelButton = new OO.ui.ButtonWidget( {
			label: mw.message( 'menueditor-ui-cancel' ).text(),
			framed: false
		} );
		this.cancelButton.connect( this, {
			click: function () {
				this.emit( 'cancel' );
			}
		} );
		this.$element.append( new OO.ui.HorizontalLayout( {
			items: [ this.cancelButton, this.saveButton ]
		} ).$element.css( { 'text-align': 'right' } ) );
	}

	this.$element.append( this.tree.$element );
};

OO.inheritClass( ext.menueditor.ui.panel.MenuPanel, OO.ui.PanelLayout );

ext.menueditor.ui.panel.MenuPanel.prototype.getTree = function () {
	return this.tree;
};
