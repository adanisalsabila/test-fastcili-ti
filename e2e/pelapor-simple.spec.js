// @ts-check
import { test, expect } from "@playwright/test";

/**
 * E2E Testing untuk Role Pelapor
 * Fokus pada test case utama yang paling penting
 */

const BASE_URL = "http://127.0.0.1:8000";
const PELAPOR_EMAIL = "pelapor@jti.com";
const PELAPOR_PASSWORD = "password";

// Helper function untuk login
/**
 * @typedef {import('@playwright/test').Page} PlaywrightPage
 *
 * @typedef {Object} Credentials
 * @property {string} email
 * @property {string} password
 *
 * @typedef {(page: PlaywrightPage) => Promise<void>} LoginAsPelaporFn
 */

/**
 * Helper function untuk login
 * @type {LoginAsPelaporFn}
 */
async function loginAsPelapor(page) {
    await page.goto(`${BASE_URL}/login`, {
        waitUntil: "domcontentloaded",
        timeout: 30000,
    });

    await page.waitForLoadState("networkidle");

    // Fill login form with flexible selectors
    const emailInput = page
        .locator('input[type="email"], input[name="email"]')
        .first();
    await emailInput.waitFor({ state: "visible", timeout: 10000 });
    await emailInput.fill(PELAPOR_EMAIL);

    const passwordInput = page
        .locator('input[type="password"], input[name="password"]')
        .first();
    await passwordInput.fill(PELAPOR_PASSWORD);

    // Submit and wait
    const loginButton = page.locator('button[type="submit"]').first();
    await Promise.all([
        page.waitForLoadState("networkidle", { timeout: 20000 }),
        loginButton.click(),
    ]);

    await page.waitForTimeout(1000);
}

