import { test, expect } from '@playwright/test';

test.describe('File Browser', () => {
    test.beforeEach(async ({ page }) => {
        await page.goto('/register');
        const email = `browser-${Date.now()}@test.com`;
        await page.fill('input[name="name"]', 'Browser Test');
        await page.fill('input[type="email"]', email);
        await page.fill('input[name="password"]', 'password123');
        await page.fill('input[name="password_confirmation"]', 'password123');
        await page.locator('button[type="submit"]').click();
        await page.waitForURL('**/browse');
    });

    test('can render file browser', async ({ page }) => {
        await page.goto('/browse');
        await expect(page).toHaveURL(/\/browse/);
        await expect(page.locator('text=Browse Files')).toBeVisible();
    });

    test('shows breadcrumb navigation', async ({ page }) => {
        await page.goto('/browse');
        await expect(page.locator('text=Root')).toBeVisible();
    });

    test('can navigate into folders', async ({ page }) => {
        await page.goto('/browse');

        // Look for any folder and click it
        const folders = page.locator('[wire\\:click^="navigateTo"]');
        const folderCount = await folders.count();

        if (folderCount > 0) {
            await folders.first().click();
            // Should update the breadcrumb
            await page.waitForTimeout(1000);
            // Breadcrumb should have more entries now
            const breadcrumbs = page.locator('nav span, nav button');
            expect(await breadcrumbs.count()).toBeGreaterThan(1);
        }
    });

    test('has up button', async ({ page }) => {
        await page.goto('/browse');
        await expect(page.locator('button', { hasText: 'Up' })).toBeVisible();
    });
});
