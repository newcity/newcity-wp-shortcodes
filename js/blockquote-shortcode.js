(function() {

      function getQuoteValues() {
            var quote, cite;
            quote = jQuery('#quote-shortcode').val();
            cite = jQuery('#cite-shortcode').val();
            return { quote: quote, cite: cite };
      }

      function clearQuoteValues() {
            jQuery('#quote-shortcode').val('');
            quote = '';
            jQuery('#cite-shortcode').val('');
            cite = '';
            return;
      }

      function buildModal( args ) {
            var $modal;
            if ( jQuery('#newcity_blockquote_ui').length > 0 ) {
                  $modal = jQuery('#newcity_blockquote_ui');
                  $modal.find('#quote-shortcode').attr('value', args.quote_pre);
            } else {
                  $modal = jQuery('<div id="newcity_blockquote_ui" title="Blockquote Details"></div>');
                  jQuery('body').append($modal);

                  if (args.validateTip) {
                        $modal.append('<p class="validateTips">' + args.validateTip + '</p>')
                  }
                  var $modalForm = $modal.append('<form><fieldset></fieldset></form>');

                  $modalForm.append('<p><label for="quote-shortcode">Quote Text</label><br/>' +
                        '<textarea rows="5" cols="83" name="quote-shortcode" id="quote-shortcode" value="' + args.quote_pre + '" class="text ui-widget-content ui-corner-all" style="width: 100%" /></p>');
                  $modalForm.append('<p><label for="cite-shortcode">Quote Citation</label><br/>' +
                        '<input type="text" name="cite-shortcode" id="cite-shortcode" value="' + args.cite_pre + '" class="text ui-widget-content ui-corner-all" style="width: 100%" /></p>');

                  
                  $modalObject = jQuery('#newcity_blockquote_ui').dialog({
                        autoOpen: false,
                        height: 325,
                        width: 640,
                        modal: true,
                        buttons: [
                              {
                              text: "Insert Blockquote",
                              // click: insertBlockquote( args.ed )
                              click: function( ) {
                                    insertBlockquote( args.ed );
                                    $modal.dialog('close');
                                    }
                              },
                              {
                              text: 'Cancel',
                              click: function() { $modal.dialog('close'); } 
                              }
                        ],
                        close: function() {
                              clearQuoteValues
                        }
                  });

                  $modalForm.on('submit', function(event) {
                        event.preventDefault();
                  });

                  jQuery('.ui-dialog').css('z-index', '2000');
            }

            return $modal;

      }

      function setupBlockquote( ed ) {
            var quote_pre = '';
            var selected = tinyMCE.activeEditor.selection.getContent();

            if ( selected ) {
                  quote_pre = selected;
            }

            var $modal = buildModal( { validateTip: '', quote_pre: quote_pre, cite_pre: '', ed: ed } );

            $modal.dialog( 'open' );
      }

      function insertBlockquote( ed ) {
            var values = getQuoteValues();

            if (values.quote != null && values.quote != ''){
                  if (values.cite != '')
                        ed.execCommand('mceInsertContent', false, '[blockquote cite="' +values.cite+ '"]' +values.quote+ '[/blockquote]');
                  else
                        ed.execCommand('mceInsertContent', false, '[blockquote]'+values.quote+'[/blockquote]');
            }

            clearQuoteValues();

            return true;
      }


   tinymce.create('tinymce.plugins.newcity_blockquote', {
      init : function(ed, url) {
         ed.addButton('newcity_blockquote', {
            title : 'Blockquote',
            icon : 'blockquote',
            onclick : function() {
                  setupBlockquote( ed );
            }
         });
      },
      createControl : function(n, cm) {
         return null;
      },
      getInfo : function() {
         return {
            longname : "NewCity Blockquote Shortcode",
            author : 'Jesse Janowiak',
            authorurl : 'https://insidenewcity.com',
            infourl : '',
            version : "1.0"
         };
      }
   });
   tinymce.PluginManager.add('newcity_blockquote', tinymce.plugins.newcity_blockquote);
})();