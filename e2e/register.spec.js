import {test, expect} from '@playwright/test';

test('User dapat melakukan registrasi dan diarahkan ke halaman login', async ({page}) => {

    test.setTimeout(60000);

    await page.goto('https://fasilitas.rf.gd/register', {timeout: 60000});

    await page.fill('input[placeholder="Nama Lengkap"]', 'danielhaqq');
    await page.fill('input[placeholder="Email"]', 'daniel@jti.com');
    await page.fill('input[placeholder="Password"]', 'jtipolinema');
    await page.fill('input[placeholder="Konfirmasi Password"]', 'jtipolinema');

    await page.locator('label.form-check-label').click();

    await page.click('button:has-text("REGISTER")');

    page.on('dialog', async dialog => {
        expect(dialog.message()).toContain('OK');
        await dialog.accept();
    })

    await expect(page).toHaveURL('https://fasilitas.rf.gd/login');
})