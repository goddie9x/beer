<div class="alert alert-danger"
    {{ isset($data->is_mailing)
        ? 'style="color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
        padding: 1rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }"'
        : '' }}>
    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i>{{ $data->device_name }}
        is reach to the threshold</h4>
    <p>{{ $data->message }}</p>
    <p>{{ $data->device_name }} is {{ $data->status }} than the threshold {{ $data->outThreshold }} with value =
        {{ $data->value }}</p>
    <hr>
    <p class="mb-0">{{ $data->created_at }}</p>
</div>
