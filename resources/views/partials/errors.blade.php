@if ($errors->any())
    <div class="alert alert-danger">
        <strong><i class="bi bi-exclamation-triangle"></i> Periksa kembali input Anda:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif