{{-- @extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <h2 class="page-title">
            Data siswa
          </h2>
        </div>
    </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @if (Session::get('success'))
                                <div class="alert alert-success">
                                    {{ Session::get('success')}}
                                </div>
                                @endif

                                @if (Session::get('warning'))
                                <div class="alert alert-warning">
                                    {{ Session::get('warning')}}
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-primary" id="btnTambahsiswa">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Tambah data</a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <form action="/siswa" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="nama_siswa" id="nama_siswa" class="form-control"
                                                placeholder="Nama siswa" value="{{ Request('nama_siswa') }}">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <select name="kode_jurusan" id="kode_jurusan" class="form-select">
                                                    <option value="">Jurusan</option>
                                                    @foreach ($jurusan as $d)
                                                    <option {{ Request('kode_jurusan') == $d->kode_jurusan ? 'selected' : ''}}
                                                        value="{{ $d->kode_jurusan }}">{{ $d->nama_jurusan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                        <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                                        <path d="M21 21l-6 -6" />
                                                    </svg>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nis</th>
                                            <th>Nama Lengkap</th>
                                            <th>Kelas</th>
                                            <th>No. Hp</th>
                                            <th>Foto</th>
                                            <th>Jurusan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswa as $d)
                                        @php
                                            $path = Storage::url('uploads/siswa/'.$d->foto);
                                        @endphp
                                            <tr>
                                                <td>{{ $loop->iteration + $siswa->firstItem()-1 }}</td>
                                                <td>{{ $d->nis }}</td>
                                                <td>{{ $d->nama_lengkap }}</td>
                                                <td>{{ $d->kelas }}</td>
                                                <td>{{ $d->no_hp }}</td>
                                                <td>
                                                    @if (empty($d->foto))
                                                    <img src="{{ asset('assets/img/no_photo.png')}}" class="avatar" alt="">
                                                    @else
                                                    <img src="{{ url($path) }}" class="avatar" alt="">
                                                    @endif
                                                </td>
                                                <td>{{ $d->nama_jurusan }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="#" class="edit btn btn-success btn-sm" nis="{{ $d->nis }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                                <path d="M16 5l3 3" />
                                                            </svg>
                                                        </a>
                                                        <form action="/siswa/{{ $d->nis }}/delete" method="POST" style="margin-left: 5px">
                                                        @csrf
                                                        <a href="" class="btn btn-danger btn-sm delete-confirm">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M4 7l16 0" />
                                                                <path d="M10 11l0 6" />
                                                                <path d="M14 11l0 6" />
                                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                            </svg>
                                                        </a>
                                                        </form>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $siswa->links("vendor.pagination.bootstrap-5")}}
                            </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <div class="modal modal-blur fade" id="modal-inputsiswa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah data siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/siswa/store" method="POST" id="frmSiswa" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-barcode" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                                    <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                                    <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                                    <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                                    <path d="M5 11h1v2h-1z" />
                                    <path d="M10 11l0 2" />
                                    <path d="M14 11h1v2h-1z" />
                                    <path d="M19 11l0 2" />
                                </svg>
                            </span>
                            <input type="text" value="" id="nis" name="nis" class="form-control" placeholder="NIS">
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                </svg>
                            </span>
                            <input type="text" value="" id="nama_lengkap" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap">
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-backpack" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 18v-6a6 6 0 0 1 6 -6h2a6 6 0 0 1 6 6v6a3 3 0 0 1 -3 3h-8a3 3 0 0 1 -3 -3z" />
                                    <path d="M10 6v-1a2 2 0 1 1 4 0v1" />
                                    <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4" />
                                    <path d="M11 10h2" />
                                </svg>
                            </span>
                            <input type="text" value="" name="kelas" id="kelas" class="form-control" placeholder="Kelas">
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-phone" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" />
                                </svg>
                            </span>
                            <input type="text" value="" name="no_hp" id="no_hp" class="form-control" placeholder="Nomor Hp">
                          </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <input type="file" name="foto" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <select name="kode_jurusan" id="kode_jurusan" class="form-select">
                            <option value="">Jurusan</option>
                            @foreach ($jurusan as $d)
                            <option {{ Request('kode_jurusan') == $d->kode_jurusan ? 'selected' : ''}}
                                value="{{ $d->kode_jurusan }}">{{ $d->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <div class="form-group">
                            <button class="btn btn-primary w-100">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>
{{-- Modal Edit --}}
  {{-- <div class="modal modal-blur fade" id="modal-editsiswa" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit data siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="loadeditform">

        </div>
      </div>
    </div>
  </div>
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnTambahsiswa").click(function() {
            $("#modal-inputsiswa").modal("show")
        });

        $(".edit").click(function() {
            var nis = $(this).attr('nis');
            $.ajax({
                type: 'POST',
                url: '/siswa/edit',
                cache: false,
                data: {
                    _token: "{{ csrf_token(); }}",
                    nis: nis
                },
                success:function(respond){
                    $("#loadeditform").html(respond);
                }
            });
            $("#modal-editsiswa").modal("show")
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
            title: "Apakah Anda yakin menghapus data ini?",
            text: "Data ini tidak dapat dipulihkan",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, saya yakin"
            }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
                Swal.fire({
                title: "Dihapus",
                text: "Data berhasil dihapus.",
                icon: "success"
                });
            }
            });
        });

        $("#frmSiswa").submit(function() {
            var nis = $("#nis").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var kelas = $("#kelas").val();
            var no_hp = $("#no_hp").val();
            var kode_jurusan = $("#frmSiswa").find("#kode_jurusan").val();
            if(nis == "") {
                // alert ('Nis harus diisi');
                Swal.fire({
                title: 'Ooops!',
                text: 'NIS harus diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#nis").focus();
            })
                return false;
            } else if(nama_lengkap == "") {
                Swal.fire({
                title: 'Ooops!',
                text: 'Nama lengkap harus diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#nama_lengkap").focus();
            })
                return false;
            }else if(kelas == "") {
                Swal.fire({
                title: 'Ooops!',
                text: 'Kelas harus diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#kelas").focus();
            })
                return false;
            }else if(no_hp == "") {
                Swal.fire({
                title: 'Ooops!',
                text: 'No. Hp harus diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#no_hp").focus();
            })
                return false;
            }else if(kode_jurusan == "") {
                Swal.fire({
                title: 'Ooops!',
                text: 'Jurusan harus diisi',
                icon: 'warning',
                confirmButtonText: 'Ok'
            }).then((result) => {
                $("#kode_jurusan").focus();
            })
                return false;
            }
        });
    });
</script>
@endpush --}}
