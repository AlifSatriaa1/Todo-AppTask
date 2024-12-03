@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Hero Section -->
    <div class="text-center mb-4">
        <h1 class="display-4 fw-bold text-primary mb-3">Managament Tugas</h1>
        <p class="lead text-muted">Membantu anda dalam mengatur tugas sekolah </p>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Tugas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $tasks->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tasks->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $tasks->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="h5 mb-0 text-gray-800">
                    <i class="fas fa-tasks me-2"></i>Daftar Tugas
                </h2>
                <a href="{{ route('task.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Tugas
                </a>
            </div>
            
            <form action="{{ route('task.index') }}" method="GET" class="mt-3">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="fas fa-search text-gray-600"></i>
                    </span>
                    <input type="text" name="search" 
                        placeholder="Cari berdasarkan judul atau deskripsi..." 
                        class="form-control border-start-0" 
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('task.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-body">
            @if($tasks->isEmpty())
                <div class="text-center py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" 
                         alt="No Tasks" class="img-fluid mb-3" style="max-width: 200px">
                    <h3 class="h5 text-muted">Belum ada tugas</h3>
                    <p class="text-muted">Mulai tambahkan tugas baru sekarang!</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4">Judul</th>
                                <th class="px-4">Deskripsi</th>
                                <th class="px-4">Status</th>
                                <th class="px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                <tr>
                                    <td class="px-4">{{ $task->title }}</td>
                                    <td class="px-4">{{ $task->description }}</td>
                                    <td class="px-4" style="width: 150px;">
                                        <form action="{{ route('task.updateStatus', $task->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" 
                                                class="form-select form-select-sm {{ $task->status == 'completed' ? 'bg-success text-white' : 'bg-warning text-dark' }}"
                                                onchange="this.form.submit()">
                                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>
                                                    🕒 Pending
                                                </option>
                                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
                                                    ✅ Completed
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-4">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('task.edit', $task->id) }}" 
                                               class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('task.destroy', $task->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #4e73df !important;
}
.border-left-success {
    border-left: 4px solid #1cc88a !important;
}
.border-left-warning {
    border-left: 4px solid #f6c23e !important;
}
.card {
    transition: transform 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection

