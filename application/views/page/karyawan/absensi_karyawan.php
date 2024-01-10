<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi karyawan</title>
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/css/dashboard.css" rel="stylesheet">
    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,500&display=swap"
        rel="stylesheet">
</head>

<body class="text-capitalize" style="font-family: 'Poppins', sans-serif;">
    <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
        <!-- sidebar -->
        <?php $this->load->view('component/sidebar'); ?>
        <div class="h-screen flex-grow-1 overflow-y-lg-auto">
            <!-- Header -->
            <header class="bg-surface-primary border-bottom pt-6">
                <div class="container-fluid">
                    <div class="mb-npx">
                        <div class="row align-items-center">
                            <div class="col-sm-6 col-12 mb-4 mb-sm-0">
                                <!-- Title -->
                                <h1 class="h2 mb-0 ls-tight">
                                    <img src="https://bytewebster.com/img/logo.png" width="40"> Absensi karyawan
                                </h1>
                            </div>
                        </div>
                        <!-- Nav -->
                        <ul class="nav nav-tabs mt-4 overflow-x border-0">
                        </ul>
                    </div>
                </div>
            </header>
            <!-- start table absensi karyawan -->
            <main class="py-6 bg-surface-secondary">
                <div class="container-fluid">
                    <?php if ($this->session->userdata('role') === "admin"): ?>
                        <form class="d-flex" style="gap:10px" action="<?php echo base_url('page/absensi_karyawan') ?>"
                            method="post">
                            <input type="search" name="search_keyword" class="form-control"
                                placeholder="Cari Nama Depan Karyawan...">
                            <button class="btn btn btn-primary" type="submit" name="submit">Cari</button>
                        </form>
                    <?php endif ?>
                    <br>
                    <div class="card shadow border-0 mb-7">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-0">Absensi</h5>
                                <?php if ($this->session->userdata('role') === "admin"): ?>
                                    <button class="btn btn-sm btn-primary"><a
                                            href="<?php echo base_url('page/export_absensi_all') ?>"
                                            class="text-decoration-none text-light">Export</a></button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-nowrap">
                                <thead class="thead-light text-capitalize">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama Karyawan</th>
                                        <th scope="col">Kegiatan</th>
                                        <th scope="col">Tanggal Absen</th>
                                        <th scope="col">Jam Masuk</th>
                                        <th scope="col">Jam Pulang</th>
                                        <th scope="col">Keterangan Izin</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    <?php $no = 0;
                                    foreach ($karyawan as $row):
                                        $no++ ?>
                                        <tr>
                                            <td>
                                                <?php echo $no ?>
                                            </td>
                                            <td>
                                                <?php echo $row->nama_depan . ' ' . $row->nama_belakang ?>
                                            </td>
                                            <td>
                                                <?php echo $row->kegiatan ?>
                                            </td>
                                            <td>
                                                <?php echo $row->date ?>
                                            </td>
                                            <?php
                                            date_default_timezone_set('Asia/Jakarta');
                                            $seven_am = strtotime(date('Y-m-d 09:00:00'));
                                            $jam_masuk = strtotime($row->jam_masuk);
                                            ?>
                                            <td
                                                class="<?php echo ($jam_masuk > $seven_am) ? 'text-danger' : 'text-black'; ?>">
                                                <?php echo $row->jam_masuk; ?>
                                            </td>

                                            <td>
                                                <?php echo $row->jam_pulang ?>
                                            </td>
                                            <td>
                                                <?php echo $row->keterangan_izin ?>
                                            </td>
                                            <td>
                                                <?php echo $row->status ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($this->session->userdata('role') === "karyawan"): ?>
                                                    <?php if ($row->status == "done"): ?>
                                                        <button type="button" class="btn btn-sm btn-secondary text-danger-hover"
                                                            disabled><a class="text-white text-decoration-none">
                                                                <i class="fas fa-home"></i></a>
                                                        </button>
                                                    <?php elseif ($row->keterangan_izin != "-"): ?>
                                                        <button type="button" class="btn btn-sm btn-secondary text-danger-hover"
                                                            disabled><a class="text-white text-decoration-none">
                                                                <i class="fas fa-home"></i></a>
                                                        </button>
                                                    <?php else: ?>
                                                        <?php
                                                        // Mendapatkan waktu saat ini
                                                        $current_time = time();
                                                        $current_time_formatted = date('H:i', $current_time); // Format jam dan menit
                                            
                                                        // Mengatur waktu batas (17.00 siang dalam format jam dan menit)
                                                        $deadline_time = '17:00';

                                                        // Memeriksa apakah waktu saat ini kurang dari waktu batas
                                                        if ($current_time_formatted < $deadline_time) {
                                                            echo '
                                                                <button type="button" onclick="belum_bisa_pulang()" class="btn btn-sm btn-warning">
                                                                        <i class="fas fa-home"></i>
                                                                </button>';
                                                        } else {
                                                            echo '
                                                            <button type="button" class="btn btn-sm btn-warning text-danger-hover">
                                                            <a class="text-light text-decoration-none"
                                                                href="' . base_url("page/pulang/" . $row->id) . '">
                                                                <i class="fas fa-home"></i>
                                                            </a>
                                                            </button>';
                                                        }
                                                        ?>
                                                    <?php endif; ?>
                                                    <?php
                                                    // Menghitung selisih waktu antara waktu sekarang dan waktu dalam data
                                                    $current_time = time(); // Waktu sekarang dalam bentuk timestamp
                                                    $data_time = strtotime($row->date); // Waktu dalam data dalam bentuk timestamp
                                                    $time_difference = $current_time - $data_time; // Selisih waktu
                                            
                                                    // Mengatur batas waktu (dalam detik) untuk menonaktifkan tombol (misalnya 24 jam = 24 * 3600 detik)
                                                    $threshold = 24 * 3600;

                                                    // Mengecek apakah selisih waktu lebih besar dari batas waktu
                                                    if ($time_difference > $threshold) {
                                                        $button_disabled = 'disabled';
                                                    } else {
                                                        $button_disabled = ''; // Tombol aktif
                                                    }
                                                    ?>
                                                    <button type="button"
                                                        class="btn btn-sm btn-square btn-primary text-danger-hover-none" <?php echo $button_disabled; ?>>
                                                        <a class="text-light text-decoration-none"
                                                            href="<?php echo base_url('page/edit_kegiatan/' . $row->id) ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </button>

                                                <?php endif; ?>
                                                <?php if ($this->session->userdata('role') == "admin"): ?>
                                                    <button type="button" onclick="hapus(<?php echo $row->id ?>)"
                                                        class="btn btn-sm btn-square btn-danger text-danger-hover-none">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- end tabel absensi karyawan -->
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/js/script.js" rel="stylesheet">

    <!-- sweet alert hapus data absensi karyawan -->
    <script>
        function hapus(id) {
            swal.fire({
                title: 'Yakin untuk menghapus data ini?',
                icon: 'warning',
                background: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya Hapus', customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil Dihapus',
                        showConfirmButton: false,
                        background: '#fff',
                        timer: 1500, customClass: {
                            title: 'text-dark',
                            content: 'text-dark'
                        }

                    }).then(function () {
                        window.location.href = "<?php echo base_url('page/hapus_absensi_karyawan/') ?>" + id;
                    });
                }
            });
        }
    </script>

    <script>
        function belum_bisa_pulang() {
            Swal.fire({
                icon: 'error',
                title: 'Belum Bisa Pulang',
                text: 'Anda Belum Bisa Pulang Sebelum Jam 12.00',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        }
    </script>

    <!-- sweet alert jika berhasil melakukan pulang -->
    <?php if ($this->session->flashdata('berhasil_pulang')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $this->session->flashdata('berhasil_pulang') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- script bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>