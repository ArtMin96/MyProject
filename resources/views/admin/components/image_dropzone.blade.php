@php

    $mock = [];
    foreach($$item->images as $additional_image) {
        $fileSize = 'N/A';
        $mock[] = [
            'id' => $additional_image->id,
            'path' => $additional_image->path,
            'size' => 'N/A'
        ];
    }
@endphp

<div class="col-md-12">

    <label class="form-control-label">Additional Images</label>
    <div class="dropzone dropzone-multiple" data-toggle="dropzone" data-dropzone-multiple data-dropzone-url="{{$url}}" data-mock="{{collect($mock)}}" data-csrf="{{csrf_token()}}" data-product-id="{{$$item->id}}">
        <div class="fallback">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="customFileUploadMultiple" multiple>
                <label class="custom-file-label" for="customFileUploadMultiple">Choose file</label>
            </div>
        </div>
        <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
            <li class="list-group-item px-0">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="avatar">
                            <img class="avatar-img rounded" src="" alt="..." data-dz-thumbnail>
                        </div>
                    </div>
                    <div class="col ml--3">
                        <h4 class="mb-1" data-dz-name>...</h4>
                        <p class="small text-muted mb-0" data-dz-size>...</p>
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" data-dz-uploadprogress>
                                <span class="progress-text"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="dropdown">
                            <a href="#" class="dropdown-ellipses dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fe fe-more-vertical"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item" data-dz-remove>
                                    Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
