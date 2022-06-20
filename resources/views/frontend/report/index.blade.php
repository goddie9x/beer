@extends('frontend.layout.main') @section('content')
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 my-2">
            <label for="file-select">File:</label>
        </div>
        <div class="col-12 col-md-6 my-2"> 
            <select class="form-control select2 without-location" id="file-select" name="file-select">
                <option value="">Chọn File</option>
                @foreach ($filesInfo as $file)
                    <option value="{{ $file['path'] }}">{{ $file['name'] }}</option>
                @endforeach
            </select></div>
        <div class="col-12 col-md-6 my-2">
            <label for="file-name">File name:</label>
        </div>
        <div class="col-12 col-md-6 my-2">
            <input type="text" class="form-control" id="file-name" name="file-name" placeholder="Nhập tên file sau khi xuất" required>
        </div>
    </div>
    <button class="btn btn-primary start-download">
            <i class="fa fa-download"></i>
            Tải file
        </button>
</div>
@endsection @section('js')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
    function downloadExcelFile(path,fileName) {
        fetch('{{route('frontend.report.getFileByPath')}}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        path: path,
                        name:fileName,
                        "_token": "{{ csrf_token() }}",
                    }),
                })
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = path.split('/').pop();
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            });
    }
    $('.start-download').unbind().click(function() {
        const path = $('#file-name').val();
        const fileName = $('#file-select').val();
        if (path == '') {
            alert('Chưa chọn file');
            return;
        }
        downloadExcelFile(path, fileName);
    });
</script>
@endsection