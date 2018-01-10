$(document).ready(function() {
    $("#fileUpload").uploadify({
        debug: false,
        buttonText: 'BROWSE',
        'fileSizeLimit' : fileLimit,
        swf: $asseturl+'js/uploadify/uploadify.swf',
        uploader: $( "#ajx_save_file_url" ).val(),
        width: 120,
        height: 30,
        fileTypeExts : '*.doc; *.pdf; *.xls; *.xlsx; *.csv; *.docx',
        method: 'post',
        buttonImage: null,
        formData: {'folder':'uploads'},
        onUploadSuccess : function(file, data, response) {
            $('#uploaded-file').val(data);
            //downloadurl
            $('#uploaded-doc').html('<a href="'+downloadurl+'?file='+data+'">Uploaded Document</a>');
        }
    });
});