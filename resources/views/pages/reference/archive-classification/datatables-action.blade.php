@if ($buttons['edit'])
    <a href="{{ route('reference.archive-classifications.edit', $archiveClassification) }}" class="btn btn-sm btn-warning">
        Edit
    </a>
@endif
@if ($buttons['delete'])
    <button type="submit" class="btn btn-sm btn-danger" form="#delete-{{ $archiveClassification->id }}" onclick="return confirm('Are you sure you want to delete this item?')">
        Hapus
    </button>
    <form id="delete-{{ $archiveClassification->id }}" action="{{ route('reference.archive-classifications.destroy', $archiveClassification) }}" method="POST">
        @csrf
        @method('DELETE')
    </form>
@endif
