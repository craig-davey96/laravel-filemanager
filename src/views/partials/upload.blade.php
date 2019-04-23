<div class="filemanager-upload-block">
    <div class="vert">
        <div class="inner">
            <form action="" method="post" enctype="multipart/form-data" id="upload_file">

                {{ csrf_field() }}

                <div class="inner-body">
                    <div class="dragdrop">
                        <div class="items">

                        </div>
                        <div class="vert">
                            <p>Click or drag to upload</p>
                        </div>
                    </div>
                    <input type="file" id="file_manager_files" multiple>
                </div>

            </form>
        </div>
    </div>
</div>