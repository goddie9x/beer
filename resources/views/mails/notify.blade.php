<div class="alert alert-danger">
    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i>{{ $data->device_name }}
        is reach to the threshold</h4>
    <p>message</p>
    <p>{{ $data->device_name }} is {{ $data->status }} than {{ $data->outThreshold }} with value = {{ $data->value }}</p>
    <hr>
    <p class="mb-0">{{ $data->created_at }}</p>
</div>