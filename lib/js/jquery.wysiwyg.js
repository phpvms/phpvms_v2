/**
 * WYSIWYG - jQuery plugin 1.0
 *
 * Copyright (c) 2007 Juan M Martinez
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id$
 */
(function( $ )
{
    $.fn.document = function()
    {
        var element = this[0];

        if ( element.nodeName.toLowerCase() == 'iframe' )
            return element.contentWindow.document;
            /*
            return ( $.browser.msie )
                ? document.frames[element.id].document
                : element.contentWindow.document // contentDocument;
             */
        else
            return $(this);
    };

    $.fn.wysiwyg = function( options )
    {
        if ( arguments.length > 0 && arguments[0].constructor == String )
        {
            var action = arguments[0].toString();
            var params = [];

            for ( var i = 1; i < arguments.length; i++ )
                params[i - 1] = arguments[i];

            if ( action in Wysiwyg )
            {
                return this.each(function()
                {
                    Wysiwyg[action].apply(this, params);
                });
            }
            else return this;
        }

        var controls = {};

        /**
         * If the user set custom controls, we catch it, and merge with the
         * defaults controls later.
         */
        if ( options && options.length > 0 && options.controls )
        {
            var controls = options.controls;
            delete options.controls;
        }

        var options = $.extend({
            html : '<'+'?xml version="1.0" encoding="UTF-8"?'+'><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body>INITIAL_CONTENT</body></html>',
            css  : {},

            debug    : false,
            autoSave : true,

            controls : {}
        }, options);

        $.extend(options.controls, Wysiwyg.TOOLBAR, controls);

        // not break the chain
        return this.each(function()
        {
            Wysiwyg(this, options);
        });
    };

    function Wysiwyg( element, options )
    {
        return this instanceof Wysiwyg
            ? this.init(element, options)
            : new Wysiwyg(element, options);
    }

    $.extend(Wysiwyg, {
        insertImage : function( szURL )
        {
            var self = $.data(this, 'wysiwyg');

            if ( self.constructor == Wysiwyg && szURL && szURL.length > 0 )
                self.editorDoc.execCommand('insertImage', false, szURL);
        },

        createLink : function( szURL )
        {
            var self = $.data(this, 'wysiwyg');

            if ( self.constructor == Wysiwyg && szURL && szURL.length > 0 )
                self.editorDoc.execCommand('createLink', false, szURL);
        },

        TOOLBAR : {
            bold          : { visible : true, tags : ['b', 'strong'], css : { fontWeight : 'bold' } },
            italic        : { visible : true, tags : ['i', 'em'], css : { fontStyle : 'italic' } },
            strikeThrough : { visible : false, tags : ['s', 'strike'], css : { textDecoration : 'line-through' } },
            underline     : { visible : true, tags : ['u'], css : { textDecoration : 'underline' } },

            separator00 : { visible : false, separator : true },

            justifyLeft   : { visible : false, css : { textAlign : 'left' } },
            justifyCenter : { visible : false, tags : ['center'], css : { textAlign : 'center' } },
            justifyRight  : { visible : false, css : { textAlign : 'right' } },
            justifyFull   : { visible : false, css : { textAlign : 'justify' } },

            separator01 : { visible : false, separator : true },

            indent  : { visible : true },
            outdent : { visible : true },

            separator02 : { visible : false, separator : true },

            subscript   : { visible : false, tags : ['sub'] },
            superscript : { visible : false, tags : ['sup'] },

            separator03 : { visible : false, separator : true },

            undo : { visible : true  },
            redo : { visible : false },

            separator04 : { visible : false, separator : true },

            insertOrderedList    : { visible : true, tags : ['ol'] },
            insertUnorderedList  : { visible : true, tags : ['ul'] },
            insertHorizontalRule : { visible : true, tags : ['hr'] },

            separator05 : { separator : true },

            createLink : {
                visible : true,
                exec    : function( self )
                {
                    if ( $.browser.msie )
                        self.editorDoc.execCommand('createLink', true, null);
                    else
                    {
                        var szURL = prompt('URL', 'http://');

                        if ( szURL && szURL.length > 0 )
                            self.editorDoc.execCommand('createLink', false, szURL);
                    }
                },

                tags : ['a']
            },

            insertImage : {
                visible : true,
                exec    : function( self )
                {
                    if ( $.browser.msie )
                        self.editorDoc.execCommand('insertImage', true, null);
                    else
                    {
                        var szURL = prompt('URL', 'http://');

                        if ( szURL && szURL.length > 0 )
                            self.editorDoc.execCommand('insertImage', false, szURL);
                    }
                },

                tags : ['img']
            },

            separator06 : { separator : true },

            h1mozilla : { visible : true && $.browser.mozilla, className : 'h1', command : 'heading', arguments : ['h1'], tags : ['h1'] },
            h2mozilla : { visible : true && $.browser.mozilla, className : 'h2', command : 'heading', arguments : ['h2'], tags : ['h2'] },
            h3mozilla : { visible : true && $.browser.mozilla, className : 'h3', command : 'heading', arguments : ['h3'], tags : ['h3'] },

            h1 : { visible : true && !( $.browser.mozilla ), className : 'h1', command : 'formatBlock', arguments : ['h1'], tags : ['h1'] },
            h2 : { visible : true && !( $.browser.mozilla ), className : 'h2', command : 'formatBlock', arguments : ['h2'], tags : ['h2'] },
            h3 : { visible : true && !( $.browser.mozilla ), className : 'h3', command : 'formatBlock', arguments : ['h3'], tags : ['h3'] },

            separator07 : { visible : false, separator : true },

            cut   : { visible : false },
            copy  : { visible : false },
            paste : { visible : false },

            separator08 : { separator : true && !( $.browser.msie ) },

            increaseFontSize : { visible : true && !( $.browser.msie ), tags : ['big'] },
            decreaseFontSize : { visible : true && !( $.browser.msie ), tags : ['small'] },

            separator09 : { separator : true },

            html : {
                visible : false,
                exec    : function( self )
                {
                    if ( self.viewHTML )
                    {
                        self.setContent( $(self.original).val() );
                        $(self.original).hide();
                    }
                    else
                    {
                        self.saveContent();
                        $(self.original).show();
                    }

                    self.viewHTML = !( self.viewHTML );
                }
            },

            removeFormat : {
                visible : true,
                exec    : function( self )
                {
                    self.editorDoc.execCommand('removeFormat', false, []);
                    self.editorDoc.execCommand('unlink', false, []);
                }
            }
        }
    });

    $.extend(Wysiwyg.prototype,
    {
        original : null,
        options  : {},

        element  : null,
        editor   : null,

        init : function( element, options )
        {
            var self = this;

            this.editor = element;
            this.options = options || {};

            $.data(element, 'wysiwyg', this);

            var newX = element.width || element.clientWidth;
            var newY = element.height || element.clientHeight;

            if ( element.nodeName.toLowerCase() == 'textarea' )
            {
                this.original = element;

                if ( newX == 0 && element.cols )
                    newX = ( element.cols * 8 ) + 21;

                if ( newY == 0 && element.rows )
                    newY = ( element.rows * 16 ) + 16;

                var editor = this.editor = $('<iframe></iframe>').css({
                    minHeight : ( newY - 6 ).toString() + 'px',
                    width     : ( newX - 8 ).toString() + 'px'
                }).attr('id', $(element).attr('id') + 'IFrame');

                if ( $.browser.msie )
                {
                    this.editor
                        .css('height', ( newY ).toString() + 'px');

                    /**
                    var editor = $('<span></span>').css({
                        width     : ( newX - 6 ).toString() + 'px',
                        height    : ( newY - 8 ).toString() + 'px'
                    }).attr('id', $(element).attr('id') + 'IFrame');

                    editor.outerHTML = this.editor.outerHTML;
                     */
                }
            }

            var panel = this.panel = $('<ul></ul>').addClass('panel');

            this.appendControls();
            this.element = $('<div></div>').css({
                width : ( newX > 0 ) ? ( newX ).toString() + 'px' : '100%'
            }).addClass('wysiwyg')
              .append(panel)
              .append( $('<div><!-- --></div>').css({ clear : 'both' }) )
              .append(editor);

            $(element)
            // .css('display', 'none')
            .hide()
            .before(this.element);

            this.viewHTML = false;

            this.initialHeight = newY - 8;
            this.initialContent = $(element).text();

            this.initFrame();

            if ( this.initialContent.length == 0 )
                this.setContent('<br />');

            if ( this.options.autoSave )
                $('form').submit(function() { self.saveContent(); });
        },

        initFrame : function()
        {
            var self = this;

            this.editorDoc = $(this.editor).document();
            this.editorDoc.open();
            this.editorDoc.write(
                this.options.html.replace(/INITIAL_CONTENT/, this.initialContent)
            );
            this.editorDoc.close();
            this.editorDoc.contentEditable = 'true';

            this.editorDoc_designMode = false;

            try {
                this.editorDoc.designMode = 'on';
                this.editorDoc_designMode = true;
            } catch ( e ) {
                // Will fail on Gecko if the editor is placed in an hidden container element
                // The design mode will be set ones the editor is focused

                $(this.editorDoc).focus(function()
                {
                    if ( !( self.editorDoc_designMode ) )
                    {
                        try {
                            self.editorDoc.designMode = 'on';
                            self.editorDoc_designMode = true;
                        } catch ( e ) {}
                    }
                });
            }

            $(this.editorDoc).click(function( event )
            {
                self.checkTargets( event.target ? event.target : event.srcElement);
            });

            if ( this.options.autoSave )
            {
                /**
                 * @link http://code.google.com/p/jwysiwyg/issues/detail?id=11
                 */
                $(this.editorDoc).keydown(function() { self.saveContent(); })
                                 .mousedown(function() { self.saveContent(); });
            }

            if ( this.options.css )
            {
                setTimeout(function()
                {
                    if ( self.options.css.constructor == String )
                    {
                        $(self.editorDoc)
                        .find('head')
                        .append(
                            $('<link rel="stylesheet" type="text/css" media="screen" />')
                            .attr('href', self.options.css)
                        );
                    }
                    else
                        $(self.editorDoc).find('body').css(self.options.css);
                }, 0);
            }
        },

        getContent : function()
        {
            return $( $(this.editor).document() ).find('body').html();
        },

        setContent : function( newContent )
        {
            $( $(this.editor).document() ).find('body').html(newContent);
        },

        saveContent : function()
        {
            if ( this.original )
                $(this.original).val( this.getContent() );
        },

        appendMenu : function( cmd, args, className, fn )
        {
            var self = this;
            var args = args || [];

            $('<li></li>').append(
                $('<a><!-- --></a>').addClass(className || cmd)
            ).mousedown(function() {
                if ( fn ) fn(self); else self.editorDoc.execCommand(cmd, false, args);
                if ( self.options.autoSave ) self.saveContent();
            }).appendTo( this.panel );
        },

        appendMenuSeparator : function()
        {
            $('<li class="separator"></li>').appendTo( this.panel );
        },

        appendControls : function()
        {
            for ( var name in this.options.controls )
            {
                var control = this.options.controls[name];

                if ( control.separator )
                {
                    if ( control.visible !== false )
                        this.appendMenuSeparator();
                }
                else if ( control.visible )
                {
                    this.appendMenu(
                        control.command || name, control.arguments || [],
                        control.className || control.command || name || 'empty', control.exec
                    );
                }
            }
        },

        checkTargets : function( element )
        {
            for ( var name in this.options.controls )
            {
                var control = this.options.controls[name];
                var className = control.className || control.command || name || 'empty';

                $('.' + className, this.panel).removeClass('active');

                if ( control.tags )
                {
                    var elm = element;

                    do {
                        if ( elm.nodeType != 1 )
                            break;

                        if ( $.inArray(elm.tagName.toLowerCase(), control.tags) != -1 )
                            $('.' + className, this.panel).addClass('active');
                    } while ( elm = elm.parentNode );
                }

                if ( control.css )
                {
                    var elm = $(element);

                    do {
                        if ( elm[0].nodeType != 1 )
                            break;

                        for ( var cssProperty in control.css )
                            if ( elm.css(cssProperty).toString().toLowerCase() == control.css[cssProperty] )
                                $('.' + className, this.panel).addClass('active');
                    } while ( elm = elm.parent() );
                }
            }
        }
    });
})(jQuery);