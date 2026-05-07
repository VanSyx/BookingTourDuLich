@include('admin.blocks.header')
<div class="container body">
    <div class="main_container">
        @include('admin.blocks.sidebar')

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="page-wrapper">
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Quản lý Lịch khởi hành</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Chọn Tour --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.tour-schedules') }}" class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chọn Tour</label>
                            <select name="tourId" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Chọn tour --</option>
                                @foreach($tours as $t)
                                    <option value="{{ $t->tourId }}" {{ $tourId == $t->tourId ? 'selected' : '' }}>
                                        {{ $t->title }} (ID: {{ $t->tourId }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            @if($selectedTour)
            {{-- Form thêm lịch mới --}}
            <div class="card mb-4">
                <div class="card-header"><h3 class="card-title">➕ Thêm lịch khởi hành — {{ $selectedTour->title }}</h3></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.tour-schedules.store') }}">
                        @csrf
                        <input type="hidden" name="tourId" value="{{ $selectedTour->tourId }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Ngày khởi hành *</label>
                                <input type="date" name="startDate" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ngày kết thúc *</label>
                                <input type="date" name="endDate" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Giá người lớn (VNĐ) *</label>
                                <input type="number" name="priceAdult" class="form-control" min="0" value="{{ $selectedTour->priceAdult }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Giá trẻ em (VNĐ) *</label>
                                <input type="number" name="priceChild" class="form-control" min="0" value="{{ $selectedTour->priceChild }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Số chỗ *</label>
                                <input type="number" name="quantity" class="form-control" min="1" value="50" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Ghi chú</label>
                                <input type="text" name="note" class="form-control" placeholder="VD: Khuyến mãi, Mùa hè...">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Thêm lịch</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danh sách lịch --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">📅 Danh sách lịch khởi hành ({{ count($schedules) }} lịch)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ngày khởi hành</th>
                                <th>Ngày kết thúc</th>
                                <th>Giá NL (VNĐ)</th>
                                <th>Giá TE (VNĐ)</th>
                                <th>Số chỗ</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $s)
                            <tr>
                                <td>{{ $s->scheduleId }}</td>
                                <td>
                                    <span class="badge {{ \Carbon\Carbon::parse($s->startDate)->isPast() ? 'bg-secondary' : 'bg-success' }}">
                                        {{ \Carbon\Carbon::parse($s->startDate)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($s->endDate)->format('d/m/Y') }}</td>
                                <td>{{ number_format($s->priceAdult, 0, ',', '.') }}</td>
                                <td>{{ number_format($s->priceChild, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $s->quantity > 0 ? 'bg-green' : 'bg-red' }}">{{ $s->quantity }}</span>
                                </td>
                                <td>{{ $s->note ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1"
                                        onclick="editSchedule({{ json_encode($s) }})">Sửa</button>
                                    <form method="POST" action="{{ route('admin.tour-schedules.destroy') }}" style="display:inline"
                                        onsubmit="return confirm('Xóa lịch này?')">
                                        @csrf
                                        <input type="hidden" name="scheduleId" value="{{ $s->scheduleId }}">
                                        <input type="hidden" name="tourId" value="{{ $selectedTour->tourId }}">
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Chưa có lịch khởi hành nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal sửa lịch --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.tour-schedules.update') }}">
            @csrf
            <input type="hidden" name="scheduleId" id="edit_scheduleId">
            <input type="hidden" name="tourId" value="{{ $tourId }}">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title">Sửa lịch khởi hành</h5></div>
                <div class="modal-body row g-3">
                    <div class="col-6">
                        <label class="form-label">Ngày khởi hành</label>
                        <input type="date" name="startDate" id="edit_startDate" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" name="endDate" id="edit_endDate" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Giá người lớn</label>
                        <input type="number" name="priceAdult" id="edit_priceAdult" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Giá trẻ em</label>
                        <input type="number" name="priceChild" id="edit_priceChild" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Số chỗ</label>
                        <input type="number" name="quantity" id="edit_quantity" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ghi chú</label>
                        <input type="text" name="note" id="edit_note" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editSchedule(s) {
    document.getElementById('edit_scheduleId').value  = s.scheduleId;
    document.getElementById('edit_startDate').value   = s.startDate;
    document.getElementById('edit_endDate').value     = s.endDate;
    document.getElementById('edit_priceAdult').value  = s.priceAdult;
    document.getElementById('edit_priceChild').value  = s.priceChild;
    document.getElementById('edit_quantity').value    = s.quantity;
    document.getElementById('edit_note').value        = s.note || '';
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
        </div>
        <!-- /page content -->
    </div>
</div>
@include('admin.blocks.footer')
