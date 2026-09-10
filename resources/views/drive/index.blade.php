@extends('layouts.app')

@section('title', 'My Drive')

@section('content')

<div class="drive-header">

    <h1>My Drive</h1>

</div>


<div class="folders-section">

    <h2>Folders</h2>

    <div class="folder-grid">

        <div class="folder-card">
            
            <span>Work</span>
        </div>

        <div class="folder-card">
            
            <span>Photos</span>
        </div>

        <div class="folder-card">
            
            <span>Projects</span>
        </div>

    </div>

</div>


<div class="files-section">

    <h2>Files</h2>

    <table class="file-table">

        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Modified</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td>📄 Report.pdf</td>
                <td>PDF</td>
                <td>Sep 10</td>
            </tr>

            <tr>
                <td>📊 Budget.xlsx</td>
                <td>Excel</td>
                <td>Sep 9</td>
            </tr>

            <tr>
                <td>📝 Notes.docx</td>
                <td>Document</td>
                <td>Sep 8</td>
            </tr>

        </tbody>

    </table>

</div>

@endsection