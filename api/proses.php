<?php
// api/proses.php
/**
 * Warrior Computer - API Central Processing Engine
 * File: api/proses.php
 * Deskripsi: Menangani seluruh request asynchronous (AJAX/Fetch) baik GET maupun POST,
 * sinkronisasi mutasi data, autentikasi user, dan manajemen database servis.
 */

// 1. PREVENT WHITE-SPACE LEAKAGE: Bersihkan buffer output agar tidak ada spasi liar yang bocor ke JSON
ob_start();

require_once '../config/db.php';

// 2. SET JSON HEADER RESPONSIVE
header('Content-Type: application/json; charset=utf-8');

// --- METHOD GET ---
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['action'])) {
        
        // 1. Ambil Semua Data Servis untuk Frontend
        if ($_GET['action'] == 'get_all') {
            $sql = "SELECT * FROM servis ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    // Konversi format tanggal SQL (YYYY-MM-DD) ke format tampilan nota (DD/MM/YYYY)
                    if (!empty($row['tgl'])) {
                        $row['tgl'] = date('d/m/Y', strtotime($row['tgl']));
                    }
                    if (!empty($row['tgl_selesai'])) {
                        $row['tgl_selesai'] = date('d/m/Y', strtotime($row['tgl_selesai']));
                    }
                    
                    $row['biaya'] = (int)$row['biaya'];
                    $row['modal'] = (int)$row['modal'];
                    $data[] = $row;
                }
            }
            ob_clean(); // Pastikan buffer bersih total sebelum cetak JSON
            echo json_encode($data);
            exit;
        }

        // 2. Ambil Semua Data Pengguna (Manajemen Pengguna oleh Admin)
        if ($_GET['action'] == 'get_users') {
            $sql = "SELECT id, username, nama, role, password, created_at FROM pengguna ORDER BY id DESC";
            $result = $conn->query($sql);
            
            $data = [];
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            ob_clean();
            echo json_encode($data);
            exit;
        }
    }
}

