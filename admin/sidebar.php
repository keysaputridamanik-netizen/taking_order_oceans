<?php
function renderSidebar(string $active = ''): void {
    $items = [
        'dashboard' => ['Dashboard',   'mdi:view-dashboard-outline', 'dashboard.php'],
        'menu'      => ['Kelola Menu', 'mdi:coffee-outline',         'menu.php'],
        'order'     => ['Data Order',  'mdi:receipt-text-outline',   'order.php'],
        'laporan'   => ['Laporan',     'mdi:file-chart-outline',     'laporan.php'],
    ];

    $activeCls   = 'bg-white/[0.06] text-white border-l-2 border-sand-400';
    $inactiveCls = 'text-ice-100/70 hover:text-white hover:bg-white/[0.04] border-l-2 border-transparent';

    // Desktop
    echo '<aside class="hidden md:flex w-64 bg-navy-900 min-h-screen flex-col shrink-0">';
    echo '<div class="px-6 py-6 border-b border-white/[0.06]">';
    echo '<a href="' . BASE_URL . '/admin/dashboard.php" class="brand-lockup">';
    echo '<img src="' . BASE_URL . '/assets/logo-brand.png" alt="Brand" class="h-8 brightness-0 invert">';
    echo '<span class="brand-text text-white text-[0.95rem]">Admin</span>';
    echo '</a></div>';
    echo '<nav class="flex-1 py-4">';
    echo '<div class="px-6 pb-3 text-[0.68rem] uppercase tracking-[0.14em] text-ice-100/40 font-semibold">Menu</div>';
    foreach ($items as $key => [$label, $icon, $url]) {
        $cls = $active === $key ? $activeCls : $inactiveCls;
        echo "<a href=\"" . BASE_URL . "/admin/$url\" class=\"flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition-colors $cls\">"
           . "<iconify-icon icon=\"$icon\" width=\"18\"></iconify-icon> $label</a>";
    }
    echo '</nav>';
    echo '<div class="p-6 border-t border-white/[0.06]">';
    echo '<a href="' . BASE_URL . '/admin/logout.php" class="flex items-center gap-2 text-sm text-ice-100/60 hover:text-white transition-colors">';
    echo '<iconify-icon icon="mdi:logout" width="16"></iconify-icon> Logout</a>';
    echo '</div></aside>';

    // Mobile drawer
    echo '<div id="mobileSidebar" class="fixed inset-0 z-40 hidden md:hidden">';
    echo '<div class="absolute inset-0 bg-black/50" onclick="document.getElementById(\'mobileSidebar\').classList.add(\'hidden\')"></div>';
    echo '<aside class="relative w-64 h-full bg-navy-900 flex flex-col">';
    echo '<div class="px-6 py-6 border-b border-white/[0.06] flex items-center justify-between">';
    echo '<a href="' . BASE_URL . '/admin/dashboard.php" class="brand-lockup">';
    echo '<img src="' . BASE_URL . '/assets/logo-brand.png" alt="Brand" class="h-8 brightness-0 invert">';
    echo '<span class="brand-text text-white text-sm">Admin</span>';
    echo '</a>';
    echo '<button onclick="document.getElementById(\'mobileSidebar\').classList.add(\'hidden\')" class="text-white/50 hover:text-white">';
    echo '<iconify-icon icon="mdi:close" width="20"></iconify-icon></button></div>';
    echo '<nav class="flex-1 py-4">';
    foreach ($items as $key => [$label, $icon, $url]) {
        $cls = $active === $key ? $activeCls : $inactiveCls;
        echo "<a href=\"" . BASE_URL . "/admin/$url\" class=\"flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition-colors $cls\">"
           . "<iconify-icon icon=\"$icon\" width=\"18\"></iconify-icon> $label</a>";
    }
    echo '</nav></aside></div>';
}