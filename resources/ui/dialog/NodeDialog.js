ext.menueditor.ui.dialog.NodeDialog = function( config, node ) {
	ext.menueditor.ui.dialog.NodeDialog.super.call( this, config );
	this.node = node;
	this.allowedNodes = config.allowedNodes || [];
	this.tree = config.tree || {};
	this.form = null;
};

OO.inheritClass( ext.menueditor.ui.dialog.NodeDialog, OO.ui.ProcessDialog );

ext.menueditor.ui.dialog.NodeDialog.static.name = 'base-edit-menu-dialog';

ext.menueditor.ui.dialog.NodeDialog.static.actions = [
	{
		action: 'done',
		label: mw.message( 'menueditor-ui-dialog-action-done' ).text(),
		flags: [ 'primary', 'progressive' ]
	},
	{
		label: mw.message( 'menueditor-ui-dialog-action-cancel' ).text(),
		flags: 'safe'
	}
];

ext.menueditor.ui.dialog.NodeDialog.prototype.initialize = function () {
	ext.menueditor.ui.dialog.NodeDialog.super.prototype.initialize.call( this );
	this.content = new OO.ui.PanelLayout( {
		padded: true,
		expanded: true
	} );
	if ( this.node ) {
		this.pushPending();
		this.node.getForm().done( function( form ) {
			this.content.$element.append( form.$element );
			this.setForm( form );
		}.bind( this ) );
	} else {
		this.actions.setAbilities( { done: false } );
		var selector = new OO.ui.DropdownWidget( {
			menu: {
				items: this.getAllowedNodeOptions()
			},
			$overlay: true
		} );
		selector.getMenu().connect( this, {
			select: function( item ) {
				this.setItem( item.getData() );

			}
		} );

		this.content.$element.append(
			new OO.ui.FieldLayout( selector, {
				label: mw.message( 'menueditor-ui-node-type-selector-label' ).text(),
				align: 'top'
			} ).$element
		);
		this.formCnt = new OO.ui.PanelLayout( { padded: false, expanded: false } );
		this.content.$element.append( this.formCnt.$element );

		var first = selector.getMenu().findFirstSelectableItem();
		if ( first ) {
			selector.getMenu().selectItem( first );
		}
	}
	this.$body.append( this.content.$element );
};

ext.menueditor.ui.dialog.NodeDialog.prototype.getAllowedNodeOptions = function () {
	var all = Object.keys( ext.menueditor.registry.node.registry ),
		allowedConfig = this.allowedNodes,
		allowedValid = this.allowedNodes.length === 0 ?
			all : all.filter( function ( x ) { return allowedConfig.indexOf( x ) !== -1; } );

	console.log( all );
	return allowedValid.map( function ( x ) {
		var msg = mw.message( 'menueditor-ui-' + x + '-label' ),
			label = msg.exists() ? msg.text() : x;
		return new OO.ui.MenuOptionWidget( {
			data: x,
			label: label
		} );
	} );
};

ext.menueditor.ui.dialog.NodeDialog.prototype.setItem = function ( type ) {
	this.pushPending();

	var classname = ext.menueditor.util.callbackFromString( ext.menueditor.registry.node.registry[type] );
	var node = new classname( { nodeData: { type: type }, tree: this.tree } );
	node.getForm().done( function( form ) {
		this.formCnt.$element.html( form.$element );
		// make some space between the selector and form
		form.$element.css( { 'margin-top': '20px' } );
		this.setForm( form );
	}.bind( this ) );
};

ext.menueditor.ui.dialog.NodeDialog.prototype.setForm = function ( form ) {
	this.form = form;

	if ( form ) {
		form.connect( this, {
			renderComplete: function() {
				this.updateSize();
			}
		} );
		this.setSize( 'large' );
		this.actions.setAbilities( { done: true } );
	}

	this.popPending();
	this.updateSize();
};

ext.menueditor.ui.dialog.NodeDialog.prototype.getBodyHeight = function () {
	if ( !this.$errors.hasClass( 'oo-ui-element-hidden' ) ) {
		return this.$element.find( '.oo-ui-processDialog-errors' )[ 0 ].scrollHeight;
	}

	return this.$element.find( '.oo-ui-window-body' )[ 0 ].scrollHeight + 20;
};

ext.menueditor.ui.dialog.NodeDialog.prototype.getActionProcess = function ( action ) {
	if ( action === 'done' ) {
		this.form.connect( this, {
			dataSubmitted: function( data ) {
				this.close( { action: action, data: data, node: this.node } );
			}
		} );
		this.form.submit();
	}
	return ext.menueditor.ui.dialog.NodeDialog.super.prototype.getActionProcess.call( this, action );
};
