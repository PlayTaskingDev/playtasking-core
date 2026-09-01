<script
    src="https://cdn.tiny.cloud/1/{{ config('app.tiny_mce_api_key') }}/tinymce/6/tinymce.min.js"
    referrerpolicy="origin">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    tinymce.init({

        selector: 'textarea.tinymce-component',

        convert_urls: false,

        automatic_uploads: true,

        plugins: [
            'advlist',
            'autolink',
            'lists',
            'link',
            'image',
            'charmap',
            'preview',
            'anchor',
            'searchreplace',
            'visualblocks',
            'code',
            'fullscreen',
            'insertdatetime',
            'media',
            'table',
            'help',
            'wordcount'
        ],

        toolbar:
            'undo redo | blocks | bold italic | ' +
            'alignleft aligncenter alignright | ' +
            'indent outdent | bullist numlist | ' +
            'link image media | code | table',

        images_upload_handler:
            (blobInfo, progress) => {

                return new Promise(
                    (resolve, reject) => {

                        const formData =
                            new FormData();

                        formData.append(
                            'file',
                            blobInfo.blob(),
                            blobInfo.filename()
                        );

                        fetch(
                            @json(
                                route(
                                    'v2.media.upload',
                                    [
                                        'tenant' =>
                                            tenant('id')
                                    ]
                                )
                            ),
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        @json(csrf_token()),

                                    'Accept':
                                        'application/json'
                                },

                                body: formData
                            }
                        )
                        .then(async response => {

                            const data =
                                await response.json();

                            if (!response.ok) {

                                throw new Error(
                                    data.message
                                    ?? 'Error uploading image'
                                );
                            }

                            return data;
                        })
                        .then(data => {

                            resolve(
                                data.location
                            );
                        })
                        .catch(error => {

                            reject(
                                error.message
                            );
                        });
                    }
                );
            }
    });

});
</script>