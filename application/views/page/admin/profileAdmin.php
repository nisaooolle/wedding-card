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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
    <title>Profile Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;1,500&display=swap"
        rel="stylesheet">
</head>

<body class="text-capitalize" style="font-family: 'Poppins', sans-serif;">
    <?php if ($this->session->userdata('role') === "admin"): ?>
        <div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
            <!-- kondisi jika tidak berrole admin maka tidak bisa melihat page ini -->
            <?php $this->load->view('component/sidebar'); ?>
            <div class="h-screen flex-grow-1 overflow-y-lg-auto">
                <!-- header/navbar -->
                <?php $this->load->view('component/header'); ?>
                <div class="flex-lg-1 h-screen">
                    <!-- header profile -->
                    <header class="bg-surface-secondary">
                        <div class="bg-cover"
                            style="height:300px;background-image:url(https://img.freepik.com/free-photo/abstract-refreshing-blue-tropical-watercolor-background-illustration-high-resolution-free-image_1340-20381.jpg?size=626&ext=jpg&ga=GA1.1.1464020286.1696819460&semt=ais);background-position:center center">
                        </div>
                        <div class="container-fluid max-w-screen-xl">
                            <div class="row g-0">
                                <div class="col-auto">
                                    <a class="avatar w-40 h-40 border border-body border-4 rounded-circle shadow mt-n16">
                                        <?php if (!empty($this->fungsi->user_login()->image)): ?>
                                            <img alt="..."
                                                src="<?php echo base_url('images/user/' . $this->fungsi->user_login()->image) ?>"
                                                class="rounded-circle">
                                        <?php else: ?>
                                            <img alt="..." src="<?php echo base_url('/asset/FlexStart/') ?>assets/img/user.avif"
                                                class="rounded-circle">
                                        <?php endif; ?>
                                    </a>
                                </div>
                                <div class="col ps-4 pt-4">
                                    <h6 class="text-xs text-uppercase text-muted mb-1">
                                        <?php echo $this->session->userData('role') ?>
                                    </h6>
                                    <h1 class="h2">
                                        <?php echo $this->session->userData('username') ?>
                                    </h1>
                                </div>
                            </div>
                            <ul class="nav nav-tabs overflow-x ms-1 mt-4">
                                <li class="nav-item">
                                    <a href="#!" class="nav-link active font-bold" id="myProfile">My profile</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#!" class="nav-link" id="editProfile">Edit Profile</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#!" class="nav-link" id="editPassword">Edit password</a>
                                </li>
                            </ul>
                        </div>
                    </header>
                    <!-- end header profile -->
                    <main class="py-6 bg-surface-secondary">
                        <!-- menampilkan data profile -->
                        <div class="container-fluid max-w-screen-xl" id="myProfileContainer">
                            <div class="vstack gap-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Profile</h5>
                                        <br>
                                        <div class="row">
                                            <p class="text-sm lh-relaxed mb-4 col-6">Username</p>
                                            <p class="text-sm lh-relaxed mb-4 col-6">
                                                <?php echo $this->session->userData('username') ?>
                                            </p>
                                            <hr>
                                            <p class="text-sm lh-relaxed mb-4 col-6">Email</p>
                                            <p class="text-sm lh-relaxed mb-4 col-6">
                                                <?php echo $this->session->userdata('email') ?>
                                            </p>
                                            <hr>
                                            <p class="text-sm lh-relaxed mb-4 col-6">Nama lengkap</p>
                                            <p class="text-sm lh-relaxed mb-4 col-6">
                                                <?= $this->fungsi->user_login()->nama_depan . " " . $this->fungsi->user_login()->nama_belakang ?>
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end menampilkan data profile -->
                        <!-- start edit password -->
                        <div class="container-fluid max-w-screen-xl" id="editPasswordContainer" style="display: none;">
                            <div class="vstack gap-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Edit Password</h5>
                                        <hr>
                                        <form method="post" action="<?php echo base_url('page/aksi_edit_password') ?>"
                                            enctype="multipart/form-data" class="row">
                                            <div class="mb-3 col-6">
                                                <label for="password_lama" class="form-label">Password lama</label>
                                                <div class="input-group">
                                                    <input type="password" name="password_lama" class="form-control"
                                                        id="password_lama" required>
                                                    <span class="input-group-text"><i id="showPasswordLama"
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="password_baru" class="form-label">Password baru</label>
                                                <div class="input-group">
                                                    <input type="password" name="password_baru" class="form-control"
                                                        id="password_baru" required>
                                                    <span class="input-group-text"><i id="showPasswordBaru"
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label for="konfirmasi_password" class="form-label">Konfirmasi
                                                    password</label>
                                                <div class="input-group">
                                                    <input type="password" name="konfirmasi_password" class="form-control"
                                                        id="konfirmasi_password" required>
                                                    <span class="input-group-text"><i id="showKonfirmasiPassword"
                                                            class="fas fa-eye"></i></span>
                                                </div>
                                            </div>
                                            <br>
                                            <button type="submit" name="submit"
                                                class="btn btn-sm btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end edit passwrod -->
                        <!-- start edit profile -->
                        <div class="container-fluid max-w-screen-xl" id="editProfileContainer" style="display: none;">
                            <div class=" vstack gap-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mb-3">Edit Profile</h5>
                                        <hr>
                                        <?php foreach ($profile as $value): ?>
                                            <form method="post" action="<?php echo base_url('page/aksi_ubah_profile') ?>"
                                                enctype="multipart/form-data" class="row">
                                                <div class="mb-3 col-6">
                                                    <label for="username" class="form-label">Username</label>
                                                    <!-- Input field untuk jurusam_kelas -->
                                                    <input type="text" name="username" class="form-control" id="jurusan_kelas"
                                                        placeholder="username" required value="<?php echo $value->username ?>">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <!-- Input field untuk jurusam_kelas -->
                                                    <input type="text" name="email" class="form-control" id="jurusan_kelas"
                                                        placeholder="email" required value="<?php echo $value->email ?>">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="nama_depan" class="form-label">Nama depan</label>
                                                    <!-- Input field untuk jurusam_kelas -->
                                                    <input type="text" name="nama_depan" class="form-control" id="jurusan_kelas"
                                                        placeholder="nama_depan" required
                                                        value="<?php echo $value->nama_depan ?>">
                                                </div>
                                                <div class="mb-3 col-6">
                                                    <label for="nama_belakang" class="form-label">Nama belakang</label>
                                                    <!-- Input field untuk jurusam_kelas -->
                                                    <input type="text" name="nama_belakang" class="form-control"
                                                        id="jurusan_kelas" placeholder="nama_belakang" required
                                                        value="<?php echo $value->nama_belakang ?>">
                                                </div>
                                                <br>
                                                <button class="btn btn-sm btn-primary" style="width:fit-content" type="submit"
                                                    name="submit">Submit</button>
                                            </form>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="card">
                                    <!-- start edit foto -->
                                    <div class="card-body">
                                        <h5 class="mb-3">Edit Foto</h5>
                                        <hr>
                                        <form method="post" action="<?php echo base_url('page/aksi_ubah_gambar') ?>"
                                            enctype="multipart/form-data" class="row">
                                            <div class="mb-3 col-6">
                                                <label for="foto" class="form-label">Foto</label>
                                                <div class="input-group">
                                                    <input type="file" name="foto" class="form-control" id="foto"
                                                        placeholder="foto" required>
                                                </div>
                                                <br>
                                                <button type="submit" name="submit"
                                                    class="btn btn-sm btn-primary">Submit</button>
                                        </form>
                                    </div>
                                    <!-- end edit foto -->
                                </div>
                                <!-- end edit profile -->
                    </main>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- script untuk sho dan hide dedatil profile, edit profile, dan edit password -->
    <script>
        document.getElementById('myProfile').addEventListener('click', function () {
            document.getElementById('myProfileContainer').style.display = 'block';
            document.getElementById('editProfileContainer').style.display = 'none';
            document.getElementById('editProfileContainer').style.display = 'none';
            document.getElementById('editPasswordContainer').style.display = 'none';
        });

        document.getElementById('editProfile').addEventListener('click', function () {
            document.getElementById('myProfileContainer').style.display = 'none';
            document.getElementById('editPasswordContainer').style.display = 'none';
            document.getElementById('editProfileContainer').style.display = 'block';
            document.getElementById('editProfileContainer').style.display = 'block';
        });

        document.getElementById('editPassword').addEventListener('click', function () {
            document.getElementById('myProfileContainer').style.display = 'none';
            document.getElementById('editPasswordContainer').style.display = 'block';
            document.getElementById('editPasswordContainer').style.display = 'block';
            document.getElementById('editProfileContainer').style.display = 'none';
        });

    </script>

    <!-- script untuk show dan hide password -->
    <script>
        function togglePassword(inputId, iconId) {
            var passwordInput = document.getElementById(inputId);
            var passwordIcon = document.getElementById(iconId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordIcon.classList.remove("fa-eye");
                passwordIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                passwordIcon.classList.remove("fa-eye-slash");
                passwordIcon.classList.add("fa-eye");
            }
        }

        // Menambahkan event listener untuk ikon mata pada input password lama
        document.getElementById('showPasswordLama').addEventListener('click', function () {
            togglePassword('password_lama', 'showPasswordLama');
        });

        // Menambahkan event listener untuk ikon mata pada input password baru
        document.getElementById('showPasswordBaru').addEventListener('click', function () {
            togglePassword('password_baru', 'showPasswordBaru');
        });

        // Menambahkan event listener untuk ikon mata pada input konfirmasi password
        document.getElementById('showKonfirmasiPassword').addEventListener('click', function () {
            togglePassword('konfirmasi_password', 'showKonfirmasiPassword');
        });
    </script>

    <!-- sweet alert jika edit profile berhasil -->
    <?php if ($this->session->flashdata('berhasil_edit_profile')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Edit Profile',
                text: '<?= $this->session->flashdata('berhasil_edit_profile') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika gagal edit profile -->
    <?php if ($this->session->flashdata('gagal_edit_profile')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Edit Profile',
                text: '<?= $this->session->flashdata('gagal_edit_profile') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika konfirmasi password tidak sama dengan password baru -->
    <?php if ($this->session->flashdata('konfirmasi_pass')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Ganti Password',
                text: '<?= $this->session->flashdata('konfirmasi_pass') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika berhasil mengganti password -->
    <?php if ($this->session->flashdata('berhasil_ganti_password')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Ganti Password',
                text: '<?= $this->session->flashdata('berhasil_ganti_password') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika menginputkan pass lama tidak sama -->
    <?php if ($this->session->flashdata('pass_lama')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Ganti Password',
                text: '<?= $this->session->flashdata('pass_lama') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika berhasil mengganti foto profile -->
    <?php if ($this->session->flashdata('berhasil_ganti_foto')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Ganti Foto',
                text: '<?= $this->session->flashdata('berhasil_ganti_foto') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <!-- sweet alert jika ggal mengganti foto -->
    <?php if ($this->session->flashdata('gagal_ganti_foto')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal Ganti Foto',
                text: '<?= $this->session->flashdata('gagal_ganti_foto') ?>',
                background: '#fff',
                customClass: {
                    title: 'text-dark',
                    content: 'text-dark'
                }
            });
        </script>
    <?php endif; ?>

    <link href="<?php echo base_url('/asset/FlexStart/') ?>assets/js/script.js" rel="stylesheet">
</body>

</html>