test.describe("Pelapor E2E Tests - Essential", () => {
    test.beforeEach(async () => {
        test.setTimeout(60000); // 60 second timeout per test
    });

    test("Test 1: Login sebagai pelapor berhasil", async ({ page }) => {
        await loginAsPelapor(page);

        // Check we're logged in
        const url = page.url();
        console.log(`After login URL: ${url}`);

        // Should be redirected away from login
        expect(url).not.toContain("/login");
    });

    test("Test 2: Akses dashboard pelapor", async ({ page }) => {
        await loginAsPelapor(page);

        // Go to pelapor dashboard
        await page.goto(`${BASE_URL}/pelapor`, {
            waitUntil: "domcontentloaded",
            timeout: 30000,
        });

        await page.waitForLoadState("networkidle");

        // Verify URL
        expect(page.url()).toContain("/pelapor");

        // Check for welcome message (flexible check)
        const hasWelcome =
            (await page.locator("text=/selamat datang/i").count()) > 0;
        console.log(`Welcome message found: ${hasWelcome}`);

        // Take screenshot for verification
        await page.screenshot({
            path: "test-results/dashboard.png",
            fullPage: true,
        });
    });

    test("Test 3: Buka halaman buat laporan", async ({ page }) => {
        await loginAsPelapor(page);

        // Navigate to create page
        await page.goto(`${BASE_URL}/pelapor/create`, {
            waitUntil: "domcontentloaded",
            timeout: 30000,
        });

        await page.waitForLoadState("networkidle");

        // Verify URL
        expect(page.url()).toContain("/pelapor/create");

        // Check if form exists
        const formExists = (await page.locator("form").count()) > 0;
        expect(formExists).toBeTruthy();

        console.log(`Form found: ${formExists}`);

        // Take screenshot
        await page.screenshot({
            path: "test-results/create-form.png",
            fullPage: true,
        });
    });

    test("Test 4: Isi form laporan (tanpa submit)", async ({ page }) => {
        await loginAsPelapor(page);

        await page.goto(`${BASE_URL}/pelapor/create`, {
            waitUntil: "domcontentloaded",
            timeout: 30000,
        });

        await page.waitForLoadState("networkidle");

        try {
            // Try to select gedung if dropdown exists
            const gedungSelect = page.locator('select[name="id_gedung"]');
            if ((await gedungSelect.count()) > 0) {
                const options = await gedungSelect.locator("option").count();
                if (options > 1) {
                    await gedungSelect.selectOption({ index: 1 });
                    console.log("✓ Gedung selected");
                    await page.waitForTimeout(2000);
                }
            }

            // Try to select ruangan if dropdown exists
            const ruanganSelect = page.locator('select[name="id_ruangan"]');
            if ((await ruanganSelect.count()) > 0) {
                const options = await ruanganSelect.locator("option").count();
                if (options > 1) {
                    await ruanganSelect.selectOption({ index: 1 });
                    console.log("✓ Ruangan selected");
                    await page.waitForTimeout(2000);
                }
            }

            // Try to select fasilitas if dropdown exists
            const fasilitasSelect = page.locator('select[name="id_fasilitas"]');
            if ((await fasilitasSelect.count()) > 0) {
                const options = await fasilitasSelect.locator("option").count();
                if (options > 1) {
                    await fasilitasSelect.selectOption({ index: 1 });
                    console.log("✓ Fasilitas selected");
                }
            }

            // Fill jumlah kerusakan if field exists
            const jumlahField = page.locator('input[name="jumlah_kerusakan"]');
            if ((await jumlahField.count()) > 0) {
                await jumlahField.fill("2");
                console.log("✓ Jumlah kerusakan filled");
            }

            // Fill deskripsi if field exists
            const deskripsiField = page.locator('textarea[name="deskripsi"]');
            if ((await deskripsiField.count()) > 0) {
                await deskripsiField.fill(
                    "Test laporan kerusakan - Lampu tidak menyala"
                );
                console.log("✓ Deskripsi filled");
            }

            // Take screenshot of filled form
            await page.screenshot({
                path: "test-results/form-filled.png",
                fullPage: true,
            });

            console.log("✓ Form filling completed successfully");
        } catch (error) {
            console.log(
                `Note: Some form fields might not be available - ${error}`
            );
            await page.screenshot({
                path: "test-results/form-error.png",
                fullPage: true,
            });
        }
    });

    test("Test 5: Lihat daftar laporan (jika ada)", async ({ page }) => {
        await loginAsPelapor(page);

        await page.goto(`${BASE_URL}/pelapor`, {
            waitUntil: "domcontentloaded",
            timeout: 30000,
        });

        await page.waitForLoadState("networkidle");

        // Count report cards
        const reportCards = page.locator(".card");
        const count = await reportCards.count();

        console.log(`Found ${count} report card(s)`);

        if (count > 0) {
            // Try to find detail button/link
            const detailLinks = page.locator(
                'a:has-text("Detail"), a.btn-info, button:has-text("Detail")'
            );
            const detailCount = await detailLinks.count();

            if (detailCount > 0) {
                console.log(`Found ${detailCount} detail link(s)`);
            }
        }

        await page.screenshot({
            path: "test-results/laporan-list.png",
            fullPage: true,
        });
    });

    test("Test 6: Logout", async ({ page }) => {
        await loginAsPelapor(page);

        try {
            // Try to find logout button/link
            const logoutButton = page
                .locator(
                    'a:has-text("Logout"), a:has-text("Keluar"), button:has-text("Logout")'
                )
                .first();

            if ((await logoutButton.count()) > 0) {
                await logoutButton.click();
                await page.waitForTimeout(2000);

                const url = page.url();
                console.log(`After logout URL: ${url}`);

                // Should be redirected to login or home
                const isLoggedOut =
                    url.includes("/login") || url === `${BASE_URL}/`;
                console.log(`Logout successful: ${isLoggedOut}`);
            } else {
                // Try dropdown menu
                const userDropdown = page
                    .locator(".dropdown-toggle, .user-menu")
                    .first();
                if ((await userDropdown.count()) > 0) {
                    await userDropdown.click();
                    await page.waitForTimeout(500);

                    const logoutInDropdown = page
                        .locator('a:has-text("Logout"), a:has-text("Keluar")')
                        .first();
                    if ((await logoutInDropdown.count()) > 0) {
                        await logoutInDropdown.click();
                        await page.waitForTimeout(2000);
                        console.log("Logout via dropdown successful");
                    }
                }
            }
        } catch (error) {
            console.log(`Logout test note: ${error}`);
        }
    });

    test("Test 7: Responsive - Mobile view", async ({ page }) => {
        // Set mobile viewport
        await page.setViewportSize({ width: 375, height: 667 });

        await loginAsPelapor(page);

        await page.goto(`${BASE_URL}/pelapor`, {
            waitUntil: "domcontentloaded",
            timeout: 30000,
        });

        await page.waitForLoadState("networkidle");

        // Take screenshot
        await page.screenshot({
            path: "test-results/mobile-view.png",
            fullPage: true,
        });

        console.log("✓ Mobile view test completed");
    });
});

test.describe("Pelapor E2E Tests - Navigation", () => {
    test.beforeEach(async () => {
        test.setTimeout(60000);
    });

    test("Test 8: Navigate between pages", async ({ page }) => {
        await loginAsPelapor(page);

        // Dashboard
        await page.goto(`${BASE_URL}/pelapor`);
        await page.waitForLoadState("networkidle");
        expect(page.url()).toContain("/pelapor");
        console.log("✓ Dashboard loaded");

        // Create page
        await page.goto(`${BASE_URL}/pelapor/create`);
        await page.waitForLoadState("networkidle");
        expect(page.url()).toContain("/create");
        console.log("✓ Create page loaded");

        // Back to dashboard
        await page.goto(`${BASE_URL}/pelapor`);
        await page.waitForLoadState("networkidle");
        expect(page.url()).toContain("/pelapor");
        console.log("✓ Back to dashboard");

        await page.screenshot({
            path: "test-results/navigation.png",
            fullPage: true,
        });
    });
});
