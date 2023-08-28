ext.menueditor.ui.data.tree.FooterLinksTree = function ( cfg ) {
	ext.menueditor.ui.data.tree.FooterLinksTree.parent.call( this, cfg );
};

// eslint-disable-next-line max-len
OO.inheritClass( ext.menueditor.ui.data.tree.FooterLinksTree, ext.menueditor.ui.data.tree.Tree );

// eslint-disable-next-line max-len
ext.menueditor.ui.data.tree.FooterLinksTree.prototype.getPossibleNodesForLevel = function ( lvl ) {
	switch ( lvl ) {
		case 0:
			return [ 'menu-wiki-link' ];
		default:
			return [];
	}
};

ext.menueditor.ui.data.tree.FooterLinksTree.prototype.getMaxLevels = function () {
	return 1;
};
