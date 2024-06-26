
<?php include "koneksi.php";?>

<!DOCTYPE html>
<html lang="en">

<?php
$errors = array();
$sukses = false;

$ada_error = false;
$result = '';

    $supplier = (isset($_POST['supplier'])) ? trim($_POST['supplier']) : '';
	$pengiriman = (isset($_POST['kecepatan_pengiriman'])) ? trim($_POST['kecepatan_pengiriman']) : '';
	$diskon = (isset($_POST['diskon'])) ? trim($_POST['diskon']) : '';
    $pelayanan = (isset($_POST['pelayanan'])) ? trim($_POST['pelayanan']) :'';
    $garansi = (isset($_POST['garansi'])) ? trim($_POST['garansi']) : '';
	$keaslian = (isset($_POST['keaslian'])) ? trim($_POST['keaslian']) : '';
    $pembayaran = (isset($_POST['tempo_pembayaran'])) ? trim($_POST['tempo_pembayaran']) :'';
	$kriteria = (isset($_POST['kriteria'])) ? $_POST['kriteria'] : array();


if(isset($_POST['submit'])):	
	
	// Validasi
	if(!$supplier) {
		$errors[] = 'Nama tidak boleh kosong';
	}	
	
	
	// Jika lolos validasi lakukan hal di bawah ini
	if(empty($errors)):
		
		$handle = $pdo->prepare('INSERT INTO supplier (supplier, kecepatan_pengiriman, diskon, pelayanan, garansi, keaslian, tempo_pembayaran) 
        VALUES (:supplier, :kecepatan_pengiriman, :diskon, :pelayanan, :garansi, :keaslian, :tempo_pembayaran)');
		$handle->execute( array(
			'supplier' => $supplier,
			'kecepatan_pengiriman' => $pengiriman,
            'diskon' => $diskon,
            'pelayanan' => $pelayanan,
			'garansi' => $garansi,
            'keaslian' => $keaslian,
            'tempo_pembayaran' => $pembayaran
		) );
		$sukses = "Supplier <strong>{$supplier}</strong> berhasil dimasukkan.";
		$id_supplier = $pdo->lastInsertId();
		
		// Jika ada kriteria yang diinputkan:
		if(!empty($kriteria)):
			foreach($kriteria as $id_kriteria => $nilai):
				$handle = $pdo->prepare('INSERT INTO nilai_supplier (id_supplier, id_kriteria, nilai) VALUES (:id_supplier, :id_kriteria, :nilai)');
				$handle->execute( array(
					'id_supplier' => $id_supplier,
					'id_kriteria' => $id_kriteria,
					'nilai' =>$nilai
				) );
			endforeach;
		endif;	
		
	endif;

endif;
?>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Tambah Supplier</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-danger sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-book"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Menu</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Interface
      </div>

      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-book"></i>
          <span>Data Supplier</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Data :</h6>
            <a class="collapse-item" href="supplier.php">Supplier</a>
            <a class="collapse-item" href="ranking.php">ranking</a>
          </div>
        </div>
      </li>

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0 bg-dark" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <div class="sidebar-brand-text mx-2"><h5>Sistem Penunjang Keputusan</h5></div>
          
          <!-- Search -->

          <!-- End Search -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->

        <?php if(!empty($errors)): ?>
			
			<div class="msg-box warning-box">
			  <p><strong>Error:</strong></p>
			  <ul>
				<?php foreach($errors as $error): ?>
				  <li><?php echo $error; ?></li>
				<?php endforeach; ?>
			  </ul>
			</div>
			
		  <?php endif; ?>
		  
		  <?php if($sukses): ?>
		  
        <div class="card mb-10">
            <div class="card-header bg-success text-white">
                Data berhasil Ditambahkan
            </div>
            <div class="card-body">
                Klik Tombol "Tinjau" untuk kembali ke halaman utama
            </div>
						<div class="card-body">
              <a href="supplier.php"><button class="btn btn-primary">Tinjau</button></a> 
            </div>
        </div>
			
		  <?php elseif($ada_error): ?>
			
			<p><?php echo $ada_error; ?></p>
		  
		  <?php else: ?>

        <form action="tambah-supplier.php" method="post">
            <table class="table">
                  <thead class="thead-dark">
                    <tr>				
                      <th colspan="3">Tambah Supplier</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr>
                        <td>Supplier</td>
                        <td><input type="text" name="supplier" value="<?php echo $supplier; ?>"></td>
                      </tr>
                      <tr>
                        <td>Kecepatan Pengiriman</td>
                        <td><input type="text" name="kecepatan_pengiriman" value="<?php echo $pengiriman; ?>"></td>
                      </tr>
                      <tr>
                        <td>Diskon</td>
                        <td><input type="text" name="diskon" value="<?php echo $diskon; ?>"></td>
                      </tr>							
                      <tr>
                        <td>Pelayanan</td>
                        <td><input type="text" name="pelayanan" value="<?php echo $pelayanan; ?>"></td>
                      </tr>
                      <tr>
                        <td>Garansi</td>
                        <td><input type="text" name="garansi" value="<?php echo $garansi; ?>"></td>
                      </tr>
                      <tr>
                        <td>Keaslian</td>
                        <td><input type="text" name="keaslian" value="<?php echo $keaslian; ?>"></td>
                      </tr>
                      <tr>
                        <td>Tempo Pembayaran</td>
                        <td><input type="text" name="tempo_pembayaran" value="<?php echo $pembayaran; ?>"></td>
                      </tr>
                  </tbody>                        
            </table>
			<table class="table">
                  <thead class="thead-dark">
                    <tr>				
                      <th colspan="3">EDIT NILAI KRITERIA</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
					$query = $pdo->prepare('SELECT id_kriteria, nama, ada_pilihan FROM kriteria ORDER BY urutan_order ASC');			
					$query->execute();
					// menampilkan berupa nama field
					$query->setFetchMode(PDO::FETCH_ASSOC);
					
					if($query->rowCount() > 0):
					
						while($kriteria = $query->fetch()):							
						?>
                      <tr>
                        <td><?php echo $kriteria['nama']; ?></td>
                        <?php if(!$kriteria['ada_pilihan']): ?>
                        <td>=></td>
                        <td><input type="number" step="0.001" name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]"></td>
                      </tr>
                      <?php else: ?>
                          <select name="kriteria[<?php echo $kriteria['id_kriteria']; ?>]">
                          <option value="0">-- Pilih Variabel --</option>
                          <?php
                          $query3 = $pdo->prepare('SELECT * FROM pilihan_kriteria WHERE id_kriteria = :id_kriteria ORDER BY urutan_order ASC');			
                          $query3->execute(array(
                            'id_kriteria' => $kriteria['id_kriteria']
                          ));
                          // menampilkan berupa nama field
                          $query3->setFetchMode(PDO::FETCH_ASSOC);
                          if($query3->rowCount() > 0): while($hasl = $query3->fetch()):
                          ?>
                            <option value="<?php echo $hasl['nilai']; ?>" <?php selected($kriteria['nilai'], $hasl['nilai']); ?>><?php echo $hasl['nama']; ?></option>
                          <?php
                          endwhile; endif;
                          ?>
                        </select>
                      <?php endif; ?>
                    </div>		
                  <?php
                  endwhile;
                  
                else:					
                  echo '<p>Kriteria masih kosong.</p>';						
                endif;
                ?>
                  </tbody> 
                  <td>
                      <td colspan="1">
                          <td><a href="tambah-supplier.php"><button type="submit" name="submit" value="submit" class="btn btn-success">Tambah Supplier</button></td>
                      </td>
                  </td>                       
            </table>
		</form>
		
		<?php endif; ?>

              <!-- /.container-fluid -->

            <!-- End of Main Content -->


      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; Khurotul Nuraini</span><br><br>
            <span><?php echo date("l, d F Y")."<br/>"; ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin ingin pergi?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Silahakan Klik tombol LogOut jika suda yakin ingin pergi</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
