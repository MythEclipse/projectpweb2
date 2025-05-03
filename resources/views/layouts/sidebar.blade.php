  <!-- Overlay for Mobile -->
  <div x-show="sidebarOpen && window.innerWidth < 768" x-transition.opacity
  class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'w-64' : '-translate-x-full md:translate-x-0'"
  class="bg-white dark:bg-[#1a1a1a] shadow-lg transition-all duration-300 mt-14 lg:mt-0 overflow-hidden z-40 fixed md:relative min-h-screen md:w-64">
  <div class="p-6">
      <h2 class="text-2xl font-semibold text-pink-600 dark:text-pink-400 mb-6">Menu Admin</h2>
      <ul class="space-y-4 text-gray-700 dark:text-gray-300">
          <li>
              <a data-turbo="false" href="{{ route('admin.products.index') }}"
                 class="hover:text-pink-500 font-medium transition flex items-center gap-2">
                 Products
              </a>
          </li>
          <li>
              <a data-turbo="false" href="{{ route('admin.transactions.index') }}"
                 class="hover:text-pink-500 font-medium transition flex items-center gap-2">
                 transactions
              </a>
          </li>
          <li>
              <a data-turbo="false" href="{{ route('admin.users.index') }}"
                 class="hover:text-pink-500 font-medium transition flex items-center gap-2">
                 users
              </a>
          </li>
      </ul>
  </div>
</aside>
