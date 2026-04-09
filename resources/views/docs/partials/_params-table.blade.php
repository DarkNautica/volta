<div class="overflow-x-auto mb-6">
    <table>
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Type</th>
                <th>Required</th>
                <th>Default</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($params as $param)
                <tr>
                    <td><code>{{ $param['name'] }}</code></td>
                    <td><code>{{ $param['type'] }}</code></td>
                    <td>{{ $param['required'] ? 'Yes' : 'No' }}</td>
                    <td>{{ $param['default'] ?? '—' }}</td>
                    <td>{{ $param['description'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
