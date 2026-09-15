@extends('layouts.app')

@section('title', 'My Drive')

@section('content')

<div class="drive-header">

    <div>
        <h1>
            {{ isset($searchQuery) ? 'Search Results' : 'My Drive' }}
        </h1>

        @isset($searchQuery)
            <p>
                Results for "{{ $searchQuery }}"
            </p>
        @endisset
    </div>

</div>

@if(session('success'))

    <div class="success-message">
        {{ session('success') }}
    </div>

@endif

@if($errors->any())
    <div class="error-message">
        {{ $errors->first() }}
    </div>
@endif


<div class="upload-section">

    <form
        method="POST"
        action="{{ route('drive.files.store') }}"
        enctype="multipart/form-data"
    >

        @csrf

        <input
            type="file"
            name="file"
            required
        >

        @if($folderId)
            <input
                type="hidden"
                name="folder_id"
                value="{{ $folderId }}"
            >
        @endif

        <button type="submit">
            Upload File
        </button>

    </form>

</div>


<form
    method="POST"
    action="{{ route('drive.folders.store') }}"
>
    @csrf

    <input
        type="text"
        name="name"
        placeholder="New folder name"
        required
    >

    @if($folderId)
        <input
            type="hidden"
            name="parent_folder_id"
            value="{{ $folderId }}"
        >
    @endif

    <button type="submit">
        Create Folder
    </button>
</form>

<div class="files-section">

    <h2>Files & Folders</h2>

    <table class="file-table">

        <thead>

        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Modified</th>
        </tr>

        </thead>

        <tbody>

        @forelse($files as $file)

            @php
                $isFolder =
                    $file->getMimeType()
                    === 'application/vnd.google-apps.folder';
            @endphp

            <tr>

                <td>

                    @if($isFolder)

                        

                        <a
                            href="{{ route('drive.index', [
                                'folder' => $file->getId()
                            ]) }}"
                        >
                            {{ $file->getName() }}
                        </a>

                    @else

                         {{ $file->getName() }}

                    @endif

                </td>

                <td>
                    {{ $isFolder ? 'Folder' : $file->getMimeType() }}
                </td>

                <td>
                    {{ $file->getModifiedTime() }}
                </td>

                <td>

                    @if(!$isFolder && $file->getWebViewLink())

                    <a
                        href="{{ $file->getWebViewLink() }}"
                        target="_blank"
                        rel="noopener"
                    >
                        Open
                    </a>

                    @endif
                    
                    <form
                    method="POST"
                    action="{{ route('drive.files.destroy', $file->getId()) }}"
                    style="display:inline;"
                    onsubmit="return confirm('Delete this item?')"
                    >
                    @csrf
                    @method('DELETE')

                    <button type="submit">
                        Delete
                    </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('drive.files.rename', $file->getId()) }}"
                        style="display:inline;"
                        >
                        @csrf
                        @method('PATCH')

                        <input
                            type="text"
                            name="name"
                            value="{{ $file->getName() }}"
                            required
                        >

                        <button type="submit">
                            Rename
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('drive.files.share', $file->getId()) }}"
                        style="display:inline;"
                    >
                        @csrf

                        <input
                            type="email"
                            name="email"
                            placeholder="Google account email"
                            required
                        >

                        <button type="submit">
                            Share
                        </button>
                    </form>
                </td>

            </tr>

        @empty

            <tr>
                <td colspan="3">
                    No files found.
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection