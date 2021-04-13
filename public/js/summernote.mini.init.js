//$(document).ready(function(){});

function setEditorMini(el){
	if(parseInt(el.attr('data-editor')) === 1){
		return false;
	};
	
	el.attr('data-editor', 1);
	
	el.summernote({
        toolbar: [
            //['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            //['para', ['ul', 'ol', 'paragraph']],
            //['table', ['table']],
            //['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ]
	});
};
