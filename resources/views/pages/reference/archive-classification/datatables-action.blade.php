@if ($buttons['edit'])
    <a href="{{ route('reference.archive-classifications.edit', $archiveClassification) }}" class="btn btn-sm btn-warning">
        Edit
    </a>
@endif
@if ($buttons['delete'])
    <button type="submit" class="btn btn-sm btn-danger" form="delete-{{ $archiveClassification->id }}" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
        Hapus
    </button>
    <form id="delete-{{ $archiveClassification->id }}" action="{{ route('reference.archive-classifications.destroy', $archiveClassification) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endif
