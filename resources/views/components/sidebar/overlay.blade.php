<!-- Mobile Overlay - Muncul kalau sidebar dibuka di mobile -->
<div x-show="isSidebarOpen && isMobile" 
     @click="toggleSidebar()"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-10 bg-black bg-opacity-50 lg:hidden"
     style="display: none;">
</div>