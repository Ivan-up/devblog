CKEDITOR.plugins.add( 'stealcontent',{
	init: function(editor) {
		
		editor.addCommand('stealcontent', new CKEDITOR.dialogCommand('stealcontentDialog'));
		
		editor.ui.addButton('Stealcontent',{
			label: 'Получить статью',
			command: 'stealcontent',
			icon: this.path + 'images/stealcontent.png',
			toolbar: 'insert'
		});
		
		CKEDITOR.dialog.add('stealcontentDialog', this.path + 'dialogs/stealcontent.js');		
	},
});