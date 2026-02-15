import { test, expect } from '@playwright/test';
import path from 'path';

test.describe('File Upload', () => {
    test.beforeEach(async ({ page }) => {
        // Register and login
        await page.goto('/register');
        const email = `upload-${Date.now()}@test.com`;
        await page.fill('input[name="name"]', 'Upload Test');
        await page.fill('input[type="email"]', email);
        await page.fill('input[name="password"]', 'password123');
        await page.fill('input[name="password_confirmation"]', 'password123');
        await page.locator('button[type="submit"]').click();
        await page.waitForURL('**/browse');
    });

    test('can navigate to upload page', async ({ page }) => {
        await page.goto('/convert');
        await expect(page).toHaveURL(/\/convert/);
        await expect(page.locator('text=Upload a file')).toBeVisible();
    });

    test('can upload a .docx file and convert', async ({ page }) => {
        await page.goto('/convert');

        const filePath = path.resolve('tests/fixtures/sample.docx');
        await page.setInputFiles('input[type="file"]', filePath);

        // Wait for file to be uploaded via Livewire
        await page.waitForTimeout(2000);

        await page.locator('button[type="submit"]').click();

        // Wait for conversion result
        await page.waitForSelector('text=Converted successfully', { timeout: 15000 });
        await expect(page.locator('text=Converted successfully')).toBeVisible();
        await expect(page.locator('text=Download Markdown')).toBeVisible();
    });
});
