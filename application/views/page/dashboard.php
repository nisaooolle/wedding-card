<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,500&display=swap"
        rel="stylesheet">
</head>

<body style="font-family: 'Poppins', sans-serif;">
    <!-- Dashboard -->
    <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
        <!-- Vertical Navbar -->
        <?php $this->load->view('component/sidebar'); ?>
        <!-- Main content -->
        <div class="h-screen flex-grow-1 overflow-y-lg-auto">
            <!-- Header -->
            <?php $this->load->view('component/header'); ?>
            <!-- Main -->
            <main class="py-6 bg-surface-secondary">
                <div class="container-fluid">
                    <!-- Card stats -->
                    <div class="row g-6 mb-6">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                Kerja</span>
                                            <span class="h3 font-bold mb-0">
                                                <?php echo $total_kerja ?>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-tertiary text-white text-lg rounded-circle">
                                                <i class="far fa-calendar-minus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                Cuti</span>
                                            <span class="h3 font-bold mb-0">
                                                <?php echo $total_cuti ?>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-primary text-white text-lg rounded-circle">
                                                <i class="far fa-calendar-minus"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card shadow border-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                Absensi</span>
                                            <span class="h3 font-bold mb-0">
                                                <?php echo $total_data ?>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-info text-white text-lg rounded-circle">
                                                <i class="fas fa-database"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="col-xl-3 col-sm-6 col-12">
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <span class="h6 font-semibold text-muted text-sm d-block mb-2">Total
                                                    Terlambat</span>
                                                <span class="h3 font-bold mb-0">
                                                    <?php echo $total_telat ?>
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <div class="icon icon-shape bg-danger text-white text-lg rounded-circle">
                                                    <i class="fa-solid fa-clock"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card shadow border-0 mb-7">
                        <div class="card-header">
                            <h5 class="mb-0">Absensi</h5>
                        </div>
                        <div class="table-responsive">
                            <!-- start table absensi-->
                            <table class="table table-hover table-nowrap">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Nama karyawan</th>
                                        <th scope="col">Kegiatan</th>
                                        <th scope="col">Tanggal absen</th>
                                        <th scope="col">Jam masuk</th>
                                        <th scope="col">Jam pulang</th>
                                        <th scope="col">Keterangan izin</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    <?php $no = 0;
                                    foreach ($dashboard as $row):
                                        $no++ ?>
                                        <tr>
                                            <td>
                                                <?php echo $no ?>
                                            </td>
                                            <td>
                                                <?php echo $row->nama_depan . '' . $row->nama_belakang ?>
                                            </td>
                                            <td>
                                                <?php echo $row->kegiatan ?>
                                            </td>
                                            <td>
                                                <?php echo $row->date ?>
                                            </td>
                                            <?php
                                            date_default_timezone_set('Asia/Jakarta');
                                            $seven_am = strtotime(date('Y-m-d 07:00:00'));
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
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- end table absensi-->
                        </div>
                    </div>
                    <?php if ($this->session->userdata('role') === "admin"): ?>
                        <div class="card shadow border-0 mb-7">
                            <div class="card-header px-3">
                                <h5 class="mb-0">User</h5>
                            </div>
                            <div class="table-responsive">
                                <!-- start tabel user -->
                                <table class="table table-hover table-nowrap">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Nama depan</th>
                                            <th scope="col">Nama belakang</th>
                                            <th scope="col">image</th>
                                            <th scope="col">Email</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-capitalize">
                                        <?php $no = 0;
                                        foreach ($get_user as $row):
                                            $no++ ?>
                                            <tr>
                                                <td>
                                                    <?php echo $no ?>
                                                </td>
                                                <td>
                                                    <?php echo $row->username ?>
                                                </td>
                                                <td>
                                                    <?php echo $row->nama_depan ?>
                                                </td>
                                                <td>
                                                    <?php echo $row->nama_belakang ?>
                                                </td>
                                                <td>
                                                    <?php if ($row->image == null): ?>

                                                        <img alt="..."
                                                            src="<?php echo base_url('/asset/FlexStart/') ?>assets/img/user.avif"
                                                            style="width:80px" class="rounded-circle">
                                                    <?php else: ?>
                                                        <img style="width:80px; border-radius:50%"
                                                            src="<?= base_url('images/user/' . $row->image) ?>" alt="">
                                                    <?php endif; ?>

                                                </td>
                                                <td>
                                                    <?php echo $row->email ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- end tabel user -->
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
        </div>
        </main>
    </div>
    </div>
    <!-- script bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/js/script.js" rel="stylesheet">
</body>

</html>