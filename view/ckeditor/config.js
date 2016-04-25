/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'en';
	 config.enterMode = Number(2);
	// config.uiColor = '#AADC6E';

    // config.extraPlugins = 'uploadimage';
    // config.extraPlugins = 'notificationaggregator';
    // config.extraPlugins = 'filebrowser';
    // config.extraPlugins = 'clipboard';
    // config.extraPlugins = 'uploadwidget';
    // config.extraPlugins = 'widget';
    // config.extraPlugins = 'lineutils';
    // config.extraPlugins = 'clipboard';
    // config.extraPlugins = 'dialog';
    // config.extraPlugins = 'dialogui';
    // config.extraPlugins = 'filetools';

    //config.extraPlugins = 'imageuploader';
    // config.extraPlugins = 'filebrowser';
    // config.extraPlugins = 'clipboard';
    //
    config.filebrowserUploadUrl = '/uploader/upload.php';
    config.filebrowserBrowseUrl = '/browser/browse.php';
};
