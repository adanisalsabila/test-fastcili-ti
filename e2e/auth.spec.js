import { test, expect } from '@playwright/test';

// --- CONFIG USER DUMMY (SESUAI USER SEEDER) ---
const USERS = {
  // Level 1
  admin:   { email: 'admin@jti.com', pass: 'password', expectedUrl: '/home' },
  // Level 3 (Ambil array pertama: Rendi Saputra)
  teknisi: { email: 'rendi.saputra@jti.com', pass: 'password', expectedUrl: '/teknisi' },
  // Level 4 (Ambil array pertama: Yefta Octa)
  pelapor: { email: 'yefta.octa@jti.com', pass: 'password', expectedUrl: '/pelapor' },
};

test.describe('Skenario Autentikasi Fastcili-TI', () => {

  test('Landing page tombol Mulai mengarah ke Login', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/');
    await expect(page.getByText('Fastcili-TI')).toBeVisible();
    
    // Klik tombol Mulai
    await page.getByRole('link', { name: 'Mulai' }).click();
    await expect(page).toHaveURL(/.*\/login/);
    await expect(page.getByRole('heading', { name: 'Login' })).toBeVisible();
  });

  test('Login Admin (Level 1) -> Masuk Dashboard Home', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('input[name="email"]', USERS.admin.email);
    await page.fill('input[name="password"]', USERS.admin.pass);
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(new RegExp(USERS.admin.expectedUrl));
  });

  test('Login Teknisi (Level 3) -> Masuk Dashboard Teknisi', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('input[name="email"]', USERS.teknisi.email);
    await page.fill('input[name="password"]', USERS.teknisi.pass);
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(new RegExp(USERS.teknisi.expectedUrl));
  });

  test('Login Mahasiswa (Level 4) -> Masuk Dashboard Pelapor', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('input[name="email"]', USERS.pelapor.email);
    await page.fill('input[name="password"]', USERS.pelapor.pass);
    await page.click('button[type="submit"]');
    await expect(page).toHaveURL(new RegExp(USERS.pelapor.expectedUrl));
  });

  test('Login Gagal (Password Salah)', async ({ page }) => {
    await page.goto('http://127.0.0.1:8000/login');
    await page.fill('input[name="email"]', USERS.admin.email);
    await page.fill('input[name="password"]', 'password_salah_total');
    await page.click('button[type="submit"]');
    
    // Pastikan tetap di halaman login & ada alert
    await expect(page).toHaveURL(/.*\/login/);
    // Mencari elemen alert danger (merah)
    await expect(page.locator('.alert.alert-danger')).toBeVisible();
  });

});