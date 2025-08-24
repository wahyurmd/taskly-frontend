<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script>
    $(document).ready(function () {
        let table_task = $('#task_list').DataTable({
            pagingType: 'full_numbers',
            processing: true,
            serverSide: true,
            ajax: {
                url: API_BASE_URL + "/tasks-table",
                type: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.user_id = "{{ session('user.id') }}";
                    let status = $('#filterStatus').val();
                    if (status !== "") {
                        d.status = status;
                    }
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader("Authorization", "Bearer {{ session('access_token') }}");
                }
            },
            columns: [
                { data: "title" },
                { data: "description" },
                { data: "plan_date" },
                {
                    data: "status",
                    render: function(data) {
                        return data == 1
                            ? '<span class="badge badge-success w-100">Completed</span>'
                            : '<span class="badge badge-danger w-100">Not Completed</span>';
                    }
                },
                {
                    data: "id", // ambil ID task dari API
                    render: function (data, type, row) {
                        return `
                            <a href="/tasks/${data}/edit" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Update
                            </a>
                            <form action="/tasks/${data}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus task ini?');">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        `;
                    },
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
</script>
