<?php
/**
 * Helper render logo brand.
 * $variant: 'dark' (untuk bg gelap — text putih) | 'light' (untuk bg terang — text navy)
 * $size: 'sm' | 'md' | 'lg'
 */
function renderBrand(string $variant = 'light', string $size = 'md', string $link = null, string $base_url = 'http://localhost/taking_order_oceans/'): void {
    $sizes = ['sm' => 'h-8', 'md' => 'h-10', 'lg' => 'h-14'];
    $h = $sizes[$size] ?? 'h-10';
    $textColor = $variant === 'dark' ? 'text-white' : 'text-navy-900';
    $href = $link ?? $base_url;
    ?>
    <a href="<?= htmlspecialchars($href); ?>" class="inline-flex items-center gap-3 shrink-0">
      <img src="<?= $base_url ?>assets/logo-brand.png" alt="Taking Order Cafe"
           class="<?= $h ?> w-auto object-contain"
           onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
      <span class="hidden items-center <?= $textColor ?> font-extrabold tracking-tight text-lg" style="display:none">
        Taking Order Cafe
      </span>
    </a>
    <?php
}