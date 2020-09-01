var phpSandbox = {
	value : "",
	editor : false,
	execute : function() {
		this.showLoading();
		$.post('execute.php', {
			code : this.editor.getValue()
		}, $.proxy(this.onResponse, this));	
	},
	clear : function() {
		this.editor.getDoc().setValue('');
		this.editor.focus();
	},
	onResponse : function(html) {
		var document = $('#results').contents();
		var body = document.find("body");
		var head = document.find("head");
		var stylesheet = $("<link/>", {
			rel: "stylesheet", 
			href:"styles/style.css", 
			type:"text/css"
		});
		
		body.html(html);
		head.append(stylesheet);
		this.hideLoading();
	},
	showLoading : function() {
		$('#loading').removeClass('hidden');
	},
	hideLoading : function() {
		$('#loading').addClass('hidden');
	},
	setLayout : function(layout) {
		this.layout = layout;

		var code = document.getElementById('ide');
		code.classList.remove("layout-vertical");
		code.classList.remove("layout-horizontal");
		code.classList.add("layout-" + layout);
		
		localStorage.setItem("layout", layout);
		$('#layout-selector').val(layout);
	},
	setTemplate : function(index) {
		if (!index) {
			return;
		}
		
		var template = this.templates[index];
		this.editor.getDoc().setValue(template.code);
		$('#template-selector').val(0);
	},
	checkAuto : function() {
		var enabled = $('input#auto').is(':checked');
		if (enabled) {
			if (this.__autoTimeout) {
				clearTimeout(this.__autoTimeout);
				this.__autoTimeout = false;
			}
			
			this.__autoTimeout = setTimeout($.proxy(this.execute, this), 300);
		}
	},
	init : function() {
		this.editor = CodeMirror(document.getElementById('code'), {
			mode:  "text/x-php",
			theme : "ambiance",
			indentUnit : 4,
			indentWithTabs : true,
			lineNumbers : true,
			scrollbarStyle : "simple",
			value: this.value
		});
		
		this.setLayout(localStorage.getItem("layout") || 'vertical');

		this.editor.on('change', $.proxy(this.checkAuto, this));
		
		jQuery.hotkeys.options.filterContentEditable = false;
		jQuery.hotkeys.options.filterInputAcceptingElements = false;
		jQuery.hotkeys.options.filterTextInputs  = false;
		
		$(document).bind('keydown', 'f9', function(){
			phpSandbox.execute();
		});
	}
};
