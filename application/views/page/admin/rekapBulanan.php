<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data absensi harian</title>
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,500&display=swap"
        rel="stylesheet">
</head>

<body class="text-capitalize" style="font-family: 'Poppins', sans-serif;">
    <!-- kondisi jika tidak berole admin maka tidak bisa melihat page ini -->
    <?php if ($this->session->userdata('role') === "admin"): ?>
        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
            <!-- sidebar -->
            <?php $this->load->view('component/sidebar'); ?>
            <div class="h-screen flex-grow-1 overflow-y-lg-auto">
                <!-- header -->
                <?php $this->load->view('component/header'); ?>
                <main class="py-6 bg-surface-secondary">
                    <!-- form yang berisi select untuk memilih bulan  -->
                    <div class="container-fluid">
                        <form class="d-flex" style="gap:10px" method="post" id="exportForm">
                            <select class="form-select form-select-sm" name="bulan" id="bulan"
                                aria-label="Small select example">
                                <option value="">Pilih Bulan</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select> <button type="submit" name="submit" class="btn btn-sm btn-primary"
                                formaction="<?php echo base_url('page/export_absensi_bulanan') ?>">Export</button>
                            <button type="submit" name="submit" class="btn btn-sm btn-primary"
                                formaction="<?php echo base_url('page/rekapBulanan') ?>">Absensi</button>
                        </form>
                        <!-- end form -->
                        <br>
                        <div class="card shadow border-0 mb-7">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0">Data perbulan</h5>
                                </div>

                            </div>
                            <div class="table-responsive">
                                <!-- start tabel -->
                                <table class="table table-hover table-nowrap">
                                    <thead class="thead-light">
                                        <?php if (!empty($this->input->post('bulan'))): ?>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama karyawan</th>
                                                <th scope="col">Kegiatan</th>
                                                <th scope="col">Tanggal absen</th>
                                                <th scope="col">Jam masuk</th>
                                                <th scope="col">Jam pulang</th>
                                                <th scope="col">Keterangan izin</th>
                                                <th scope="col">Status</th>
                                                <?php if ($this->session->userdata('role') == "karyawan"): ?>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endif; ?>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($this->input->post('bulan'))): ?>
                                            <?php $no = 0;
                                            foreach ($rekapBulanan as $row):
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
                                                    <?php if ($this->session->userdata('role') == "karyawan"): ?>
                                                        <td class="text-end">
                                                            <?php if ($row->status == "done"): ?>
                                                                <button type="button" class="btn btn-sm btn-secondary text-danger-hover"
                                                                    disabled><a class="text-white text-decoration-none">
                                                                        Pulang</a>
                                                                </button>
                                                            <?php elseif ($row->keterangan_izin != "-"): ?>
                                                                <button type="button" class="btn btn-sm btn-secondary text-danger-hover"
                                                                    disabled><a class="text-white text-decoration-none">
                                                                        Pulang</a>
                                                                </button>
                                                            <?php else: ?>
                                                                <button type="button" class="btn btn-sm btn-warning text-danger-hover"><a
                                                                        class="text-black text-decoration-none"
                                                                        href="<?php echo base_url('page/pulang/' . $row->id) ?>">
                                                                        Pulang</a>
                                                                </button>
                                                            <?php endif; ?>
                                                            <button type="button"
                                                                class="btn btn-sm btn-square btn-primary text-danger-hover-none"><a
                                                                    class="text-light text-decoration-none"
                                                                    href="<?php echo base_url('page/edit_kegiatan/' . $row->id) ?>">
                                                                    <i class="fas fa-edit"></i></a>
                                                            </button>
                                                            <button type="button" onclick="hapus(<?php echo $row->id ?>)"
                                                                class="btn btn-sm btn-square btn-danger text-danger-hover-none">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <h5> Pilih bulan terlebih dahulu untuk menampilkan data nya</h5>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <!-- end tabel -->
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    <?php endif; ?>
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/js/script.js" rel="stylesheet">
    <!-- sweet
     alert jika export data gagal -->
    <?php if ($this->session->flashdata('error_export_perbulan')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Uppss...',
                text: '<?= $this->session->flashdata('error_export_perbulan') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var selectElement = document.getElementById('bulan');
            var formElement = selectElement.form;

            selectElement.addEventListener('change', function () {
                formElement.submit();
            });
        });
    </script>
    <script>
        function setAction(action) {
            // Setel nilai input dengan name "action" sesuai dengan tindakan yang dipilih
            document.querySelector('#exportForm input[name="action"]').value = action;
        }
    </script>
</body>

</html>