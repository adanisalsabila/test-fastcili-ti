// @ts-check
import { expect } from "@playwright/test";

/**
 * Helper functions untuk authentication dalam testing
 */

/**
 * Login sebagai user dengan role tertentu
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {string} email - Email user
 * @param {string} password - Password user
 * @param {string} baseUrl - Base URL aplikasi
 */
export async function loginAs(
    page,
    email,
    password,
    baseUrl = "http://127.0.0.1:8000"
) {
    await page.goto(`${baseUrl}/login`);

    // Fill login form
    await page.fill('input[type="email"]', email);
    await page.fill('input[type="password"]', password);

    // Click login button
    await page.click('button[type="submit"]');

    // Wait for navigation
    await page.waitForLoadState("networkidle");
}

/**
 * Login sebagai pelapor
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {string} baseUrl - Base URL aplikasi
 */
export async function loginAsPelapor(page, baseUrl = "http://127.0.0.1:8000") {
    const PELAPOR_EMAIL = process.env.PELAPOR_EMAIL || "pelapor@jti.com";
    const PELAPOR_PASSWORD = process.env.PELAPOR_PASSWORD || "password";

    await loginAs(page, PELAPOR_EMAIL, PELAPOR_PASSWORD, baseUrl);

    // Verify we're on pelapor page
    await expect(page).toHaveURL(/.*pelapor/, { timeout: 10000 });
}

/**
 * Login sebagai teknisi
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {string} baseUrl - Base URL aplikasi
 */
export async function loginAsTeknisi(page, baseUrl = "http://127.0.0.1:8000") {
    const TEKNISI_EMAIL = process.env.TEKNISI_EMAIL || "teknisi@jti.com";
    const TEKNISI_PASSWORD = process.env.TEKNISI_PASSWORD || "password";

    await loginAs(page, TEKNISI_EMAIL, TEKNISI_PASSWORD, baseUrl);

    // Verify we're on teknisi page
    await expect(page).toHaveURL(/.*teknisi/, { timeout: 10000 });
}

/**
 * Login sebagai admin/sarpras
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {string} baseUrl - Base URL aplikasi
 */
export async function loginAsAdmin(page, baseUrl = "http://127.0.0.1:8000") {
    const ADMIN_EMAIL = process.env.ADMIN_EMAIL || "admin@jti.com";
    const ADMIN_PASSWORD = process.env.ADMIN_PASSWORD || "password";

    await loginAs(page, ADMIN_EMAIL, ADMIN_PASSWORD, baseUrl);

    // Verify we're on admin page
    await expect(page).toHaveURL(/.*home/, { timeout: 10000 });
}

/**
 * Logout dari aplikasi
 * @param {import('@playwright/test').Page} page - Playwright page object
 */
export async function logout(page) {
    // Look for logout link/button
    const logoutButton = page
        .locator(
            'a:has-text("Logout"), a:has-text("Keluar"), button:has-text("Logout")'
        )
        .first();

    if (await logoutButton.isVisible({ timeout: 5000 })) {
        await logoutButton.click();
    } else {
        // Try alternative logout method via dropdown
        const userDropdown = page
            .locator(".dropdown-toggle, .user-menu")
            .first();
        if (await userDropdown.isVisible({ timeout: 5000 })) {
            await userDropdown.click();
            await page.waitForTimeout(500);

            const logoutInDropdown = page
                .locator('a:has-text("Logout"), a:has-text("Keluar")')
                .first();
            if (await logoutInDropdown.isVisible()) {
                await logoutInDropdown.click();
            }
        }
    }

    // Wait for redirect
    await page.waitForTimeout(2000);
}

/**
 * Check if user is logged in
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @returns {Promise<boolean>} True if logged in, false otherwise
 */
export async function isLoggedIn(page) {
    const currentUrl = page.url();
    return !currentUrl.includes("/login") && !currentUrl.endsWith("/");
}

/**
 * Save authentication state to file
 * @param {import('@playwright/test').Page} page - Playwright page object
 * @param {string} path - Path to save auth state
 */
export async function saveAuthState(page, path) {
    await page.context().storageState({ path });
}

/**
 * Load authentication state from file
 * @param {import('@playwright/test').Browser} browser - Playwright browser object
 * @param {string} path - Path to load auth state
 * @returns {Promise<import('@playwright/test').Page>} Page with auth state loaded
 */
export async function loadAuthState(browser, path) {
    const context = await browser.newContext({ storageState: path });
    return await context.newPage();
}
