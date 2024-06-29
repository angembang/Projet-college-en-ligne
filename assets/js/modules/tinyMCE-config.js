function configureTinyMCE() {
    tinymce.init({
        selector: '#content',
        plugins: 'link image',
        toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | link image',
        content_style: 'body { font-size: 16px; }',
        images_upload_url: 'index.php?route=upload', // URL to handle image upload
        automatic_uploads: true,
        images_upload_handler: function (blobInfo, success, failure) {
            console.log('Starting image upload');
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', 'index.php?route=upload');

            xhr.onload = function () {
                var json;

                if (xhr.status != 200) {
                    console.error('HTTP Error: ' + xhr.status);
                    if (typeof failure === 'function') {
                        console.log('Calling failure callback with HTTP error');
                        failure('HTTP Error: ' + xhr.status);
                    }
                    return;
                }

                try {
                    json = JSON.parse(xhr.responseText);
                } catch (e) {
                    console.error('Invalid JSON response', xhr.responseText);
                    if (typeof failure === 'function') {
                        console.log('Calling failure callback with invalid JSON');
                        failure('Invalid JSON: ' + xhr.responseText);
                    }
                    return;
                }

                if (json.error) {
                    console.error('Upload error:', json.error);
                    if (typeof failure === 'function') {
                        console.log('Calling failure callback with upload error');
                        failure(json.error);
                    }
                    return;
                }

                if (!json || typeof json.location !== 'string') {
                    console.error('JSON response does not contain location', json);
                    if (typeof failure === 'function') {
                        console.log('Calling failure callback with invalid JSON location');
                        failure('Invalid JSON: ' + xhr.responseText);
                    }
                    return;
                }

                console.log('Upload successful, location:', json.location);
                if (typeof success === 'function') {
                    console.log('Calling success callback with location');
                    success(json.location);
                }
            };

            formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
    });
}

export {configureTinyMCE};