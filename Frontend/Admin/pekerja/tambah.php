<h2 style="color:#0072CF; margin-bottom:20px;">Tambah Pekerja</h2>

<style>
    .form-table {
        width: 80%;
        background: #ffffff;
        border-collapse: collapse;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .form-table th {
        width: 25%;
        background: #0072CF;
        color: white;
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .form-table td {
        padding: 12px;
        border: 1px solid #ddd;
        background: #f4f9ff;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .btn-cleanify {
        background: #0072CF;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    .btn-cleanify:hover {
        background: #005fa8;
    }

    .btn-secondary {
        padding: 8px 16px;
        border-radius: 6px;
        background: #aaa;
        color: white;
        text-decoration: none;
        margin-left: 8px;
    }
</style>

<form method="POST" enctype="multipart/form-data">

<table class="form-table">

    <tr>
        <th>Nama</th>
        <td><input type="text" name="name" class="form-control" required></td>
    </tr>

    <tr>
        <th>Posisi</th>
        <td>
            <select name="position" class="form-control" required>
                <option>Regular Cleaner</option>
                <option>Deep Clean Specialist</option>
                <option>Premium Senior Staff</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Status</th>
        <td>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="on_leave">On Leave</option>
                <option value="inactive">Inactive</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Rating</th>
        <td><input type="number" step="0.01" name="rating" class="form-control" required></td>
    </tr>

    <tr>
        <th>No Telepon</th>
        <td><input type="text" name="phone" class="form-control" required></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><input type="email" name="email" class="form-control"></td>
    </tr>

    <tr>
        <th>Password</th>
        <td><input type="text" name="password" class="form-control"></td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td><textarea name="address" rows="2" class="form-control"></textarea></td>
    </tr>

    <tr>
        <th>Kontak Darurat (Nama)</th>
        <td><input type="text" name="emergency_contact_name" class="form-control"></td>
    </tr>

    <tr>
        <th>Kontak Darurat (Telepon)</th>
        <td><input type="text" name="emergency_contact_phone" class="form-control"></td>
    </tr>

    <tr>
        <th>Skill</th>
        <td><textarea name="skills" rows="2" class="form-control"></textarea></td>
    </tr>

    <tr>
        <th>Foto</th>
        <td><input type="file" name="photo" class="form-control"></td>
    </tr>

</table>

<button type="submit" name="submit" class="btn-cleanify">Simpan</button>
<a href="pekerja.php" class="btn-secondary">Kembali</a>

</form>
