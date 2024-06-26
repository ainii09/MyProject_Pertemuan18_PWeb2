
<?php
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Sistem Penunjang Keputusan</title>

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
        <a class="nav-link" href="user_dashboard.php">
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
            <a class="collapse-item" href="#">ranking</a>
          </div>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" href="logout.php">
          <i class="fas fa-fw"></i>
          <span>Log Out</span></a>
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
        
        <?php
        $digit = 4 ;
        /*fetch kriteria*/
        $query = $pdo ->prepare('SELECT id_kriteria, nama, sifat, bobot FROM kriteria ORDER BY id_kriteria ASC');
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        $kriterias = $query->fetchAll();
        
        /*fetch Supplier*/
        $query2 = $pdo->prepare('SELECT id_supplier,supplier FROM supplier');
        $query2->execute();
        $query2->setFetchMode(PDO::FETCH_ASSOC);
        $supplier = $query2->fetchAll();

        /*matriks keputusan*/
        $matriks_x = array();
            $list_kriteria = array();
            foreach($kriterias as $kriteria):
                $list_kriteria[$kriteria['id_kriteria']] = $kriteria;
                foreach($supplier as $supp):
                    
                    $id_supplier = $supp['id_supplier'];
                    $id_kriteria = $kriteria['id_kriteria'];
                    
                    // Fetch nilai dari db
                    $query3 = $pdo->prepare('SELECT nilai FROM nilai_supplier
                        WHERE id_supplier = :id_supplier AND id_kriteria = :id_kriteria');
                    $query3->execute(array(
                        'id_supplier' => $id_supplier,
                        'id_kriteria' => $id_kriteria,
                    ));			
                    $query3->setFetchMode(PDO::FETCH_ASSOC);
                    if($nilai_supplier = $query3->fetch()) {
                        // Jika ada nilai kriterianya
                        $matriks_x[$id_kriteria][$id_supplier] = $nilai_supplier['nilai'];
                    } else {			
                        $matriks_x[$id_kriteria][$id_supplier] = 0;
                    }

                endforeach;
            endforeach;

        /*matriks ternormalisasi*/
        $matriks_r = array();
        foreach($matriks_x as $id_kriteria => $nilai_suppliers):
            
            $tipe = $list_kriteria[$id_kriteria]['sifat'];
            foreach($nilai_suppliers as $id_alternatif => $nilai) {
                if($tipe == 'Benefit') {
                    $nilai_normal = $nilai / max($nilai_suppliers);
                } elseif($tipe == 'Cost') {
                    $nilai_normal = min($nilai_suppliers) / $nilai;
                }
                
                $matriks_r[$id_kriteria][$id_alternatif] = $nilai_normal;
            }
            
        endforeach;

        /*perangkingan*/
        $ranks = array();
        foreach($supplier as $supp):

            $total_nilai = 0;
            foreach($list_kriteria as $kriteria) {
            
                $bobot = $kriteria['bobot'];
                $id_supplier = $supp['id_supplier'];
                $id_kriteria = $kriteria['id_kriteria'];
                
                $nilai_r = $matriks_r[$id_kriteria][$id_supplier];
                $total_nilai = $total_nilai + ($bobot * $nilai_r);

            }
            
            $ranks[$supp['id_supplier']]['id_supplier'] = $supp['id_supplier'];
            $ranks[$supp['id_supplier']]['supplier'] = $supp['supplier'];
            $ranks[$supp['id_supplier']]['nilai'] = $total_nilai;
            
        endforeach;
 
        ?>
        
        <!-- Begin Page Content -->
        <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">Detail Supplier</h1>
          <?php
            $query = $pdo->prepare('SELECT * FROM supplier');			
            $query->execute();
            // menampilkan berupa nama field
            $query->setFetchMode(PDO::FETCH_ASSOC);
            
            if($query->rowCount() > 0):
            ?>
            
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th>Supplier</th>
                  <th>Kecepatan Pengiriman</th>
                  <th>Diskon</th>
                  <th>Pelayanan</th>
                  <th>Garansi</th>
                  <th>Keaslian</th>
                  <th>Tanggal Pembayaran</th>						
                </tr>
              </thead>
              <tbody>
                <?php while($hasil = $query->fetch()): ?>
                  <tr>
                    <td><?php echo $hasil['supplier']; ?></td>							
                    <td><?php echo $hasil['kecepatan_pengiriman']; ?></td>	
                    <td><?php echo $hasil['diskon']; ?></td>							
                    <td><?php echo $hasil['pelayanan']; ?></td>	
                    <td><?php echo $hasil['garansi']; ?></td>							
                    <td><?php echo $hasil['keaslian']; ?></td>							
                    <td><?php echo $hasil['tempo_pembayaran']; ?></td>                                        
                  </tr>
                <?php endwhile; ?>
              </tbody>                    
            </table>
            
            <?php else: ?>
              <p>Maaf, belum ada data untuk Supplier</p>
            <?php endif; ?>


          <!-- Page Heading -->
          <!--<marquee behavior="alternate"><h1 class="h3 mb-4 text-gray-800">Selamat Datang di Halaman Dashboard!</h1></marquee><br>-->
          <h1 class="h3 mb-4 text-gray-800">Matriks Keputusan (X)</h1>
            
            <table class="table">
              <thead class="thead-dark">
              <tr class="super-top">
					<th rowspan="2" class="super-top-left">Nama</th>
					<th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
				</tr>
				<tr>
					<?php foreach($kriterias as $kriteria ): ?>
						<th><?php echo $kriteria['nama']; ?></th>
					<?php endforeach; ?>
				</tr>
              </thead>
              <tbody>
				<?php foreach($supplier as $supp): ?>
					<tr>
						<td><?php echo $supp['supplier']; ?></td>
						<?php						
						foreach($kriterias as $kriteria):
							$id_supplier = $supp['id_supplier'];
							$id_kriteria = $kriteria['id_kriteria'];
							echo '<td>';
							echo $matriks_x[$id_kriteria][$id_supplier];
							echo '</td>';
						endforeach;
						?>
					</tr>
				<?php endforeach; ?>
			</tbody>
            </table>

            <!-- STEP 2. Bobot Preferensi (W) ==================== -->
            <h3>Bobot Preferensi (W)</h3>			
            <table class="table">
                <thead class="thead-dark">
                    <tr class="super-top">
                        <th>Nama Kriteria</th>
                        <th>Sifat</th>
                        <th>Bobot (W)</th>						
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($kriterias as $hasil): ?>
                        <tr>
                            <td><?php echo $hasil['nama']; ?></td>
                            <td>
                            <?php
                            if($hasil['sifat'] == 'Benefit') {
                                echo 'Benefit';
                            } elseif($hasil['sifat'] == 'Cost') {
                                echo 'Cost';
                            }							
                            ?>
                            </td>
                            <td><?php echo $hasil['bobot']; ?></td>							
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3>Matriks Ternormalisasi (R)</h3>			
            <table class="table">
              <thead class="thead-dark">
                <tr>
                  <th rowspan="2">Nama</th>
                  <th colspan="<?php echo count($kriterias); ?>">Kriteria</th>
                </tr>
                <tr>
                  <?php foreach($kriterias as $kriteria ): ?>
                    <th><?php echo $kriteria['nama']; ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach($supplier as $supp): ?>
                  <tr>
                    <td><?php echo $supp['supplier']; ?></td>
                    <?php						
                    foreach($kriterias as $kriteria):
                      $id_supplier = $supp['id_supplier'];
                      $id_kriteria = $kriteria['id_kriteria'];
                      echo '<td>';
                      echo round($matriks_r[$id_kriteria][$id_supplier], $digit);
                      echo '</td>';
                    endforeach;
                    ?>
                  </tr>
                <?php endforeach; ?>				
              </tbody>
            </table>	
            
            <!-- Step 4: Perangkingan ==================== -->
              <?php		
              $sorted_ranks = $ranks;		
              // Sorting
              if(function_exists('array_multisort')):
                $supplier = array();
                $nilai = array();
                foreach ($sorted_ranks as $key => $row) {
                  $supplier[$key]  = $row['supplier'];
                  $nilai[$key] = $row['nilai'];
                }
                array_multisort($nilai, SORT_DESC, $supplier, SORT_ASC, $sorted_ranks);
              endif;
              ?>		
              <h3>Perangkingan (V)</h3>			
              <table class="table">
                <thead class="thead-dark">					
                  <tr>
                    <th>Nama</th>
                    <th>Ranking</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($sorted_ranks as $supp ): ?>
                    <tr>
                      <td><?php echo $supp['supplier']; ?></td>
                      <td><?php echo round($supp['nilai'], $digit); ?></td>											
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>			

              </div>
              <!-- /.container-fluid -->

            </div>
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
