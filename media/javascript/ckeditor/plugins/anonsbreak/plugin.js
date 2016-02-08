( function(){
		var pluginRequires = [ 'fakeobjects' ];
		
		CKEDITOR.plugins.add( 'anonsbreak', {
				requires: pluginRequires,
				onLoad: function() {
					CKEDITOR.addCss("img.cke_anonsbreak {" +
														"background-image: url(" + CKEDITOR.getUrl(this.path + "images/anonsbreak.png") + ");" +
														"background-position: center center;" +
														"background-repeat: no-repeat;" +
														"clear: both;" + 
														"display: block" +
														"float: none;" +
														"width: 100%;" +
														"height: 10px;" +
														"padding: 3px 0;" +
														"border-top: #999999 1px dotted;" +
														"border-bottom: #999999 1px dotted;}"
														);
				},
				init: function( editor ) {
						editor.addCommand( 'insertAnonsBreak', {
								exec: function( editor ) {
									var images = editor.document.getElementsByTag( 'img' );
									for (var i = 0, len = images.count(); i < len; i++) {
										var img = images.getItem( i );
										if (img.hasClass('cke_anonsbreak')) {
											if ( confirm( 'В документы уже есть разделитель анонса. Вы хотите установить новое место разделителя?')) {
												img.remove();
												break;
											}
											else 
												return;
										}
									}
										insertComment( 'anonsbreak' );										
								}						
						});
						editor.ui.addButton( 'AnonsBreak', {
								label: 'Читать далее',
								icon: this.path + 'icons/anonsbreak.png',
								command: 'insertAnonsBreak',
								toolbar: 'insert'
						});
						
						function insertComment( text ){
							// Create the fake element that will be inserted into the document.
							// The trick is declaring it as an <hr>, so it will behave like a
							// block element (and in effect it behaves much like an <hr>).
							if ( !CKEDITOR.dom.comment.prototype.getAttribute ) {
								CKEDITOR.dom.comment.prototype.getAttribute = function() {
									return '';
								};
								CKEDITOR.dom.comment.prototype.attributes = {
									align : ''
								};
							}
							var fakeElement = editor.createFakeElement( new CKEDITOR.dom.comment( text ), 'cke_' + text, 'hr' );
							fakeElement.setAttribute( 'title', 'Читать далее' );
							
							// This is the trick part. We can't use editor.insertElement()
							// because we need to put the comment directly at <body> level.
							// We need to do range manipulation for that.

							// Get a DOM range from the current selection.
							var range = editor.getSelection().getRanges()[0],
							elementsPath = new CKEDITOR.dom.elementPath( range.getCommonAncestor( true ) ),
							element = ( elementsPath.block && elementsPath.block.getParent() ) || elementsPath.blockLimit,
							hasMoved;

							// If we're not in <body> go moving the position to after the
							// elements until reaching it. This may happen when inside tables,
							// lists, blockquotes, etc.
							while ( element && element.getName() != 'body' )
							{
								range.moveToPosition( element, CKEDITOR.POSITION_AFTER_END );
								hasMoved = 1;
								element = element.getParent();
							}

							// Split the current block.
							if ( !hasMoved )
								range.splitBlock( 'p' );
							
							//if (fakeElement != false) {							
								//fakeElement.setAttribute( 'title', 'Читать далее' );								
							//}
							// Insert the fake element into the document.
							range.insertNode( fakeElement );

							// Now, we move the selection to the best possible place following
							// our fake element.
							var next = fakeElement;
							while ( ( next = next.getNext() ) && !range.moveToElementEditStart( next ) )
							{}

							range.select();
						}
				},
				
				afterInit : function( editor )
				{
					// Adds the comment processing rules to the data filter, so comments
					// are replaced by fake elements.
					editor.dataProcessor.dataFilter.addRules(
					{
						comment : function( value )
						{
							if ( !CKEDITOR.htmlParser.comment.prototype.getAttribute ) {
								CKEDITOR.htmlParser.comment.prototype.getAttribute = function() {
									return '';
								};
								CKEDITOR.htmlParser.comment.prototype.attributes = {
									align : ''
								};
							}

							if ( value == 'anonsbreak'){
								var fakeElem = editor.createFakeParserElement( new CKEDITOR.htmlParser.comment( value ), 'cke_' + value, 'hr');
								return fakeElem;
							}

							return value;
						}
					});
				}
		});
} )();