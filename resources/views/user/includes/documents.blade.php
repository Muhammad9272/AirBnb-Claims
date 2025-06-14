
    @foreach($documents as $document)
    <tr>
        <td>
            <div class="d-flex align-items-center">
                <div class="avatar-sm">
                    <div
                        class="avatar-title bg-soft-primary text-primary rounded fs-20">
                        <i class=" ri-file-fill"></i>
                    </div>
                </div>
                <div class="ms-3 flex-grow-1">
                    <h6 class="fs-15 mb-0"><a
                            href="javascript:void(0)">{{ $document->title }}</a>
                    </h6>
                </div>
            </div>
        </td>
        <td>
            {{ strtoupper(pathinfo($document->file, PATHINFO_EXTENSION)) }} File
        </td>
        <td>
            {{ $document->updated_at->format('d M Y') }}
        </td>
        <td>
            <div class="dropdown">
                <a href="{!! Helpers::image($document->file, 'banners/') !!}" download="true" 
                    class="btn btn-light btn-icon" >
                    <i class="ri-download-2-fill  align-middle "></i>
                </a>  
            </div>
            {{-- <div class="dropdown">
                <a href="javascript:void(0);"
                    class="btn btn-light btn-icon" id="dropdownMenuLink15"
                    data-bs-toggle="dropdown" aria-expanded="true">
                    <i class="ri-download-2-fill me-2 align-middle text-muted"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end"
                    aria-labelledby="dropdownMenuLink15">
                    <li><a class="dropdown-item"
                            href="javascript:void(0);"><i
                                class="ri-eye-fill me-2 align-middle text-muted"></i>View</a>
                    </li>
                    <li><a class="dropdown-item"
                            href="javascript:void(0);"><i
                                class="ri-download-2-fill me-2 align-middle text-muted"></i>Download</a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li><a class="dropdown-item"
                            href="javascript:void(0);"><i
                                class="ri-delete-bin-5-line me-2 align-middle text-muted"></i>Delete</a>
                    </li>
                </ul>
            </div> --}}
        </td>
    </tr>
    @endforeach
