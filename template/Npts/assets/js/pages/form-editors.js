/* 
SummerNote
*/
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'summernote' ]) ) {

		$(function() {
			$('[data-plugin-summernote]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginSummerNote(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

(function(theme, $) {

	theme = theme || {};

	var instanceName = '__summernote';

	var PluginSummerNote = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginSummerNote.defaults = {
		onfocus: function() {
			$( this ).closest( '.note-editor' ).addClass( 'active' );
		},
		onblur: function() {
			$( this ).closest( '.note-editor' ).removeClass( 'active' );
		}
	};

	PluginSummerNote.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginSummerNote.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.summernote( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginSummerNote: PluginSummerNote
	});

	// jquery plugin
	$.fn.themePluginSummerNote = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginSummerNote($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

/* 
Markdown
*/
(function( $ ) {

	'use strict';

	if ( $.isFunction($.fn[ 'markdown' ]) ) {

		$(function() {
			$('[data-plugin-markdown-editor]').each(function() {
				var $this = $( this ),
					opts = {};

				var pluginOptions = $this.data('plugin-options');
				if (pluginOptions)
					opts = pluginOptions;

				$this.themePluginMarkdownEditor(opts);
			});
		});

	}

}).apply(this, [ jQuery ]);

(function(theme, $) {

	theme = theme || {};

	var instanceName = '__markdownEditor';

	var PluginMarkdownEditor = function($el, opts) {
		return this.initialize($el, opts);
	};

	PluginMarkdownEditor.defaults = {
		iconlibrary: 'fa'
	};

	PluginMarkdownEditor.prototype = {
		initialize: function($el, opts) {
			if ( $el.data( instanceName ) ) {
				return this;
			}

			this.$el = $el;

			this
				.setData()
				.setOptions(opts)
				.build();

			return this;
		},

		setData: function() {
			this.$el.data(instanceName, this);

			return this;
		},

		setOptions: function(opts) {
			this.options = $.extend( true, {}, PluginMarkdownEditor.defaults, opts );

			return this;
		},

		build: function() {
			this.$el.markdown( this.options );

			return this;
		}
	};

	// expose to scope
	$.extend(theme, {
		PluginMarkdownEditor: PluginMarkdownEditor
	});

	// jquery plugin
	$.fn.themePluginMarkdownEditor = function(opts) {
		return this.each(function() {
			var $this = $(this);

			if ($this.data(instanceName)) {
				return $this.data(instanceName);
			} else {
				return new PluginMarkdownEditor($this, opts);
			}

		});
	}

}).apply(this, [ window.theme, jQuery ]);

 function sendFile(files, editor, $editable) {
 var target="file/update";  
        var data = new FormData();  
        data.append("imageFile", files[0]);  
        $.ajax({  
            data : data,  
            type : "POST",  
            url : target, //图片上传出来的url，返回的是图片上传后的路径，http格式  
            cache : false,  
            contentType : false,  
            processData : false,  
            dataType : "json",  
            success: function(data) {
            console.log(data);
            if(data.status==1){//data是返回的hash,key之类的值，key是定义的文件名
              editor.insertImage($editable, data.path);
                }else{
                	$(".modal-title").html(data.info);
					$('#finish-button') .modal('show');
                }  
            },  
            error:function(){  
	               $(".modal-title").html('出错了...');
					$('#finish-button') .modal('show')
            }  
        });  
    }  