// --- METHOD POST ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {

        // ==========================================
        // FITUR UTAMA: AUTENTIKASI LOGIN
        // ==========================================
        if ($_POST['action'] === 'login_auth') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $user_input = isset($_POST['username']) ? trim($_POST['username']) : '';
            $pass_input = isset($_POST['password']) ? $_POST['password'] : '';

            if (empty($user_input) || empty($pass_input)) {
                ob_clean();
                echo json_encode(['success' => false, 'error' => 'Username dan password wajib diisi!']);
                exit;
            }

            $stmt = $conn->prepare("SELECT * FROM pengguna WHERE username = ? LIMIT 1");
            $stmt->bind_param("s", $user_input);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if ($pass_input === $user['password']) {
                    $_SESSION['is_logged_in'] = true;
                    $_SESSION['user_id']      = $user['id'];
                    $_SESSION['username']     = $user['username'];
                    $_SESSION['nama_user']    = $user['nama'];
                    $_SESSION['role']         = $user['role'];

                    ob_clean();
                    echo json_encode([
                        'success'  => true,
                        'username' => $user['username'],
                        'role'     => $user['role'],
                        'nama'     => $user['nama']
                    ]);
                    exit;
                }
            }
            ob_clean();
            echo json_encode(['success' => false, 'error' => 'Username atau Password yang Anda masukkan salah.']);
            exit;
        }

        // ==========================================
        // SUB-SISTEM: MANAJEMEN SERVIS COMPUTER
        // ==========================================
        
        // 1. Tambah Data Servis Baru (Menerima parameter modal terakumulasi otomatis)
        if ($_POST['action'] == 'add') {
            $id = 'W-' . substr(time(), -6); 
            $tgl = $_POST['tgl']; 
            $nama = $_POST['nama'];
            $wa = $_POST['wa'];
            $merk = $_POST['merk'];
            $kelengkapan = $_POST['kelengkapan'];
            $kerusakan = $_POST['kerusakan'];
            $penanganan = $_POST['penanganan'];
            $garansi = $_POST['garansi'];
            $biaya = (int)$_POST['biaya'];
            $modal = (int)($_POST['modal'] ?? 0); // Menangkap input data modal otomatis dari frontend

            $stmt = $conn->prepare("INSERT INTO servis (id, tgl, nama, wa, merk, kelengkapan, kerusakan, penanganan, garansi, biaya, modal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssii", $id, $tgl, $nama, $wa, $merk, $kelengkapan, $kerusakan, $penanganan, $garansi, $biaya, $modal);
            
            ob_clean();
            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'id' => $id]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // 2. Perbarui Data Servis (Status, Teknisi, Modal, atau Biaya Akhir)
        if ($_POST['action'] == 'update') {
            $id = $_POST['id'];
            $key = $_POST['key'];
            $val = trim($_POST['val']); 

            // Pembatasan kolom yang diizinkan untuk keamanan query dinamis (White-listing)
            $allowed_keys = ['status', 'teknisi', 'meja_teknisi', 'modal', 'biaya'];
            if (!in_array($key, $allowed_keys)) {
                ob_clean();
                echo json_encode(['success' => false, 'error' => 'Akses mutasi kolom ditolak!']);
                exit;
            }

            // Mendorong update tgl_selesai jika status bernilai 'Selesai' ATAU 'Lunas'
            if ($key === 'status' && ($val === 'Selesai' || $val === 'Lunas')) {
                $today = date('Y-m-d');
                $stmt = $conn->prepare("UPDATE servis SET status = ?, tgl_selesai = ? WHERE id = ?");
                $stmt->bind_param("sss", $val, $today, $id);
            } 
            // Validasi khusus tipe data angka/integer
            else if ($key === 'modal' || $key === 'biaya') {
                $val = (int)$val;
                $stmt = $conn->prepare("UPDATE servis SET `$key` = ? WHERE id = ?");
                $stmt->bind_param("is", $val, $id);
            } 
            // Validasi string standar (Status selain Selesai/Lunas, Nama Teknisi, Meja)
            else {
                $stmt = $conn->prepare("UPDATE servis SET `$key` = ? WHERE id = ?");
                $stmt->bind_param("ss", $val, $id);
            }

            ob_clean();
            if ($stmt && $stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error ?? 'Gagal menyiapkan statement database.']);
            }
            
            if ($stmt) $stmt->close();
            exit;
        }

        // FIX BARU: Aksi Pembaharuan String Tindakan Gabungan & Mutasi Akumulasi Nilai Modal dari Detail Modal
        if ($_POST['action'] == 'update_penanganan_modal') {
            $id = $_POST['id'];
            $penanganan = $_POST['penanganan'];
            $modal_total = (int)$_POST['modal_total'];

            // Eksekusi mutasi ganda: Mengubah isi teks rincian tindakan sekaligus menimpa total kolom modal suku cadang
            $stmt = $conn->prepare("UPDATE servis SET penanganan = ?, modal = ? WHERE id = ?");
            $stmt->bind_param("sis", $penanganan, $modal_total, $id);
            
            ob_clean();
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // 3. Hapus Data Servis (Khusus Direktur)
        if ($_POST['action'] == 'delete') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM servis WHERE id = ?");
            $stmt->bind_param("s", $id);

            ob_clean();
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // ==========================================
        // SUB-SISTEM: MANAJEMEN PENGGUNA (CRUD)
        // ==========================================
        
        // 1. Tambah Pengguna Baru
        if ($_POST['action'] == 'add_user') {
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $role = trim($_POST['role']);
            $password = $_POST['password'];

            $stmt = $conn->prepare("INSERT INTO pengguna (nama, username, role, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $username, $role, $password);
            
            ob_clean();
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // 2. Perbarui Data Pengguna
        if ($_POST['action'] == 'update_user') {
            $id = (int)$_POST['id'];
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $role = trim($_POST['role']);
            $password = $_POST['password'];

            $stmt = $conn->prepare("UPDATE pengguna SET nama = ?, username = ?, role = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $nama, $username, $role, $password, $id);
            
            ob_clean();
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
            exit;
        }

        // 3. Hapus Pengguna
        if ($_POST['action'] == 'delete_user') {
            $id = (int)$_POST['id'];

            $stmt = $conn->prepare("DELETE FROM pengguna WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            ob_clean();
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => $conn->error]);
            }
            $stmt->close();
            exit;
        }
    }
}

ob_end_flush();
?>