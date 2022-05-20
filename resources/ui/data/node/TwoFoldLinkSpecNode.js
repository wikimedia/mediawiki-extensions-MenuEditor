ext.menueditor.ui.data.node.TwoFoldLinkSpecNode = function( cfg ) {
	ext.menueditor.ui.data.node.TwoFoldLinkSpecNode.parent.call( this, cfg );
};

OO.inheritClass( ext.menueditor.ui.data.node.TwoFoldLinkSpecNode, ext.menueditor.ui.data.node.TreeNode );

ext.menueditor.ui.data.node.TwoFoldLinkSpecNode.static.canHaveChildren = false;

ext.menueditor.ui.data.node.TwoFoldLinkSpecNode.prototype.labelFromData = function( data ) {
	return data.label + ' (' + data.target + ')';
};

ext.menueditor.ui.data.node.TwoFoldLinkSpecNode.prototype.getIcon = function( data ) {
	return 'link';
};

ext.menueditor.ui.data.node.TwoFoldLinkSpecNode.prototype.getFormFields = function( ) {
	return [
		{
			name: 'target',
			type: 'text',
			required: true,
			label: mw.message( 'menueditor-ui-form-field-target' ).text()
		},
		{
			name: 'label',
			type: 'text',
			required: true,
			label: mw.message( 'menueditor-ui-form-field-label' ).text()
		}
	];
};
