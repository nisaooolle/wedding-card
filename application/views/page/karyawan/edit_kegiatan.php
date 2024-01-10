<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/css/dashboard.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Edit Kegiatan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,500&display=swap" rel="stylesheet">
</head>

<body style="font-family: 'Poppins', sans-serif;">
    <!-- kondisi jika tidak role karyawan maka tidak bisa melihat page ini -->
    <?php if ($this->session->userdata('role') === "karyawan"): ?>
        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
            <!-- sidebar -->
            <?php $this->load->view('component/sidebar'); ?>
            <div class="h-screen flex-grow-1 overflow-y-lg-auto">
                <!-- header -->
                <?php $this->load->view('component/header'); ?>
                <?php foreach ($karyawan1 as $data): ?>
                    <form method="post" action="<?php echo base_url('page/aksi_edit') ?>" enctype="multipart/form-data"
                        class="card shadow border-0 w-100 py-3">
                        <input type="hidden" name="id" value="<?php echo $data->id ?>">
                        <div class="card-body">
                            <div>
                                <h5>Menu kegiatan</h5>
                                <!-- <hr> -->
                                <textarea cols="40" rows="10" name="kegiatan"
                                    class="form-control mt-5"><?php echo $data->kegiatan; ?></textarea>
                            </div>
                        </div>
                        <div class="flex px-3">
                            <button type="button" class="btn btn-sm btn-danger text-danger-hover-none"><a
                                    class="text-light text-decoration-none" href="/absensi/page/absensi_karyawan">
                                    Cancel</a>
                            </button>
                            <?php if ($data->keterangan_izin != "-"): ?>
                                <button type="button" onclick="tampilSweetAlertKeterangan()"
                                    class="btn btn-sm btn-success text-danger-hover-none">
                                    Izin
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-sm btn-success text-danger-hover-none" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Izin
                                </button>
                            <?php endif; ?>
                            <?php if ($data->jam_masuk == "00:00:00"):?>
                                <button type="submit" name="submit" class="btn btn-sm btn-primary text-danger-hover-none" disabled>
                                Submit
                            </button>
                            <?php else:?>
                            <button type="submit" name="submit" class="btn btn-sm btn-primary text-danger-hover-none">
                                Submit
                            </button>
                            <?php endif;?>
                        </div>
                    </form>
                <?php endforeach ?>
            </div>
        </div>
        <!-- Modalizin -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="post" action="<?php echo base_url('page/aksi_keterangan_izin') ?>"
                    enctype="multipart/form-data">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal Izin</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <?php foreach ($karyawan1 as $keterangan): ?>
                        <input type="hidden" name="id" value="<?php echo $keterangan->id ?>">
                        <div class="modal-body">
                            <label for="" class="form-label">Keterangan izin</label>
                            <textarea cols="40" rows="10" name="keterangan_izin"
                                placeholder="Izin hanya dilakukan sekali sehari"
                                class="form-control"><?php echo $keterangan->keterangan_izin ?></textarea>
                        </div>
                    <?php endforeach; ?>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                        <!-- <button type="submit" name="submit" class="btn btn-primary">Save changes</button> -->
                    </div>
                </form>
            </div>
        </div>
        <!-- end modal izin -->
    <?php endif; ?>

    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/js/script.js" rel="stylesheet">
    <!-- scrip sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- sweet alert jika ggal izin -->
    <?php if ($this->session->flashdata('gagal_izin')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Melakukan Izin',
                text: '<?= $this->session->flashdata('gagal_izin') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika berhasil melakukan izin -->
    <?php if ($this->session->flashdata('berhasil_izin')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '<?= $this->session->flashdata('berhasil_izin') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika ingin izin tetapi belum pulang -->
    <script>
        function tampilSweetAlert() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal melakukan izin',
                text: 'Tidak bisa izin karena anda belum pulang',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        }
    </script>

    <!-- sweet alert jika sudah melakukan izin hari ini -->
    <script>
        function tampilSweetAlertKeterangan() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal melakukan izin',
                text: 'Anda sudah izin hari ini',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        }
    </script>
    <!-- script bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>