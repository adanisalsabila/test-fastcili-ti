import { test, expect } from '@playwright/test';

// --- CONFIG USER TEKNISI ---
// Menggunakan user Teknisi dari seeder: Rendi Saputra
const TEKNISI = { email: 'rendi.saputra@jti.com', pass: 'password' };

test.describe('Akses Halaman Fasilitas - Role Teknisi (Read Only)', () => {

    // Login sebagai Teknisi sebelum setiap test
    test.beforeEach(async ({ page }) => {
        await page.goto('http://127.0.0.1:8000/login');
        await page.fill('input[name="email"]', TEKNISI.email);
        await page.fill('input[name="password"]', TEKNISI.pass);
        await page.click('button[type="submit"]');

        // Navigasi ke menu Fasilitas
        await page.goto('http://127.0.0.1:8000/fasilitas');
        
        // Validasi berhasil masuk halaman Fasilitas
        await expect(page.getByRole('heading', { name: 'Kelola Data Fasilitas' })).toBeVisible();
    });

    test('1. Teknisi bisa melihat daftar fasilitas (Read)', async ({ page }) => {
        // Pastikan container fasilitas muncul
        await expect(page.locator('#fasilitas-container')).toBeVisible();
        
        // Tunggu sebentar karena data diload via AJAX
        await page.waitForTimeout(1000); 
        const cards = page.locator('.fasilitas-card');
        
        // Cek apakah kartu fasilitas tampil (jika seeder sudah dijalankan)
        if (await cards.count() > 0) {
            await expect(cards.first()).toBeVisible();
            // Pastikan informasi penting tampil di kartu
            await expect(cards.first()).toContainText(/Jumlah:/);
            await expect(cards.first()).toContainText(/Status:/);
            console.log(`✅ Teknisi berhasil melihat ${await cards.count()} kartu fasilitas.`);
        } else {
            console.log('⚠️ List kosong (mungkin database belum di-seed), tapi halaman berhasil diakses.');
        }
    });

    test('2. Teknisi TIDAK BOLEH melihat tombol Aksi Admin (Tambah & Import)', async ({ page }) => {
        // Cek tombol "Tambah Data Fasilitas" (Harus Hilang)
        const btnTambah = page.locator('button:has-text("Tambah Data Fasilitas")');
        await expect(btnTambah).not.toBeVisible();

        // Cek tombol "Impor Data Fasilitas" (Harus Hilang)
        const btnImport = page.locator('button:has-text("Impor Data Fasilitas")');
        await expect(btnImport).not.toBeVisible();
    });

    test('3. Teknisi TIDAK BOLEH melihat tombol Edit dan Hapus di kartu fasilitas', async ({ page }) => {
        await page.waitForTimeout(1000);
        const cards = page.locator('.fasilitas-card');

        if (await cards.count() > 0) {
            const firstCard = cards.first();
            
            // Cek tombol Edit (class .btn-warning)
            await expect(firstCard.locator('.btn-warning')).not.toBeVisible();
            
            // Cek tombol Hapus (class .btn-danger)
            await expect(firstCard.locator('.btn-danger')).not.toBeVisible();
        }
    });

    test('4. Teknisi bisa menggunakan Fitur Pencarian', async ({ page }) => {
        // Cek Input Search
        const searchInput = page.locator('#search');
        await expect(searchInput).toBeVisible();
        
        // Ketik keyword "Proyektor" (dari seeder)
        await searchInput.fill('Proyektor');
        
        // Tunggu AJAX reload (debounce 500ms)
        await page.waitForTimeout(1000);
        
        // Pastikan container masih ada dan hasil filter sesuai (jika data ada)
        await expect(page.locator('#fasilitas-container')).toBeVisible();
        
        // Optional: Jika data seeder ada, cek text-nya
        // const cards = page.locator('.fasilitas-card');
        // if (await cards.count() > 0) {
        //    await expect(cards.first()).toContainText('Proyektor');
        // }
    });

    test('5. Teknisi bisa menggunakan Filter Gedung dan Ruangan', async ({ page }) => {
        // 1. Filter Gedung harus ada
        const filterGedung = page.locator('#id_gedung');
        await expect(filterGedung).toBeVisible();

        // 2. Filter Ruangan harus disabled di awal
        const filterRuangan = page.locator('#id_ruangan');
        await expect(filterRuangan).toBeDisabled();

        // 3. Pilih Gedung (Misal index ke-1 / gedung pertama yang muncul)
        const optionsGedung = await filterGedung.locator('option');
        if (await optionsGedung.count() > 1) {
            await filterGedung.selectOption({ index: 1 });
            
            // Tunggu AJAX reload
            await page.waitForTimeout(1000);

            // 4. Filter Ruangan harusnya jadi ENABLED setelah pilih gedung
            await expect(filterRuangan).toBeEnabled();
            
            // 5. Coba pilih ruangan
            const optionsRuangan = await filterRuangan.locator('option:not([style*="display: none"])');
            if (await optionsRuangan.count() > 1) {
                 // Pilih ruangan pertama yang visible (index 0 biasanya placeholder, jadi index 1)
                 // Note: Playwright selectOption by index might select hidden ones, better use value/label if known.
                 // Here we just verify it is unlocked.
                 await expect(filterRuangan).not.toBeDisabled();
            }
        }
    });

    test('6. Teknisi bisa memfilter Status Fasilitas (Baik/Rusak)', async ({ page }) => {
        const filterStatus = page.locator('#status_fasilitas');
        await expect(filterStatus).toBeVisible();

        // Pilih status "Baik"
        await filterStatus.selectOption('Baik');
        await page.waitForTimeout(1000);
        
        // Pastikan tidak crash
        await expect(page.locator('#fasilitas-container')).toBeVisible();
    });

});